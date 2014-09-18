<?php
session_start();
define("__WINGMAN__","started");

/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * global.php
 * 
 * global configuration for the wingman application
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

include_once('configuration.php');

define("APP_NAME", "Wingman");
define("APP_VERSION","5");
define("APP_RELEASE","201404011642");
define("APP_STATUS","DEV");

define("FS_HTDOCS",FS_ROOT."htdocs/");
define("FS_LIB",FS_ROOT."lib/");
define("FS_CLASS",FS_LIB."class/");
define("FS_LANGUAGE",FS_LIB."languages/");
define("FS_DIALOG",FS_LIB."dialog/");
define("FS_ADVANCED",FS_LIB."advanced/");
define("FS_VAR",FS_LIB."var/");

define("WW_JS",WW_ROOT. "js/");
define("WW_CSS",WW_ROOT. "css/");
define("WW_images",WW_ROOT. "images/");

include_once(FS_LIB. 'autoload.php');

$DBO = new dbo(DBO_HOST, DBO_USER, DBO_PASSWORD, DBO_DB, DBO_TBL_PREFIX);

if ( !isset($_SESSION["auth::id"]) ) $_SESSION["auth::id"] = -1;
if ( !isset($_SESSION["auth::token"]) ) $_SESSION["auth::token"] = "";
if ( !isset($_SESSION["space"]) ) $_SESSION["space"] = "";
$AUTH = new auth();
list($_SESSION["auth::id"],$_SESSION["auth::token"]) = $AUTH->verifyToken($_SESSION["auth::id"],$_SESSION["auth::token"]);
$tUser = new user($_SESSION["auth::id"]);
$tUser->details();
$_SESSION["space"] = $tUser->get("space");

if ( isset($_SESSION["auth::id"]) && $_SESSION["auth::id"] > -1 ) {
    $sql = "SELECT `a`.`short` FROM `##_language` `a`
                    LEFT JOIN `##_auth_user` `b` ON (`b`.`language_id`=`a`.`id`)
                    WHERE `a`.`deleted`=0 AND `b`.`id`='" .$_SESSION["auth::id"]. "' LIMIT 0,1";
    $DBO->query($sql);
    if ($DBO->result_count == 1) {
        define("APP_LANGUAGE",$DBO->result("single"));
    } else {
        define("APP_LANGUAGE",DEFAULT_LANGUAGE);
    }
    $sql = "SELECT `a`.* FROM `##_locale` `a`
                    LEFT JOIN `##_auth_user` `b` ON (`b`.`locale_id`=`a`.`id`)
                    WHERE `a`.`deleted`=0 AND `b`.`id`='" .$_SESSION["auth::id"]. "' LIMIT 0,1";
    $DBO->query($sql);
    if ($DBO->result_count != 1) {
        $sql = "SELECT * FROM `##_locale` WHERE `name`='" .DEFAULT_LOCALE. "';";
        $DBO->query($sql);
    }
    $locale = $DBO->result("object");
    define("APP_LOCALE",$locale->name);
    define("APP_DECIMAL_SEPARATOR",$locale->decimal_separator);
    define("APP_THOUSAND_SEPARATOR","");

} else {
    define("APP_LANGUAGE",DEFAULT_LANGUAGE);
    $sql = "SELECT * FROM `##_locale` WHERE `name`='" .DEFAULT_LOCALE. "';";
    $DBO->query($sql);
    $locale = $DBO->result("object");
    define("APP_LOCALE",$locale->name);
    define("APP_DECIMAL_SEPARATOR",$locale->decimal_separator);
    define("APP_THOUSAND_SEPARATOR","");
}
//include_once(FS_LANGUAGE.APP_LANGUAGE."/default.php");
if ( !defined("MAINTENANCE") ) define("MAINTENANCE",false);
if ( !defined("MAINTENANCE_MSG") ) define("MAINTENANCE_MSG","The application is currently down for maintenance. Please try again later.");
?>