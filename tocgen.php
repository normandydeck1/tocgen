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
	$extention = pathinfo($path, PATHINFO_EXTENSION);

	/* if file with correct extention */

	if (is_file($path))
	{
		if ($extention == 'css' || $extention == 'js')
		{
			write_toc($path);
		}
	}

	/* else if directory */

	else if (is_dir($path))
	{
		/* walk directory */

		walk_directory($path, 'write_toc');
	}

	/* else handle error */

	else
	{
		echo console(TOCGEN_NO_FILES . TOCGEN_POINT, 'error') . PHP_EOL;
	}
}
?>