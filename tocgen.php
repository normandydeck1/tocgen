<?php

/**
 * parent class to generate table of contents from multiple css and js files
 *
 * @since 4.0.0
 *
 * @package Tocgen
 * @author Henry Ruhs
 */

class Tocgen
{
	/**
	 * paths
	 *
	 * @var array
	 */

	private $_paths = array(
		'target' => '.',
		'config' => '.tocgen'
	);

	/**
	 * config
	 *
	 * @var object
	 */

	private $_config;

	/**
	 * wording
	 *
	 * @var object
	 */

	protected $_wording;

	/**
	 * options
	 *
	 * @var array
	 */

	protected $_options;

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $argv
	 * @param string $baseDirectory
	 */

	public function __construct($argv = array(), $baseDirectory = null)
	{
		/* call init */

		$this->init($argv, $baseDirectory);
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $argv
	 * @param string $baseDirectory
	 */

	public function init($argv = array(), $baseDirectory = null)
	{
		$config = '';

		/* handle first argument */

		if (isset($argv[1]) && file_exists($argv[1]))
		{
			$this->_paths['target'] = realpath($argv[1]);
		}

		/* handle second argument */

		if (isset($argv[2]) && file_exists($argv[2]))
		{
			$contents = file_get_contents($argv[2]);
			$config = json_decode($contents, true);
		}

		/* load config */

		$this->_config = file_get_contents($baseDirectory . '/' . $this->_paths['config']);
		$this->_config = json_decode($this->_config, true);

		/* merge config */

		if (is_array($config))
		{
			$this->_config = array_replace_recursive($this->_config, $config);
		}

		/* override options */

		foreach ($this->_config['options'] as $optionKey => $optionValue)
		{
			if (in_array('--' . $optionKey, $argv) || in_array('-' . substr($optionKey, 0, 1), $argv))
			{
				$this->_config['options'][$optionKey] = true;
			}
		}

		/* create shortcuts */

		$this->_wording = $this->_config['wording'];
		$this->_options = $this->_config['options'];
	}

	/**
	 * process the target
	 *
	 * @since 4.0.0
	 *
	 * @return null|string
	 */

	public function process()
	{
		$output = PHP_EOL . $this->_console($this->_wording['tocgen'], 'info') . PHP_EOL;

		/* handle file */

		if (is_file($this->_paths['target']))
		{
			$file = new SplFileObject($this->_paths['target']);

			/* extension and exclude */

			if(in_array($file->getExtension(), $this->_config['extensions']) && !in_array($file, $this->_config['exclude']))
			{
				$output .= $this->_writeToc($file->getPathname());
			}
		}

		/* handle directory */

		else if (is_dir($this->_paths['target']))
		{
			if ($this->_options['recursive'] === true)
			{
				$directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->_paths['target']));
			}
			else
			{
				$directory = new DirectoryIterator($this->_paths['target']);
			}

			/* process directory */

			foreach ($directory as $file)
			{
				/* extension and exclude */

				if(in_array($file->getExtension(), $this->_config['extensions']) && !in_array($file, $this->_config['exclude']))
				{
					$output .= $this->_writeToc($file->getPathname());
				}
			}
		}

		/* else handle error */

		else
		{
			$output .= PHP_EOL . $this->_console($this->_wording['noTarget'], 'error') . PHP_EOL;
		}

		/* quite option */

