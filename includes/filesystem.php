<?php

/**
 * Read Directory
 *
 * @since 2.1
 *
 * @category Filesystem
 * @package Tocgen
 * @author Henry Ruhs
 *
 * @param $input string
 * @param $ignore string|array
 * @return $output array
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
 * Walk Directory
 *
 * @since 1.0
 *
 * @category Filesystem
 * @package Tocgen
 * @author Henry Ruhs
 *
 * @param $path string
 * @param $function string
 * @param $recursive boolean
 */

function walk_directory($path = '', $function = '', $recursive = '')
{
	/* if file */

	if (is_file($path))
	{
		$directory = array(
			$path
		);
		$path = '';
	}

	/* else if directory */

	else if (is_dir($path))
	{
		/* read directory */

		$ignore = array(
			'.git',
			'.svn'
		);
		$directory = read_directory($path, $ignore);
		$path .= '/';
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
			$path_sub = $path . $filename;

			/* if file */

			if (is_file($path_sub))
			{
				$extension = pathinfo($path_sub, PATHINFO_EXTENSION);
				$supported = array(
					'coffee',
					'css',
					'js',
					'less',
					'sass',
					'scss'
				);

				/* if supported extension */

				if (in_array($extension, $supported))
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