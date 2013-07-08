<?php

/**
 * console
 *
 * @since 2.1
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
	$operating_system = strtolower(php_uname('s'));

	/* if linux present */

	if ($operating_system == 'linux')
	{
		if ($message && $mode)
		{
			/* collect output */

			$output = chr(27);

			/* mode error */

			if ($mode == 'error')
			{
				$output .= '[1;31m';
			}

			/* mode warning */

			else if ($mode == 'warning')
			{
				$output .= '[1;33m';
			}

			/* mode success */

			else if ($mode == 'success')
			{
				$output .= '[1;32m';
			}
			$output .= $message . chr(27) . '[0m';
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