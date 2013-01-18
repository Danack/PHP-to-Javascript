PHP-to-Javascript
=================

A tool for converting simple PHP objects to Javascript code, so that code for manipulating objects can be used both server-side and client-side.


How to use
==========

Examples
--------

php examples.php

This will convert the files in PHP-to-Javascript/src and for each write a javascript file PHP-to-Javascript/export.


Programmatically
----------------

Include the file PHP-to-Javascript/PHPToJavascript.php in your file and then call:

$javascript = new PHPToJavascript($srcFilename)->toJavascript();

Limitations
===========

There are several features of the PHP language that either are too difficult to map into Javascript, or are just not possible in Javascript.


Pass by reference
-----------------

This feature does not exist in Javascript and so is not supported.

Static class variables are always public
----------------------------------------

Due to the way objects in Javascript are _implemented_ static class variables will always have public scope.


Defines are converted to value, but not defined in Javascript.
-------------------------------------------------------------

The code:

    define('DATABASE_TYPE', 'MySQL');
	echo "Database type is ".DATABASE_TYPE;

is converted to:

    // define('DATABASE_TYPE', 'MySQL');
    document.write( "Database type is " + 'MySQL');



TODO
====

* Keyword 'PUBLIC' inside a class scope is not implemented.

* Figure out how to set flags for what should be done with echo.

* Add more examples.