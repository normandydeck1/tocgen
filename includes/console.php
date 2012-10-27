<?php

/* console */

function console($message = '', $mode = '')
{
	$operating_system = strtolower(php_uname('s'));

	/* if linux */

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

			/* mode success */

			else if ($mode === 'success')
			{
				$output .= '[1;32m';
			}
			$output .= $input . chr(27) . '[0m';

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