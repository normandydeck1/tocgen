<?php
error_reporting(0);

/* include file */

$baseDirectory = dirname(__FILE__);
include_once($baseDirectory . '/tocgen.php');

/* process */

$tocgen = new Tocgen($argv, $baseDirectory);
echo $tocgen->process();
?>