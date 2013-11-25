<?php

/**
 * read directory
 *
 * @since 2.1
 *
 * @package Tocgen
 * @category Filesystem
 * @author Henry Ruhs
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
 * @since 1.0
 *
 * @package Tocgen
 * @category Filesystem
 * @author Henry Ruhs
 *
 * @param string $path
 * @param string $function
 */

function walk_directory($path = '', $function = '')
{
	global $config;

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

	else if ($config['options']['quite'] == false)
	{
		echo console($config['wording']['noTarget'] . $config['wording']['point'], 'error') . PHP_EOL;
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

				/* check supported extension */

				if (in_array($extension, $config['extensions']))
				{
					call_user_func($function, $path_sub);
				}
			}

			/* else if directory */

			else if (is_dir($path_sub) && $config['options']['recursive'])
			{
				walk_directory($path_sub, $function);
			}
		}
	}
}
?>