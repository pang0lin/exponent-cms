<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################
/** @define "BASE" "." */

// Following code taken from http://us4.php.net/manual/en/function.get-magic-quotes-gpc.php
//   - it allows magic_quotes to be on without screwing stuff up.
// magic quotes were removed in php6
if(phpversion() < 6) { 
	if (get_magic_quotes_gpc()) {
		/**
		 * @param $value
		 * @return mixed
		 */
		function stripslashes_deep($value) {
			return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		}

		$_POST = stripslashes_deep($_POST);
		$_GET = stripslashes_deep($_GET);
		$_COOKIE = stripslashes_deep($_COOKIE);
	}
}

// for scripts that want to bootstrap minimally, we will need _realpath()
// if it isn't already defined.
if (!function_exists('__realpath')) {
	/**
	 * @param $path
	 * @return string
	 */
	function __realpath($path) {
		$path = str_replace('\\','/',realpath($path));
		if ($path{1} == ':') {
			// We can't just check for C:/, because windows users may have the IIS webroot on X: or F:, etc.
			$path = substr($path,2);
		}
		return $path;
	}
}

// Process user-defined constants first in overrides.php (if it exists)
include_once('overrides.php');

// load constants for paths and other environment  not overridden in overrides.php
require_once(dirname(__realpath(__FILE__)) . '/exponent_constants.php');

// load the code version
require_once(BASE.'exponent_version.php');

/*
 * EXPONENT Constant
 *
 * The EXPONENT Constant signals to other parts of the system that they are operating within the confines
 * of the Exponent Framework.  (Module actions check this -- if it is not defined, they must abort).
 */
define('EXPONENT', '1');

// load the constants from the global config, theme config, and then default config settings
require_once(BASE . 'framework/core/subsystems-1/config/load.php');

// Set the default timezone.
if (function_exists('date_default_timezone_set')) {
    @date_default_timezone_set(DISPLAY_DEFAULT_TIMEZONE);
}

if (!defined('DISPLAY_THEME')) {
	/* exdoc
	 * The directory and class name of the current active theme.  This may be different
	 * than the configure theme (DISPLAY_THEME_REAL) due to previewing.
	 */
	define('DISPLAY_THEME',DISPLAY_THEME_REAL);
}

if (!defined('THEME_ABSOLUTE')) {
	/* exdoc
	 * The absolute path to the current active theme's files.  This is similar to the BASE constant
	 */
	define('THEME_ABSOLUTE',BASE.'themes/'.DISPLAY_THEME.'/'); // This is the recommended way
}

if (!defined('THEME_RELATIVE')) {
	/* exdoc
	 * The relative web path to the current active theme.  This is similar to the PATH_RELATIVE constant.
	 */
	define('THEME_RELATIVE',PATH_RELATIVE.'themes/'.DISPLAY_THEME.'/');
}

// Process PHP-wrapper settings (ini_sets and setting detectors)
require_once(BASE . 'exponent_php_setup.php');

$info = gd_info();
define('EXPONENT_HAS_GD',($info['GD Version'] == 'Not Supported' ? 0 : 1));

?>