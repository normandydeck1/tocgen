Tocgen
======

> Generate table of contents from multiple CSS and JS files.

[![Latest Stable Version](https://poser.pugx.org/redaxmedia/tocgen/v/stable.png)](https://packagist.org/packages/redaxmedia/tocgen)
[![Total Downloads](https://poser.pugx.org/redaxmedia/tocgen/downloads.png)](https://packagist.org/packages/redaxmedia/tocgen)


API
---

Send a <code>POST</code> request to <code>api.php</code>.


**$_POST[1]:**

Single file contents.


**$_POST[2]:**

Config contents from <code>.tocgen</code> (JSON) file.


Console
-------

Run <code>php cli.php {path} {config} {options}</code> from console.


**Path:**

Single file or directory.


**Config:**

Load config from another <code>.tocgen</code> (JSON) file.


**Options:**

<code>--force</code>, <code>-f</code> - Force table of contents generation

<code>--recursive</code>, <code>-r</code> - Walk target recursively

<code>--lint</code>, <code>-l</code> - Lint for errors (readonly)

<code>--quite</code>, <code>-q</code> - Print nothing inside console


Service
-------

**GUI:**

[http://tocgen.net](http://tocgen.net)


**API:**

[http://tocgen.net/api.php](http://tocgen.net/api.php)


Config
------

Configuration are stored inside <code>.tocgen</code> (JSON) file:

<pre>
{
	"eol": "\r\n",
	"toc":
	{
		"flag": "@tableofcontents",
		"start": "/**",
		"end": " */\r\n\r\n",
		"prefix": " * ",
		"delimiter": " *\r\n",
		"indent": "   ",
		"head": "\r\n * @tableofcontents\r\n *\r\n",
		"foot": ""
	},
	"section":
	{
		"flag": "@section",
		"start": "/*",
		"end": "*/",
		"delimiter": ".",
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
	"exclude":
	[
		".",
		"..",
		".git",
		".svn"
	],
	"notes":
	{
		"error": "[1;31m",
		"success": "[1;32m",
		"warning": "[1;33m",
		"info": "[1;36m"
	},
	"wording":
	{
		"tocgen": "Tocgen by Redaxmedia",
		"noTarget": "File or directory not found",
		"noSection": "No section found",
		"noChanges": "No changes were made",
		"duplicateRank": "Duplicate rank detected",
		"wrongOrder": "Wrong order detected",
		"tocUpdated": "Table of contents updated",
		"point": ".",
		"colon": ":",
		"indent": "  "
	},
	"options":
	{
		"force": false,
		"recursive": true,
		"lint": false,
		"quite": false
	}
}
</pre>

Extend your table of contents with *@since*, *@package* and *@author* by using <code>foot</code> like:

<pre>
"foot": " *\r\n * @since 1.0.0\r\n *\r\n * @package Your Project\r\n * @author Your Name\r\n"
</pre>


Composer
--------

How to register Tocgen inside [composer.json](https://github.com/composer/composer):

<pre>
{
	"require-dev":
	{
		"redaxmedia/tocgen": "3.0.1"
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
			command: 'php vendor/redaxmedia/tocgen/cli.php css',
			stdout: true
		},
		tocJS:
		{
			command: 'php vendor/redaxmedia/tocgen/cli.php js',
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

Keep in mind that <code>eol</code> inside your <code>.tocgen</code> (JSON) file equals your IDE's setup. Otherwise Tocgen fails to detect existing table of contents and therefore generates a fresh one.