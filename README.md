TOC Generator
=============

> Generate table of contents from CSS and JS files.


Config
------

Setup your table of contents block inside config.php.


Usage
-----

Run <code>php tocgen.php "file or directory"</code> from console.


Grunt
-----

How to implement togcen into [grunt.js](https://github.com/gruntjs/grunt) using the [grunt-shell](https://github.com/sindresorhus/grunt-shell) extention.

<pre>

/* config grunt */

grunt.initConfig(
{
	shell:
	{
		tocCSS:
		{
			command: 'php ../tocgen.php css',
			stdout: true
		},
		tocJS:
		{
			command: 'php ../tocgen.php js',
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

Source file:

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

Result file:

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