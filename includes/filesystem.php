<?php

/**
 * read directory
 *
 * @param string $input
 * @param string|array $ignore
 * @return array
 */

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

/**
 * walk directory
 *
 * @param string $path
 * @param string $function
 * @param string $recursive
 */

function walk_directory($path = '', $function = '', $recursive = '')
{
	/* if file */

	if (is_file($path))
	{
		$directory = array(
			$path
		);
	}

	/* else if directory */

	else if (is_dir($path))
	{
		/* read directory */

		$directory = read_directory($path, array(
			'.git',
			'.svn'
		));
	}

	/* else handle error */

	else if (TOCGEN_QUITE == 0)
	{
		echo console(TOCGEN_NO_TARGET . TOCGEN_POINT, 'error') . PHP_EOL;
	}

	/* if directory count */

	if (count($directory))
	{
		foreach($directory as $filename)
		{
			$path_sub = $path . '/' . $filename;

			/* if file */

			if (is_file($path_sub))
			{
				$extension = pathinfo($path_sub, PATHINFO_EXTENSION);

				/* if correct extension */

				if ($extension == 'css' || $extension == 'js')
				{
					call_user_func($function, $path_sub);
				}
			}

			/* else if directory */

			else if (is_dir($path_sub) && $recursive)
			{
				walk_directory($path_sub, $function, $recursive);
			}
		}
	}
}
?>