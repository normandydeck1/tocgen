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

Load config from .tocgen JSON file.


**Options:**

<code>--force</code>, <code>-f</code> - Force table of contents generation

<code>--recursive</code>, <code>-r</code> - Walk target directory recursively

<code>--quite</code>, <code>-q</code> - Print nothing


Config
------

Extend your table of contents with *@since*, *@package* and *@author* by using a .tocgen file like this:

<pre>
{
	"eol": "\r\n",
	"toc":
	{
		"flag": "@tableofcontents",
		"start": "/**",
		"end": " */\r\n\r\n",
		"prefix": " * ",
		"indent": "   ",
		"delimiter": " *\r\n",
		"head": "\r\n * @tableofcontents\r\n *\r\n",
		"foot": " *\r\n * @since 1.0.0\r\n *\r\n * @package Your Project\r\n * @author Your Name\r\n"
	},
	"section":
	{
		"flag": "@section",
		"start": "/*",
		"end": "*/",
		"prefix": "*",
		"pattern": "/\\/\\*(.|[\\r\\n])*?\\*\\//"
	},
	"extensions":
	[
		"coffee",
		"css",
		"js",
		"less",
		"sass",
		"scss"
	],
	"wording":
	{
		"noTarget": "File or directory not found",
		"noSection": "No section found",
		"noChanges": "No changes were made",
		"tocUpdated": "Table of contents updated",
		"point": ".",
		"colon": ":"
	},
	"options":
	{
		"force": false,
		"recursive": false,
		"quite": false
	}
}
</pre>


Composer
--------

How to register Tocgen inside [composer.json](https://github.com/composer/composer):

<pre>
{
	"require-dev":
	{
		"redaxmedia/tocgen": "2.2.1"
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
			command: 'php vendor/redaxmedia/tocgen/tocgen.php css',
			stdout: true
		},
		tocJS:
		{
			command: 'php vendor/redaxmedia/tocgen/tocgen.php js',
			stdout: true
		}
	}
}

/* load tasks */

grunt.loadNpmTasks('grunt-shell');

/* register tasks */

grunt.registerTask('toc',
[
	'shell:tocCSS',
	'shell:tocJS'
]);
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

/* Ignored comment */

.sub
{
	color: #fff;
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

/* Ignored comment */

.sub
{
	color: #fff;
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


Troubleshooting
---------------

Keep in mind that <code>TOCGEN_EOL</code> inside your .tocgen file equals your IDE's setup. Otherwise Tocgen fails to detect existing table of contents and therefore generates a fresh one.