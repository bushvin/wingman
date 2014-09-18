<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");

/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * autoload.php
 * 
 * function for automagically loading classes
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
include_once(FS_CLASS."logger.php");

function __autoload($class_name) {
	if ( file_exists(FS_CLASS .$class_name . ".php") ) {
	    logger::debug("__autoload: file '" .FS_CLASS .$class_name . ".php". "' exists. Loading.");
		include_once(FS_CLASS .$class_name . ".php");
	} else {
	    logger::debug(FS_CLASS .$class_name . ".php". "' doesn't exists.");
		$class = "class " .$class_name. " {};";
		eval($class);
	}
}
?>