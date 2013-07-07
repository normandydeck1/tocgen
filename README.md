Tocgen
======

> Generate table of contents from multiple CSS and JS files.

Supported extensions: <code>.coffee</code>, <code>.css</code>, <code>.js</code>, <code>.less</code>, <code>.sass</code>, <code>.scss</code>


Usage
-----

Run <code>php tocgen.php [path] [config] [options]</code> from console.


**Path:**

Single file or directory.


**Config:**

Load config from .tocgen file.


**Options:**

<code>--force</code>, <code>-f</code> - Force table of contents generation

<code>--recursive</code>, <code>-r</code> - Walk target directory recursively

<code>--quite</code>, <code>-q</code> - Print nothing


Config
------

Extend your table of contents with *@since*, *@package* and *@author* by using a .tocgen file like this:

<pre>
/* config tocgen */

define(TOCGEN_EOL, "\r\n");
define(TOCGEN_TOC_FLAG, '@tableofcontents');
define(TOCGEN_TOC_START, '/**');
define(TOCGEN_TOC_END, ' */' . TOCGEN_EOL . TOCGEN_EOL);
define(TOCGEN_TOC_PREFIX, ' * ');
define(TOCGEN_TOC_INDENT, '   ');
define(TOCGEN_TOC_DELIMITER, ' *' . TOCGEN_EOL);
define(TOCGEN_TOC_HEAD, TOCGEN_EOL . ' * @tableofcontents' . TOCGEN_EOL . TOCGEN_TOC_DELIMITER);
define(TOCGEN_TOC_FOOT, TOCGEN_TOC_DELIMITER . ' * @since 1.0' . TOCGEN_EOL . ' *' . TOCGEN_EOL . ' * @package Your Project' . TOCGEN_EOL . ' * @author Your Name' . TOCGEN_EOL);
define(TOCGEN_SECTION_FLAG, '@section');
define(TOCGEN_SECTION_START, '/*');
define(TOCGEN_SECTION_END, '*/');
define(TOCGEN_SECTION_PREFIX, '*');
define(TOCGEN_SECTION_REGEX, '/\/\*(.|[' . TOCGEN_EOL . '])*?\*\//');
define(TOCGEN_NO_TARGET, 'File or directory not found');
define(TOCGEN_NO_SECTION, TOCGEN_SECTION_FLAG . ' not found');
define(TOCGEN_NO_CHANGES, 'No changes were made');
define(TOCGEN_TOC_UPDATED, 'Table of contents updated');
define(TOCGEN_POINT, '.');
define(TOCGEN_COLON, ':');
</pre>


Composer
--------

How to register Tocgen inside [composer.json](https://github.com/composer/composer):

<pre>
{
	"name": "Your Project",
	"repositories":
	[
		{
			"type": "package",
			"package":
			{
				"name": "tocgen",
				"version": "2.1",
				"source":
				{
					"url": "https://github.com/redaxmedia/tocgen.git",
					"type": "git",
					"reference": "2.1"
				}
			}
		}
	],
	"require":
	{
		"tocgen": "2.1"
	}
}
</pre>


Grunt
-----

How to implement Tocgen into [gruntfile.js](https://github.com/gruntjs/grunt) using the [grunt-shell](https://github.com/sindresorhus/grunt-shell) extention:

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