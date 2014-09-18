<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * advanced.php
 * 
 * Advanced file operations
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
ini_set('max_execution_time', 14400);

/* include base needs */
include("../lib/global.php");
if ( SSL_FORCE === true && !isset($_SERVER['HTTPS'])  ) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}
$ID = (int)$DBO->escape($_GET["id"]);
$ITEM_IDS = json_decode($DBO->escape($_GET["itemIds"]));

$sql = "SELECT * FROM `##_" .$_SESSION["space"]. "_advanced` WHERE `deleted`=0 AND `id`=" .$ID. ";";
$DBO->query($sql);
if ($DBO->result_count==0) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
$row = $DBO->result("object");
if ( file_exists(FS_ADVANCED .$_SESSION["space"]. "/".$row->view."/".$row->include) ) {
    include_once(FS_ADVANCED .$_SESSION["space"]. "/".$row->view."/".$row->include);
    exit();
} else {
	header('HTTP/1.1 404 Not Found');
	exit();
}


?>