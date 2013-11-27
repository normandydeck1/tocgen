<?php
error_reporting(-1);

/* include file */

$currentDirectory = dirname(__FILE__);
include_once($currentDirectory . '/tocgen.php');

/* handle arguments */

if (isset($argv))
{
	$tocgen = new Tocgen($argv);
	echo $tocgen->process();
}
else
{
	exit();
}
?>