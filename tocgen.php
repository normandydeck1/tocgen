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
	$path = realpath($argv[1]);
	$recursive = 0;

	/* include config */

	if (file_exists($argv[2]))
	{
		include_once($argv[2]);
	}
	else
	{
		include_once($tocgen_directory . '/.tocgen');
	}

	/* force option */

	if (in_array('--force', $argv) || in_array('-f', $argv))
	{
		define('TOCGEN_FORCE', 1);
	}
	else
	{
		define('TOCGEN_FORCE', 0);
	}

	/* recursive option */

	if (in_array('--recursive', $argv) || in_array('-r', $argv))
	{
		$recursive = 1;
	}

	/* quite option */

	if (in_array('--quite', $argv) || in_array('-q', $argv))
	{
		define('TOCGEN_QUITE', 1);
	}
	else
	{
		define('TOCGEN_QUITE', 0);
	}

	/* walk directory */

	walk_directory($path, 'write_toc', $recursive);
}
?>