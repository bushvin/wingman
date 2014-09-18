<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * error.php
 * 
 * error dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
$eid = $data->data->eid;
switch (true) {
    case $eid < 1000:
?>
    <div class="message error"><?php echo ucfirst(WARNING_THIS_DOES_NOT_COMPUTE); ?><br />Code: <?php echo $eid; ?></div>
    <div class="buttons"><div class="button blue" onclick="location.reload(true);"><?php echo ucwords(RELOAD_PAGE); ?></div></div>
<?php
        break;
    case $eid == 1001:
?>
    <div class="message error"><?php echo ucfirst(ERROR_USER_PASSWORD_INCORRECT); ?></div>
    <div class="buttons"><div class="button blue" onclick="app.cookie.set('id',-1);app.cookie.set('token','');location.reload(true);"><?php echo ucwords(GO_TO_LOGIN_PAGE); ?></div></div>
<?php
        break;
    case $eid == 1002:
?>
    <div class="message error"><?php echo ucfirst(ERROR_USER_TOKEN_INVALID); ?></div>
    <div class="buttons"><div class="button blue" onclick="app.cookie.set('id',-1);app.cookie.set('token','');location.reload(true);"><?php echo ucwords(GO_TO_LOGIN_PAGE); ?></div></div>
<?php
        break;
    case $eid == 1003:
?>
    <div class="message error"><?php echo ucfirst(ERROR_OLD_PASSWORD_INVALID); ?></div>
    <div class="buttons"><div class="button blue" onclick="app.error.hide();"><?php echo ucwords(CLOSE); ?></div></div>
<?php
        break;
    case $eid == 1004:
?>
    <div class="message error"><?php echo ucfirst(ERROR_ITEM_COULD_NOT_BE_REMOVED); ?></div>
    <div class="buttons"><div class="button blue" onclick="app.error.hide();"><?php echo ucwords(CLOSE); ?></div></div>
<?php
        break;
    case $eid == 1005:
?>
    <div class="message error"><?php echo ucfirst(ERROR_API_DOESNT_EXIST); ?></div>
    <div class="buttons"><div class="button blue" onclick="app.error.hide();"><?php echo ucwords(CLOSE); ?></div></div>
<?php
        break;
    case $eid > 999:
?>
    <div class="message error"><?php echo ucfirst(ERROR_GENERAL_1000); ?><br />Code: <?php echo $eid; ?></div>
    <div class="buttons"><div class="button blue" onclick="location.reload(true);"><?php echo ucwords(RELOAD_PAGE); ?></div></div>
<?php
        break;
    default:
}
?>