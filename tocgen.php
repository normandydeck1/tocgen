<?php
error_reporting(0);

/* include core files */

include_once('config.php');
include_once('includes/filesystem.php');
include_once('includes/write.php');

/* listen for argument */

if ($argv[1])
{
    $directory = $argv[1];
}

/* read target directory */

$target_directory = read_directory($directory, array(
    '.git',
    '.loader',
    '.svn'
));

if (count($target_directory))
{
	foreach($target_directory as $filename)
	{
		write_toc($directory . '/' . $filename);
	}
}
?>