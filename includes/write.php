<?php

/* write toc */

function write_toc($path = '')
{
	/* get contents */

	$contents = $contents_old = file_get_contents($path);
	$contents_explode = explode(TOCGEN_TOC_END, $contents);

	/* remove present toc block */

	if ($contents_explode[1])
	{
		$contents = trim($contents_explode[1]);
	}

	/* get all matches */

	preg_match_all(TOCGEN_COMMENT_REGEX, $contents, $matches);
	$matches = $matches[0];

	/* prepare matches */

	$comment_parts = array(
		TOCGEN_COMMENT_START,
		TOCGEN_COMMENT_END
	);

	foreach ($matches as $key => $value)
	{
		$value = trim(str_replace($comment_parts, '', $value));
		$position_section = strpos($value, TOCGEN_COMMENT_SECTION);

		/* replace if section */

		if ($position_section > -1)
		{
			$value = trim(str_replace(TOCGEN_COMMENT_SECTION, '', $value));
		}

		/* else indent */

		else
		{
			$value = TOCGEN_TOC_INDENT . $value;
		}

		/* collect toc listing */

		$list .= TOCGEN_TOC_PREFIX . $value . PHP_EOL;
	}

	/* new contents */

	$contents_new = TOCGEN_TOC_START . TOCGEN_TOC_HEAD . PHP_EOL . $list . TOCGEN_TOC_END . PHP_EOL . PHP_EOL . $contents;

	/* if no changes */

	if ($contents_old === $contents_new)
	{
		echo TOCGEN_NO_CHANGES . TOCGEN_COLON . $path . PHP_EOL;
	}

	/* else update toc */

	else
	{
		file_put_contents($path, $contents_new);
		echo TOCGEN_TOC_UPDATED . TOCGEN_COLON . $path . PHP_EOL;
	}
}
?>