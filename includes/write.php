<?php

/**
 * write toc
 *
 * @since 2.3.0
 *
 * @package Tocgen
 * @category Write
 * @author Henry Ruhs
 *
 * @param string $path
 */

function writeToc($path = '')
{
	global $config;

	/* get contents */

	$contents = $contentsOld = file_get_contents($path);
	$contentsExplode = explode($config['toc']['end'], $contents, 2);

	/* remove present toc */

	if ($contentsExplode[1])
	{
		$positionToc = strpos($contentsExplode[0], $config['toc']['flag']);

		/* if toc check passed */

		if ($positionToc > -1)
		{
			/* store toc parts */

			$tocParts = explode($config['toc']['delimiter'], $contentsExplode[0]);

			/* store contents */

			$contents = trim($contentsExplode[1]);
		}
	}

	/* get all section matches */

	preg_match_all($config['section']['pattern'], $contents, $sectionMatches);
	$sectionMatches = $sectionMatches[0];

	/* prepare section matches */

	$sectionParts = array(
		$config['section']['start'],
		$config['section']['end']
	);

	/* process section matches */

	foreach ($sectionMatches as $sectionValue)
	{
		$sectionValue = trim(str_replace($sectionParts, '', $sectionValue));
		$positionSection = strpos($sectionValue, $config['section']['flag']);

		/* section is present */

		if ($positionSection > -1)
		{
			$sectionValue = trim(str_replace($config['section']['flag'], '', $sectionValue));
			$sectionRankNew = preg_replace('/[^0-9' . $config['section']['delimiter'] . ']/', '', $sectionValue);
			$sectionRankExplode = explode($config['section']['delimiter'], $sectionRankNew);

			/* collect new toc */

			$tocNew .= $config['toc']['prefix'];

			/* duplicate rank */

			if (version_compare($sectionRankNew, $sectionRankOld, '=='))
			{
				if ($config['options']['quite'] === false)
				{
					echo console($config['wording']['duplicateRank'] . $config['wording']['colon'], 'error') . ' ' . $path . PHP_EOL;
				}
				$tocNew .= $config['wording']['duplicateRank'] . $config['wording']['colon'] . ' ';
			}

			/* wrong order */

			else if (version_compare($sectionRankNew, $sectionRankOld, '<'))
			{
				if ($config['options']['quite'] === false)
				{
					echo console($config['wording']['wrongOrder'] . $config['wording']['colon'], 'error') . ' ' . $path . PHP_EOL;
				}
				$tocNew .= $config['wording']['wrongOrder'] . $config['wording']['colon'] . ' ';
			}

			/* indent rank */

			else if (is_array($sectionRankExplode))
			{
				foreach ($sectionRankExplode as $rankKey => $rankValue)
				{
					if (is_numeric($rankValue) && $rankKey !== 0)
					{
						$tocNew .= $config['toc']['indent'];
					}
				}
			}
			$tocNew .= $sectionValue . $config['eol'];

			/* store old rank */

			$sectionRankOld = $sectionRankNew;
		}
	}

	/* process new toc */

	if ($tocNew)
	{
		/* if equal toc */

		if ($config['options']['force'] === false && in_array($tocNew, $tocParts))
		{
			/* handle warning */

			if ($config['options']['quite'] === false)
			{
				echo console($config['wording']['noChanges'] . $config['wording']['colon'], 'warning') . ' ' . $path . PHP_EOL;
			}
		}

		/* else update toc */

		else
		{
			if ($config['options']['quite'] === false)
			{
				echo console($config['wording']['tocUpdated'] . $config['wording']['colon'], 'success') . ' ' . $path . PHP_EOL;
			}
			$contentsNew = $config['toc']['start'] . $config['toc']['head'] . $tocNew . $config['toc']['foot'] . $config['toc']['end'] . $contents;
			file_put_contents($path, $contentsNew);
		}
	}

	/* else handle error */

	else if ($config['options']['quite'] === false)
	{
		echo console($config['wording']['noSection'] . $config['wording']['colon'], 'warning') . ' ' . $path . PHP_EOL;
	}
}
?>