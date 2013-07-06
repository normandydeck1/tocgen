<?php

/**
 * write toc
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
				$section_length = strlen($section_sub);
				$value = constant(TOCGEN_TOC_INDENT . $section_length) . $value;
			}
			$section_sub_old = $section_sub;

			/* collect toc list */

			$toc_list .= TOCGEN_TOC_PREFIX . $value . TOCGEN_EOL;
		}
	}

	/* if toc list */

	if ($toc_list)
	{
		$contents_new = TOCGEN_TOC_START . TOCGEN_TOC_HEAD . $toc_list . TOCGEN_TOC_FOOT . TOCGEN_TOC_END . TOCGEN_TOC_SPACE . $contents;

		/* if no changes */

		if ($contents_old == $contents_new)
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