<?php
error_reporting(0);

/* include core files */

include_once('config.php');
include_once('includes/filesystem.php');
include_once('includes/write.php');

/* get argument */

if ($argv[1])
{
	$directory = $argv[1];

	/* read target directory */

	if ($directory)
	{
		$target_directory = read_directory($directory, array(
			'.git',
			'.loader',
			'.svn'
		));
	}
}

/* write toc */

if (count($target_directory))
{
	foreach($target_directory as $filename)
	{
		write_toc($directory . '/' . $filename);
	}
}

/* else handle error */

else
{
	echo TOCGEN_NO_FILES . TOCGEN_POINT;
}
?>