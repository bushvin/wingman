<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * dialog.php
 * 
 * interface to retrieve html dialogs
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

include("../lib/global.php");
include_once(FS_LANGUAGE.APP_LANGUAGE."/default.php");

if ( SSL_FORCE === true && !isset($_SERVER['HTTPS'])  ) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}
if ($_SESSION["auth::id"] == -1) {
	header('HTTP/1.1 401 Unauthorized');
	exit();
}
$data = json_decode(file_get_contents('php://input'));
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-type: text/html; charset=utf-8");


if ( file_exists(FS_DIALOG .$data->data->dialog. ".php") ) {
    $data->data->view = $DBO->escape($data->data->view);
	include_once(FS_DIALOG .$data->data->dialog. ".php");
} else {
	header('HTTP/1.1 404 Not Found');
//	echo "1007";
	exit();
}

?>
