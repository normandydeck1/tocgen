<?php

/**
 * console
 *
 * @since 2.3.0
 *
 * @package Tocgen
 * @category Console
 * @author Henry Ruhs
 *
 * @param string $message
 * @param string $mode
 * @return string
 */

function console($message = '', $mode = '')
{
	$operatingSystem = strtolower(php_uname('s'));
	$noteColors = array(
		'error' => '[1;31m',
		'success' => '[1;32m',
		'warning' => '[1;33m'
	);

	/* linux is present */

	if ($operatingSystem == 'linux')
	{
		if ($message && $mode)
		{
			$output = chr(27) . $noteColors[$mode] . $message . chr(27) . '[0m';
		}
	}

	/* else fallback */

	else
	{
		$output = $message;
	}
	return $output;
}
?>