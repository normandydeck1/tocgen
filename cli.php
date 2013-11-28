<?php
error_reporting(0);

/* include file */

$currentDirectory = dirname(__FILE__);
include_once($currentDirectory . '/tocgen.php');

/* process */

$tocgen = new Tocgen($argv);
echo $tocgen->process();
?>