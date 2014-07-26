<?php
error_reporting(0);
$currentDirectory = dirname(__FILE__);

/* include files */

include_once($currentDirectory . '/tocgen.php');

/* define variables */

$target = $_POST[1];
$config = $_POST[2];
$targetFile = $currentDirectory . '/tmp/' . sha1($target) . '.tmp';
$configFile = $currentDirectory . '/tmp/' . sha1($config) . '.tmp';
$argv = array(
	1 => $targetFile,
	2 => $configFile
);

/* put files */

file_put_contents($targetFile, $target);
file_put_contents($configFile, $config);

/* process */

$tocgen = new Tocgen($argv, $currentDirectory);
$tocgen->process();

/* get contents */

echo file_get_contents($targetFile);

/* unlink files */

array_map('unlink', glob($currentDirectory . '/tmp/*.tmp'));
