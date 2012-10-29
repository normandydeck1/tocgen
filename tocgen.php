<?php
error_reporting(0);

/* include core files */

$tocgen_directory = dirname(__FILE__);
include_once($tocgen_directory . '/config.php');
include_once($tocgen_directory . '/includes/console.php');
include_once($tocgen_directory . '/includes/filesystem.php');
include_once($tocgen_directory . '/includes/write.php');

/* get argument */

if ($argv[1])
{
	$path = realpath($argv[1]);
	$recursive = 0;

	/* recursive option */

	if ($argv[2] == '--recursive' || $argv[3] == '--recursive')
	{
		$recursive = 1;
	}

	/* quite option */

	if ($argv[2] == '--quite' || $argv[3] == '--quite')
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