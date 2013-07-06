TOC Generator
=============

> Generate table of contents from CSS and JS files.


Syntax
------

Run <code>php tocgen.php [path] [config] [options]</code> from console.


**Path:**

Single file or directory.


**Config:**

Load config from file.


**Options:**

<code>--force</code>, <code>-f</code> - Force table of contents generation

<code>--recursive</code>, <code>-r</code> - Walk target directory recursively

<code>--quite</code>, <code>-q</code> - Print nothing


Usage
-----

<code>php vendor/tocgen/tocgen.php css .tocgen -r</code>

<code>php vendor/tocgen/tocgen.php js .tocgen -r</code>


Config
------

Customize your table of contents block inside the .tocgen file.


**Checkout [Redaxscript](https://github.com/redaxmedia/redaxscript)'s .tocgen**

<pre>
<?php

/* config tocgen */

define(TOCGEN_EOL, "\r\n");
define(TOCGEN_TOC_CHECK, '@tableofcontents');
define(TOCGEN_TOC_START, '/**');
define(TOCGEN_TOC_END, ' */' . TOCGEN_EOL . TOCGEN_EOL);
define(TOCGEN_TOC_DELIMITER, ' *' . TOCGEN_EOL);
define(TOCGEN_TOC_HEAD, TOCGEN_EOL . ' * @tableofcontents' . TOCGEN_EOL . TOCGEN_TOC_DELIMITER);
define(TOCGEN_TOC_FOOT, TOCGEN_TOC_DELIMITER . ' * @since 2.0' . TOCGEN_EOL . ' *' . TOCGEN_EOL . ' * @package Redaxscript' . TOCGEN_EOL . ' * @author Henry Ruhs' . TOCGEN_EOL);
define(TOCGEN_TOC_PREFIX, ' * ');
define(TOCGEN_TOC_INDENT1, '   ');
define(TOCGEN_TOC_INDENT2, '    ');
define(TOCGEN_COMMENT_START, '/*');
define(TOCGEN_COMMENT_END, '*/');
define(TOCGEN_COMMENT_SECTION, '@section');
define(TOCGEN_COMMENT_REGEX, '/\/\*([\s\S]*?)\*\//');
define(TOCGEN_NO_TARGET, 'File or directory not found');
define(TOCGEN_NO_SECTION, 'Comment ' . TOCGEN_COMMENT_SECTION . ' not found');
define(TOCGEN_NO_CHANGES, 'No changes were made');
define(TOCGEN_TOC_UPDATED, 'Table of contents updated');
define(TOCGEN_POINT, '.');
define(TOCGEN_COLON, ':');
?>
</pre>


Grunt
-----

How to implement togcen into [gruntfile.js](https://github.com/gruntjs/grunt) using the [grunt-shell](https://github.com/sindresorhus/grunt-shell) extention:

<pre>

/* config grunt */

grunt.initConfig(
{
	shell:
	{
		tocCSS:
		{
			command: 'php vendor/tocgen/tocgen.php css',
			stdout: true
		},
		tocJS:
		{
			command: 'php vendor/tocgen/tocgen.php js',
			stdout: true
		}
	}
}

/* load tasks */

grunt.loadNpmTasks('grunt-shell');

/* register tasks */

grunt.registerTask('toc', 'shell:tocCSS shell:tocJS');
</pre>


Composer
--------

How to register tocgen inside [composer.json](https://github.com/composer/composer):

<pre>
{
	"name": "Your project",
	"repositories":
	[
		{
			"type": "package",
			"package":
			{
				"name": "tocgen",
				"version": "2.0",
				"source":
				{
					"url": "https://github.com/redaxmedia/tocgen.git",
					"type": "git",
					"reference": "2.0"
				}
			}
		}
	],
	"require":
	{
		"tocgen": "2.0"
	}
}
</pre>


Example
-------

Input file:

<pre>
/* @section 1. First section */

.first
{
	margin: auto;
}

/* @section 1.1 Sub section */

.first > .sub
{
	padding: 2em;
}

/* @section 2. Second section */

.second
{
	text-decoration: underline;
}

/* @section 3. Third section */

.third
{
	color: #fff;
}
</pre>

Output file:

<pre>
/**
 * @tableofcontents
 *
 * 1. First section
 *    1.1 Sub section
 * 2. Second section
 * 3. Third section
 */

/* @section 1. First section */

.first
{
	margin: auto;
}

/* @section 1.1 Sub section */

.first > .sub
{
	padding: 2em;
}

/* @section 2. Second section */

.second
{
	text-decoration: underline;
}

/* @section 3. Third section */

.third
{
	color: #fff;
}
</pre>