<?php

/**
 * read directory
 *
 * @since 2.3.0
 *
 * @package Tocgen
 * @category Filesystem
 * @author Henry Ruhs
 *
 * @param string $input
 * @param string|array $ignore
 * @return array
 */

function readDirectory($input = '', $ignore = '')
{
	$output = scandir($input);

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
 * @since 2.3.0
 *
 * @package Tocgen
 * @category Filesystem
 * @author Henry Ruhs
 *
 * @param string $path
 * @param string $function
 */

function walkDirectory($path = '', $function = '')
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
		$directory = readDirectory($path, $ignore);
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
			$pathSub = $path . $filename;

			/* if file */

			if (is_file($pathSub))
			{
				$extension = pathinfo($pathSub, PATHINFO_EXTENSION);

				/* check supported extension */

				if (in_array($extension, $config['extensions']))
				{
					call_user_func($function, $pathSub);
				}
			}

			/* else if directory */

			else if (is_dir($pathSub) && $config['options']['recursive'])
			{
				walkDirectory($pathSub, $function);
			}
		}
	}
}
?>