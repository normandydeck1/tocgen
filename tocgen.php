<?php
error_reporting(0);

/* include core files */

include_once('config.php');
include_once('includes/console.php');
include_once('includes/filesystem.php');
include_once('includes/write.php');

/* get argument */

if ($argv[1])
{
	$path = $argv[1];

	/* is file */

	if (is_file($path))
	{
		write_toc($path);
	}

	/* is directory */

	else if (is_dir($path))
	{
		/* read directory files */

		$target_directory = read_directory($path, array(
			'.git',
			'.loader',
			'.svn'
		));

		/* if directory has files */

		if (count($target_directory))
		{
			foreach($target_directory as $filename)
			{
				write_toc($path . '/' . $filename);
			}
		}

		/* else handle error */

		else
		{
			echo console(TOCGEN_NO_FILES . TOCGEN_POINT, 'error') . PHP_EOL;
		}
	}

	/* else handle error */

	else
	{
		echo console(TOCGEN_NO_FILES . TOCGEN_POINT, 'error') . PHP_EOL;
	}
}
?>