TOC Generator
=============

> Generate table of contents from CSS and JS files.


Config
------

Setup your table of contents block inside the .tocgen file.


Usage
-----

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


Grunt
-----

How to implement togcen into [grunt.js](https://github.com/gruntjs/grunt) using the [grunt-shell](https://github.com/sindresorhus/grunt-shell) extention:

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

How to register tocgen inside composer.json:

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