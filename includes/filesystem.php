<?php

/* read directory */

function read_directory($input = '', $ignore = '')
{
	$handle = opendir($input);
	while ($value = readdir($handle))
	{
		$output[] = $value;
	}

	/* collect output */

	if ($output)
	{
		if (is_array($ignore) == '')
		{
			$ignore = array(
				$ignore
			);
		}
		$ignore[] = '.';
		$ignore[] = '..';
		$output = array_diff($output, $ignore);
		sort($output);
	}
	return $output;
}

/* walk directory */

function walk_directory($path = '', $function = '')
{
	if ($path)
	{
		/* read directory */

		$directory = read_directory($path, array(
			'.git',
			'.svn'
		));
	}

	/* if directory */

	if ($directory)
	{
		foreach($directory as $filename)
		{
			$path_sub = $path . '/' . $filename;
			$extention = pathinfo($path_sub, PATHINFO_EXTENSION);

			/* if file with correct extention */

			if (is_file($path_sub) && ($extention == 'css' || $extention == 'js'))
			{
				call_user_func($function, $path_sub);
			}

			/* else if directory */

			else if (is_dir($path_sub))
			{
				walk_directory($path_sub, $function);
			}
		}
	}
}
?>