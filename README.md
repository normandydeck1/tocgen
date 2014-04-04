Tocgen
======

> Generate table of contents from multiple CSS and JS files.

[![Dependency Status](https://www.versioneye.com/user/projects/53110552ec13753a90000477/badge.png)](https://www.versioneye.com/user/projects/53110552ec13753a90000477)
[![Latest Stable Version](https://poser.pugx.org/redaxmedia/tocgen/v/stable.png)](https://packagist.org/packages/redaxmedia/tocgen)
[![Total Downloads](https://poser.pugx.org/redaxmedia/tocgen/downloads.png)](https://packagist.org/packages/redaxmedia/tocgen)
[![License](https://poser.pugx.org/redaxmedia/tocgen/license.png)](https://packagist.org/packages/redaxmedia/tocgen)


API
---

Send a <code>POST</code> request to <code>api.php</code>.


**$_POST[1]:**

Single file contents.


**$_POST[2]:**

Config from <code>.tocgen</code> file.


CLI
---

Run <code>php cli.php {path} {config} {options}</code> from console.


**Example:**

Run <code>php vendor/redaxmedia/tocgen/cli.php templates .tocgen --recursive</code> to walk <code>templates</code> recursively with your <code>.tocgen</code> file.


**Path:**

Single file or directory.


**Config:**

Load config from another <code>.tocgen</code> file.


**Options:**

<code>--force</code>, <code>-f</code> - Force table of contents generation

<code>--recursive</code>, <code>-r</code> - Walk target recursively

<code>--lint</code>, <code>-l</code> - Lint for errors (readonly)

<code>--quite</code>, <code>-q</code> - Print nothing to console


Service
-------

**Website:**

[http://tocgen.net](http://tocgen.net)


**API:**

[http://tocgen.net/api.php](http://tocgen.net/api.php)


**Example:**

<pre>
$.ajax(
{
	url: 'api.php',
	type: 'post',
	data:
	{
		1: input.val(),
		2: config.val()
	},
	success: function (data)
	{
		output.val(data);
	}
});
</pre>


Config
------

Configuration are stored inside <code>.tocgen</code> file:

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

Extend your foot with <code>@since</code>, <code>@package</code> and <code>@author</code> annotations:

<pre>
"toc":
{
	"foot": " *\r\n * @since 1.0.0\r\n *\r\n * @package Your Project\r\n * @author Your Name\r\n"
}
</pre>


Composer
--------

How to register Tocgen inside [composer.json](https://github.com/composer/composer):

<pre>
{
	"require-dev":
	{
		"redaxmedia/tocgen": "3.0.2"
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
		},
		tocJS:
		{
			command: 'php vendor/redaxmedia/tocgen/cli.php js'
		},
		tocLintCSS:
		{
			command: 'php vendor/redaxmedia/tocgen/cli.php css -l',
		},
		tocLintJS:
		{
			command: 'php vendor/redaxmedia/tocgen/cli.php js -l'
		},
 		options:
		{
			stdout: true,
			failOnError: true
		}
	}
}

/* load tasks */

grunt.loadNpmTasks('grunt-shell');

/* register tasks */

grunt.registerTask('toclint',
[
	'shell:tocLintCSS',
	'shell:tocLintJS'
]);
grunt.registerTask('toc',
[
	'shell:tocCSS',
	'shell:tocJS'
]);
</pre>

Task <code>toclint</code> returns <code>exit</code> on errors and works perfect with [Travis CI](https://travis-ci.org) and other continous integration tools.


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

Keep in mind that <code>eol</code> inside your <code>.tocgen</code> file equals your IDE's setup. Otherwise Tocgen fails to detect existing table of contents and therefore generates a fresh one.
