TOC Generator
=============

> Generate table of contents from CSS, JS and PHP files.


Config
------

Setup your table of contents block inside config.php.


Usage
------

Run <code>php tocgen.php "path to files"</code> from console.


Example
-------

Your source file:

<pre>
/* @section 1. First section */

.first
{
	margin: auto;
}

/* General comment */

.first > div
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

Your file result:

<pre>
/**
 * @tableOfContents
 *
 * 1. First section
 *    General comment
 * 2. Second section
 * 3. Third section
**/

/* @section 1. First section */

.first
{
	margin: auto;
}

/* General comment */

.first > div
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