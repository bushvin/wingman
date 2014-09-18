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
 * logger.php
 * 
 * logger function to log stuff to some place
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class logger {
	public static function level ( $level = "warning" ) {
		$levels = Array("debug","info","warning","error");
		if (in_array($level, $levels)) {
		    define("LOG_LEVEL",$level);
		}
	}
	
	public static function add( $severity, $message ) {
		if ( ! defined("LOG_LEVEL")) define("LOG_LEVEL","warning");
		$log = false;
		switch (LOG_LEVEL) {
			case "debug":
				if ($severity == "debug") $log = true;
			case "info":
				if ($severity == "info") $log = true;
			case "warning":
				if ($severity == "warning") $log = true;
			case "error":
				if ($severity == "error") $log = true;
				break;
		}
		if ($log) {
			error_log($severity.": " .$message);
		}
	}
	
	public static function info($message) {
		logger::add("info", $message);
	}
	
	public static function warning($message) {
		logger::add("warning", $message);
	}
	
	public static function error($message) {
		logger::add("error", $message);
	}
	
	public static function debug($message) {
		logger::add("debug", $message);
	}
}
?>
