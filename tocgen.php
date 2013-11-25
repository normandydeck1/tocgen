<?php
error_reporting(0);

/* include core files */

$tocgenDirectory = dirname(__FILE__);
include_once($tocgenDirectory . '/includes/console.php');
include_once($tocgenDirectory . '/includes/filesystem.php');
include_once($tocgenDirectory . '/includes/write.php');

/* handle argument */

if ($argv[1])
{
	global $config;

	/* include config */

	if (basename($argv[2]) === '.tocgen' && file_exists($argv[2]))
	{
		$configContents = file_get_contents($argv[2]);
	}

	/* else if default config */

	else if (file_exists($tocgenDirectory . '/.tocgen'))
	{
		$configContents = file_get_contents($tocgenDirectory . '/.tocgen');
	}

	/* else exit */

	else
	{
		exit();
	}

	/* decode contents */

	if ($configContents)
	{
		$config = json_decode($configContents, true);
	}

	/* handle options */

	foreach ($config['options'] as $key => $value)
	{
		if (in_array('--' . $key, $argv) || in_array('-' . substr($key, 0, 1), $argv))
		{
			$config['options'][$key] = true;
		}
	}

	/* walk directory */

	walkDirectory(realpath($argv[1]), 'writeToc');
}
?>