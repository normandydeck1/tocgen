<?php

/* config tocgen */

define(TOCGEN_TOC_START, '/**');
define(TOCGEN_TOC_END, '**/');
define(TOCGEN_TOC_HEAD, PHP_EOL . ' * @tableOfContents' . PHP_EOL . ' *');
define(TOCGEN_TOC_PREFIX, ' * ');
define(TOCGEN_TOC_INDENT, '   ');
define(TOCGEN_COMMENT_START, '/*');
define(TOCGEN_COMMENT_END, '*/');
define(TOCGEN_COMMENT_SECTION, '@section');
define(TOCGEN_COMMENT_REGEX, '/\/\*\.*.*?\.*\*\//');
define(TOCGEN_NO_CHANGES, 'No changes were made');
define(TOCGEN_TOC_UPDATED, 'Table of contents updated');
define(TOCGEN_COLON, ': ');
?>