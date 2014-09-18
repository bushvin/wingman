<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * api.php
 * 
 * interface to the application
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
ini_set('max_execution_time', 14400);

/* include base needs */
include("../lib/global.php");
include_once(FS_LANGUAGE.APP_LANGUAGE."/default.php");

if ( SSL_FORCE === true && !isset($_SERVER['HTTPS'])  ) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

$data = json_decode(file_get_contents('php://input'));

if ( !isset($data) || !is_object($data) ) $data = new stdClass();
if (!isset($data->id)) $data->id = -1;
$data->id = (int)$data->id;

if (!isset($data->token)) $data->token = "";
if (!isset($data->api)) $data->api = "error_no_data";
if (!isset($data->data)) $data->data = new stdClass();


$envelope = new envelope($data);
$envelope->returnResult();

?>