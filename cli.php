<?php
error_reporting(0);
$baseDirectory = dirname(__FILE__);

/* include file */

include_once($baseDirectory . '/tocgen.php');

/* process */

$tocgen = new Tocgen($argv, $baseDirectory);
echo $tocgen->process();
