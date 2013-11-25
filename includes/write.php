<?php

/**
 * write toc
 *
 * @since 2.1
 *
 * @package Tocgen
 * @category Write
 * @author Henry Ruhs
 *
 * @param string $path
 */

function write_toc($path = '')
{
	global $config;

	/* get contents */

	$contents = $contents_old = file_get_contents($path);
	$contents_explode = explode($config['toc']['end'], $contents, 2);

	/* remove present toc block */

	if ($contents_explode[1])
	{
		$position_toc = strpos($contents_explode[0], $config['toc']['flag']);

		/* if toc check passed */

		if ($position_toc > -1)
		{
			/* store toc parts */

			$toc_list_parts_array = explode($config['toc']['delimiter'], $contents_explode[0]);

			/* store contents */

			$contents = trim($contents_explode[1]);
		}
	}

	/* get all matches */

	preg_match_all($config['section']['pattern'], $contents, $matches);
	$matches = $matches[0];

	/* prepare matches */

	$section_parts = array(
		$config['section']['start'],
		$config['section']['end'],
		$config['section']['prefix']
	);

	/* process matches */

	foreach ($matches as $key => $value)
	{
		$value = trim(str_replace($section_parts, '', $value));
		$position_section = strpos($value, $config['section']['flag']);

		/* if section */

		if ($position_section > -1)
		{
			$value = trim(str_replace($config['section']['flag'], '', $value));
			$section_explode = explode('.', $value);
			if ($section_explode[0])
			{
				$section_sub_new = $section_explode[0];
			}

			/* if sub section */

			if ($section_sub_old == $section_sub_new)
			{
				$value = $config['toc']['indent'] . $value;
			}
			$section_sub_old = $section_sub_new;

			/* collect new toc list */

			$toc_list_new .= $config['toc']['prefix'] . $value . $config['eol'];
		}
	}

	/* process new toc list */

	if ($toc_list_new)
	{
		/* if equal toc list */

		if ($config['options']['force'] === false && in_array($toc_list_new, $toc_list_parts_array))
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
			$contents_new = $config['toc']['start'] . $config['toc']['head'] . $toc_list_new . $config['toc']['foot'] . $config['toc']['end'] . $contents;
			file_put_contents($path, $contents_new);
		}
	}

	/* else handle error */

	else if ($config['options']['quite'] === false)
	{
		echo console($config['wording']['noSection'] . $config['wording']['colon'], 'error') . ' ' . $path . PHP_EOL;
	}
}
?>