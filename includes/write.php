<?php

/* write toc */

function write_toc($path = '')
{
	/* get contents */

	$contents = $contents_old = file_get_contents($path);
	$contents_explode = explode(TOCGEN_TOC_END, $contents, 2);

	/* remove present toc block */

	if ($contents_explode[1])
	{
		$position_toc_check = strpos($contents_explode[0], TOCGEN_TOC_CHECK);

		/* if toc check */

		if ($position_toc_check > -1)
		{
			$contents = trim($contents_explode[1]);
		}
	}

	/* get all matches */

	preg_match_all(TOCGEN_COMMENT_REGEX, $contents, $matches);
	$matches = $matches[0];

	/* prepare matches */

	$comment_parts = array(
		TOCGEN_COMMENT_START,
		TOCGEN_COMMENT_END
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
				$section_sub = $section_explode[0];
			}

			/* if sub section*/

			if ($section_sub_old == $section_sub)
			{
				$value = TOCGEN_TOC_INDENT . $value;
			}
			$section_sub_old = $section_sub;

			/* collect toc list */

			$toc_list .= TOCGEN_TOC_PREFIX . $value . TOCGEN_EOL;
		}
	}

	/* if toc list */

	if ($toc_list)
	{
		$contents_new = TOCGEN_TOC_START . TOCGEN_TOC_HEAD . TOCGEN_EOL . $toc_list . TOCGEN_TOC_END . TOCGEN_EOL . TOCGEN_EOL . $contents;

		/* if no changes */

		if ($contents_old == $contents_new)
		{
			echo console(TOCGEN_NO_CHANGES . TOCGEN_COLON, 'warning') . ' ' . $path . PHP_EOL;
		}

		/* else update toc */

		else
		{
			echo console(TOCGEN_TOC_UPDATED . TOCGEN_COLON, 'success') . ' ' . $path . PHP_EOL;
			file_put_contents($path, $contents_new);
		}
	}

	/* else handle error */

	else
	{
		echo console(TOCGEN_NO_SECTIONS . TOCGEN_COLON, 'warning') . ' ' . $path . PHP_EOL;
	}
}
?>