		if ($this->_options['quite'] === true)
		{
			$output = null;
		}
		return $output;
	}

	/**
	 * write table of contents
	 *
	 * @since 4.0.0
	 *
	 * @param string $path
	 *
	 * @return string
	 */

	protected function _writeToc($path = null)
	{
		$output = '';

		/* parse contents */

		$parseContents = $this->_parseContents($path);
		$contents = $parseContents['contents'];
		$tocParts = $parseContents['tocParts'];

		/* parse sections */

		$parseSections = $this->_parseSections($contents);

		/* transport section errors */

		$notes = array(
			'error' => $parseSections['error'],
			'success' => array(),
			'warning' => array()
		);
		$tocNew = $parseSections['tocNew'];

		/* process new toc */

		if ($tocNew)
		{
			/* equal toc */

			if ($this->_options['force'] === false && in_array($tocNew, $tocParts))
			{
				$notes['warning'][] = $this->_wording['noChanges'];
			}

			/* write toc */

			else
			{
				$this->_lintExit(1);
				$notes['success'][] = $this->_wording['tocUpdated'];
				$contentsNew = $this->_config['toc']['start'] . $this->_config['toc']['head'] . $tocNew . $this->_config['toc']['foot'] . $this->_config['toc']['end'] . $contents;
				file_put_contents($path, $contentsNew);
			}
		}

		/* else handle error */

		else
		{
			$this->_lintExit(1);
			$notes['error'][] = $this->_wording['noSection'];
		}

		/* handle notes */

		$noteCounter = 1;
		$output .= PHP_EOL . $path . $this->_wording['colon'];
		if ($this->_options['lint'] === false)
		{
			foreach ($notes as $noteKey => $noteValue)
			{
				foreach ($noteValue as $noteSubValue)
				{
					$output .= PHP_EOL . $this->_wording['indent'] . $this->_console($noteCounter++ . $this->_wording['point'] . ' ' . $noteSubValue, $noteKey) . PHP_EOL;
				}
			}
		}
		return $output;
	}

	/**
	 * parse contents
	 *
	 * @since 4.0.0
	 *
	 * @param string $path
	 *
	 * @return array
	 */

	protected function _parseContents($path = null)
	{
		$output = array(
			'contents' => '',
			'tocParts' => ''
		);

		/* get contents */

		$output['contents'] = file_get_contents($path);
		$contentsExplode = explode($this->_config['toc']['end'], $output['contents'], 2);

		/* remove present toc */

		if (isset($contentsExplode[0]) && isset($contentsExplode[1]))
		{
			$positionToc = strpos($contentsExplode[0], $this->_config['toc']['flag']);

			/* if toc check passed */

			if ($positionToc > -1)
			{
				$output['contents'] = trim($contentsExplode[1]);
				$output['tocParts'] = explode($this->_config['toc']['delimiter'], $contentsExplode[0]);
			}
		}
		return $output;
	}

	/**
	 * parse sections
	 *
	 * @since 4.0.0
	 *
	 * @param string $contents
	 *
	 * @return array
	 */

	protected function _parseSections($contents = null)
	{
		$output = array(
			'error' => array(),
			'tocNew' => ''
		);
		$rankOld = '';

		/* get section matches */

		preg_match_all($this->_config['section']['pattern'], $contents, $sectionMatches);
		$sectionMatches = $sectionMatches[0];

		/* prepare section matches */

		$sectionParts = array(
			$this->_config['section']['start'],
			$this->_config['section']['end']
		);

		/* process section matches */

		foreach ($sectionMatches as $sectionValue)
		{
			$sectionValue = trim(str_replace($sectionParts, '', $sectionValue));
			$positionSection = strpos($sectionValue, $this->_config['section']['flag']);

			/* section is present */

			if ($positionSection > -1)
			{
				$sectionValue = trim(str_replace($this->_config['section']['flag'], '', $sectionValue));

				/* get rank matches */

				preg_match('/[0-9' . $this->_config['section']['delimiter'] . ']+/', $sectionValue, $rankMatches);
				$rankNew = $rankMatches[0];

				/* collect new toc */

				$output['tocNew'] .= $this->_config['toc']['prefix'];

				/* duplicate rank */

				if (version_compare($rankNew, $rankOld, '=='))
				{
					$this->_lintExit(1);
					$output['error'][] = $this->_wording['duplicateRank'] . $this->_config['wording']['colon'] . ' ' . $sectionValue;
				}

				/* wrong order */

				else if (version_compare($rankNew, $rankOld, '<'))
				{
					$this->_lintExit();
					$output['error'][] = $this->_wording['wrongOrder'] . $this->_config['wording']['colon'] . ' ' . $sectionValue;
				}

				/* indent rank */

				else
				{
					$rankExplode = explode($this->_config['section']['delimiter'], $rankNew);

					/* handle rank explode */

					foreach ($rankExplode as $rankKey => $rankValue)
					{
						if (is_numeric($rankValue) && $rankKey > 0)
						{
							$output['tocNew'] .= $this->_config['toc']['indent'];
						}
					}
				}
				$output['tocNew'] .= $sectionValue . $this->_config['eol'];

				/* store old rank */

				$rankOld = $rankNew;
			}
		}
		return $output;
	}

	/**
	 * lint exit
	 *
	 * @since 4.0.0
	 *
	 * @param string $status
	 */

	protected function _lintExit($status = 1)
	{
		if ($this->_options['lint'] === true)
		{
			exit($status);
		}
	}

	/**
	 * console
	 *
	 * @since 4.0.0
	 *
	 * @param string $message
	 * @param string $mode
	 *
	 * @return null|string
	 */

	protected function _console($message = null, $mode = 'success')
	{
		$output = $message;
		$operatingSystem = strtolower(php_uname('s'));

		/* linux is present */

		if ($operatingSystem === 'linux')
		{
			if ($message && array_key_exists($mode, $this->_config['notes']))
			{
				$output = chr(27) . $this->_config['notes'][$mode] . $message . chr(27) . '[0m';
			}
		}
		return $output;
	}
}
