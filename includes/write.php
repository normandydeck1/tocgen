<?php

/**
 * Write Toc
 *
 * @since 2.0
 *
 * @category Write
 * @package Tocgen
 * @author Henry Ruhs
 *
 * @param $path string
 */

function write_toc($path = '')
{
	/* get contents */

	$contents = $contents_old = file_get_contents($path);
	$contents_explode = explode(TOCGEN_TOC_END, $contents, 2);

	/* remove present toc block */

	if ($contents_explode[1])
	{
		$position_toc_check = strpos($contents_explode[0], TOCGEN_TOC_CHECK);

		/* if toc check passed */

		if ($position_toc_check > -1)
		{
			/* store old toc list */

			$toc_list_old = explode(TOCGEN_TOC_DELIMITER, $contents_explode[0], 3);
			$toc_list_old = $toc_list_old[1];

			/* store contents */

			$contents = trim($contents_explode[1]);
		}
	}

	/* get all matches */

	preg_match_all(TOCGEN_COMMENT_REGEX, $contents, $matches);
	$matches = $matches[0];

	/* prepare matches */

	$comment_parts = array(
		TOCGEN_COMMENT_START,
		TOCGEN_COMMENT_END,
		TOCGEN_COMMENT_PREFIX
	);

	/* process matches */

	foreach ($matches as $key => $value)
	{
		$value = trim(str_replace($comment_parts, '', $value));
		$position_section = strpos($value, TOCGEN_COMMENT_SECTION);

		/* if section */

		if ($position_section > -1)
		{
			$value = trim(str_replace(TOCGEN_COMMENT_SECTION, '', $value));
			$section_explode = explode('.', $value);
			if ($section_explode[0])
			{
				$section_sub_new = $section_explode[0];
			}

			/* if sub section */

			if ($section_sub_old == $section_sub_new)
			{
				$section_length = strlen($section_sub);
				$value = constant(TOCGEN_TOC_INDENT . $section_length) . $value;
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

		if (TOCGEN_FORCE == 0 && $toc_list_old == $toc_list_new)
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