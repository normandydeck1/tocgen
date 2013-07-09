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
	/* get contents */

	$contents = $contents_old = file_get_contents($path);
	$contents_explode = explode(TOCGEN_TOC_END, $contents, 2);

	/* remove present toc block */

	if ($contents_explode[1])
	{
		$position_toc = strpos($contents_explode[0], TOCGEN_TOC_FLAG);

		/* if toc check passed */

		if ($position_toc > -1)
		{
			/* store toc parts */

			$toc_list_parts_array = explode(TOCGEN_TOC_DELIMITER, $contents_explode[0]);

			/* store contents */

			$contents = trim($contents_explode[1]);
		}
	}

	/* get all matches */

	preg_match_all(TOCGEN_SECTION_REGEX, $contents, $matches);
	$matches = $matches[0];

	/* prepare matches */

	$section_parts = array(
		TOCGEN_SECTION_START,
		TOCGEN_SECTION_END,
		TOCGEN_SECTION_PREFIX
	);

	/* process matches */

	foreach ($matches as $key => $value)
	{
		$value = trim(str_replace($section_parts, '', $value));
		$position_section = strpos($value, TOCGEN_SECTION_FLAG);

		/* if section */

		if ($position_section > -1)
		{
			$value = trim(str_replace(TOCGEN_SECTION_FLAG, '', $value));
			$section_explode = explode('.', $value);
			if ($section_explode[0])
			{
				$section_sub_new = $section_explode[0];
			}

			/* if sub section */

			if ($section_sub_old == $section_sub_new)
			{
				$value = TOCGEN_TOC_INDENT . $value;
			}
			$section_sub_old = $section_sub_new;

			/* collect new toc list */

			$toc_list_new .= TOCGEN_TOC_PREFIX . $value . TOCGEN_EOL;
		}
	}

	/* process new toc list */

	if ($toc_list_new)
	{
		/* if equal toc list */

		if (TOCGEN_FORCE == 0 && in_array($toc_list_new, $toc_list_parts_array))
		{
			/* handle warning */

			if (TOCGEN_QUITE == 0)
			{
				echo console(TOCGEN_NO_CHANGES . TOCGEN_COLON, 'warning') . ' ' . $path . PHP_EOL;
			}
		}

		/* else update toc */

		else
		{
			if (TOCGEN_QUITE == 0)
			{
				echo console(TOCGEN_TOC_UPDATED . TOCGEN_COLON, 'success') . ' ' . $path . PHP_EOL;
			}
			$contents_new = TOCGEN_TOC_START . TOCGEN_TOC_HEAD . $toc_list_new . TOCGEN_TOC_FOOT . TOCGEN_TOC_END . $contents;
			file_put_contents($path, $contents_new);
		}
	}

	/* else handle error */

	else if (TOCGEN_QUITE == 0)
	{
		echo console(TOCGEN_NO_SECTION . TOCGEN_COLON, 'error') . ' ' . $path . PHP_EOL;
	}
}
?>
