<?php
error_reporting(0);

/* include core files */

$tocgen_directory = dirname(__FILE__);
include_once($tocgen_directory . '/includes/console.php');
include_once($tocgen_directory . '/includes/filesystem.php');
include_once($tocgen_directory . '/includes/write.php');

/* handle argument */

if ($argv[1])
{
	global $config;
	$path = realpath($argv[1]);

	/* include config */

	if (basename($argv[2]) == '.tocgen' && file_exists($argv[2]))
	{
		$config_contents = file_get_contents($argv[2]);
	}
	else if (file_exists($tocgen_directory . '/.tocgen'))
	{
		$config_contents = file_get_contents($tocgen_directory . '/.tocgen');
	}

	/* else exit */

	else
	{
		exit();
	}

	/* decode json */

	if ($config_contents)
	{
		$config = json_decode($config_contents, true);
	}

	/* force option */

	if (in_array('--force', $argv) || in_array('-f', $argv))
	{
		$config['options']['force'] = true;
	}

	/* recursive option */

	if (in_array('--recursive', $argv) || in_array('-r', $argv))
	{
		$config['options']['recursive'] = true;
	}

	/* quite option */

	if (in_array('--quite', $argv) || in_array('-q', $argv))
	{
		$config['options']['quite'] = true;
	}

	/* walk directory */

	walk_directory($path, 'write_toc');
}
?>