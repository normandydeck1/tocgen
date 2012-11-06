<?php

/* config tocgen */

define(TOCGEN_EOL, "\r\n");
define(TOCGEN_TOC_START, '/**');
define(TOCGEN_TOC_END, ' */');
define(TOCGEN_TOC_CHECK, '@tableofcontents');
define(TOCGEN_TOC_HEAD, TOCGEN_EOL . ' * @tableofcontents' . TOCGEN_EOL . ' *');
define(TOCGEN_TOC_PREFIX, ' * ');
define(TOCGEN_TOC_INDENT1, '   ');
define(TOCGEN_TOC_INDENT2, '    ');
define(TOCGEN_COMMENT_START, '/*');
define(TOCGEN_COMMENT_END, '*/');
define(TOCGEN_COMMENT_SECTION, '@section');
define(TOCGEN_COMMENT_REGEX, '/\/\*([\s\S]*?)\*\//');
define(TOCGEN_NO_FILES, 'File or directory not found');
define(TOCGEN_NO_SECTIONS, TOCGEN_COMMENT_SECTION . ' not found');
define(TOCGEN_NO_CHANGES, 'No changes were made');
define(TOCGEN_TOC_UPDATED, 'Table of contents updated');
define(TOCGEN_POINT, '.');
define(TOCGEN_COLON, ':');
?>