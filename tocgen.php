<?php

/**
 * Tocgen
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tocgen
 * @author Henry Ruhs
 */

class Tocgen
{
	/**
	 * paths
	 * @var array
	 */

	private $_paths = array(
		'target' => '.',
		'config' => '.tocgen'
	);

	/**
	 * config
	 * @var object
	 */

	private $_config;

	/**
	 * wording
	 * @var array
	 */

	protected $_wording;

	/**
	 * options
	 * @var array
	 */

	protected $_options;

	/**
	 * target
	 * @var object
	 */

	private $_target;

	/**
	 * construct
	 *
	 * @since 3.0.0
	 *
	 * @param array $argv
	 */

	public function __construct($argv = array())
	{
		/* call init */

		$this->init($argv);
	}

	/**
	 * init
	 *
	 * @since 3.0.0
	 *
	 * @param array $argv
	 */

	public function init($argv = array())
	{
		/* handle arguments */

		if (isset($argv[1]) && file_exists($argv[1]))
		{
			$this->_paths['target'] = realpath($argv[1]);
		}
		if (isset($argv[2]) && file_exists($argv[2]))
		{
			$this->_paths['config'] = basename($argv[2]);
		}

		/* load config */

		$this->_config = file_get_contents($this->_paths['config']);
		$this->_config = json_decode($this->_config, true);

		/* overwrite options */

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

		/* scan target */

		$this->_target = $this->_scanTarget($this->_paths['target'], $this->_config['exclude']);
	}

	/**
	 * getTarget
	 *
	 * @since 3.0.0
	 */

	public function getTarget()
	{
		return $this->_target;
	}

	/**
	 * scanTarget
	 *
	 * @since 3.0.0
	 *
	 * @param string $target
	 * @param array $exclude
	 */

	protected function _scanTarget($target = '', $exclude = array())
	{
		$directoryArray = scandir($target);
		$directoryArray = array_diff($directoryArray, $exclude);

		/* scan recursive */

		if ($this->_options['recursive'] === true)
		{
			foreach ($directoryArray as $key => $value)
			{
				$targetSub = $target . '/' . $value;
				if (is_dir($targetSub))
				{
					$directoryArray[$key] = $this->_scanTarget($targetSub, $exclude);
				}
				else
				{
					$directoryArray[$key] = $targetSub;
				}
			}
		}
		return $directoryArray;
	}

	/**
	 * process
	 *
	 * @since 3.0.0
	 */

	public function process()
	{
		$output = '';
		$target = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->_target));

		/* handle target */

		foreach ($target as $file)
		{
			$extension = pathinfo($file, PATHINFO_EXTENSION);

			/* check extension */

			if (in_array($extension, $this->_config['extensions']))
			{
				$output .= $this->_writeToc($file);
			}
		}
		return $output;
	}

	/**
	 * writeToc
	 *
	 * @since 3.0.0
	 *
	 * @param string $file
	 */

	protected function _writeToc($file = '')
	{
		/* parse contents */

		$parseContents = $this->_parseContents($file);
		$contents = $parseContents['contents'];
		$tocParts = $parseContents['tocParts'];

		/* parse sections */

		$parseSections = $this->_parseSections($file, $contents);
		$errors = $parseSections['errors'];
		$tocNew = $parseSections['tocNew'];

		return $this->_console($file) . PHP_EOL;
	}

	/**
	 * parseContents
	 *
	 * @since 3.0.0
	 *
	 * @param string $file
	 */

	protected function _parseContents($file = '')
	{
		$output = array(
		    'contents' => '',
		    'tocParts' => ''
		);

		/* get contents */

		$output['contents'] = file_get_contents($file);
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
	 * parseSections
	 *
	 * @since 3.0.0
	 */

	protected function _parseSections($file = '', $contents = '')
	{
		$output = array(
		    'errors' => '',
		    'tocNew' => ''
		);

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
				$sectionRankNew = preg_replace('/[^0-9' . $this->_config['section']['delimiter'] . ']/', '', $sectionValue);
				$sectionRankOld = '';
				$sectionRankExplode = explode($this->_config['section']['delimiter'], $sectionRankNew);

				/* collect new toc */

				$output['tocNew'] .= $this->_config['toc']['prefix'];

				/* duplicate rank */

				if (version_compare($sectionRankNew, $sectionRankOld, '=='))
				{
					$output['errors'] .= $this->_config['wording']['duplicateRank'] . $this->_config['wording']['colon'] . ' ' . $file . PHP_EOL;
				}

				/* wrong order */

				else if (version_compare($sectionRankNew, $sectionRankOld, '<'))
				{
					$output['errors'] .= $this->_config['wording']['wrongOrder'] . $this->_config['wording']['colon'] . ' ' . $file . PHP_EOL;
				}

				/* indent rank */

				else if (is_array($sectionRankExplode))
				{
					foreach ($sectionRankExplode as $rankKey => $rankValue)
					{
						if (is_numeric($rankValue) && $rankKey !== 0)
						{
							$output['tocNew'] .= $this->_config['toc']['indent'];
						}
					}
				}
				$output['tocNew'] .= $sectionValue . $this->_config['eol'];

				/* store old rank */

				$sectionRankOld = $sectionRankNew;
			}
		}
		return $output;
	}

	/**
	 * console
	 *
	 * @since 3.0.0
	 *
	 * @param string $message
	 * @param string $mode
	 */

	protected function _console($message = '', $mode = 'success')
	{
		$output = $message;
		$operatingSystem = strtolower(php_uname('s'));

		/* linux is present */

		if ($operatingSystem == 'linux')
		{
			if ($message && key_exists($mode, $this->_config['colors']))
			{
				$output = chr(27) . $this->_config['colors'][$mode] . $message . chr(27) . '[0m';
			}
		}
		return $output;
	}
}
?>