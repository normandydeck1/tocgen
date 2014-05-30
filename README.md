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

Run <code>sh vendor/bin/tocgen.sh {path} {config} {options}</code> from console. This bash redirects to the <code>cli.php</php> file.


**Example:**

Run <code>sh vendor/bin/tocgen.sh templates .tocgen --recursive</code> to walk <code>templates</code> recursively with your <code>.tocgen</code> file.


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
	"eol": "\n",
	"toc":
	{
		"flag": "@tableofcontents",
		"start": "/**",
		"end": " */\n\n",
		"prefix": " * ",
		"delimiter": " *\n",
		"indent": "   ",
		"head": "\n * @tableofcontents\n *\n",
		"foot": ""
	},
	"section":
	{
		"flag": "@section",
		"start": "/*",
		"end": "*/",
		"delimiter": ".",
		"pattern": "/\\/\\*(.|[\n])*?\\*\\//"
	}
}
</pre>


Extend
------

Foot with <code>@since</code>, <code>@package</code> and <code>@author</code> annotations:

<pre>
"toc":
{
	"foot": " *\n * @since 1.0.0\n *\n * @package Your Project\n * @author Your Name\n"
}
</pre>

Section pattern to handle multiple lines:

<pre>
"section":
{
	"pattern": "/\\/\\*([\\s\\S]*?)([\n])|\\*\\//"
}
</pre>


Composer
--------

How to register Tocgen inside [composer.json](https://github.com/composer/composer):

<pre>
{
	"require-dev":
	{
		"redaxmedia/tocgen": "4.0.0"
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
			command: 'sh vendor/bin/tocgen.sh css',
		},
		tocJS:
		{
			command: 'sh vendor/bin/tocgen.sh js'
		},
		tocLintCSS:
		{
			command: 'sh vendor/bin/tocgen.sh css -l',
		},
		tocLintJS:
		{
			command: 'sh vendor/bin/tocgen.sh js -l'
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


Troubleshooting
---------------

Keep in mind that <code>eol</code> inside your <code>.tocgen</code> file equals your IDE's setup. Otherwise Tocgen fails to detect existing table of contents and therefore generates a fresh one.
