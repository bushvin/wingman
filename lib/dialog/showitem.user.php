<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.user.php
 * 
 * showitem.user dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//


$o = new user($data->data->id);
$o->details();
$sql = "SELECT `id`,`name` FROM `##_language` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$lang = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_customer` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$customers = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_auth_role` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$roles = $DBO->result("objectlist");


//print_r($o);
?><div class="title"><?php echo ucwords(USER_EDIT_USER); ?></div>
<div class="form">
    <div class="column">
        <div class="row login">
            <div class="caption"><?php echo ucwords(USER_LOGIN); ?><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" /></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('login'); ?>" placeholder="<?php echo ucwords(USER_LOGIN); ?>" id="login"/></div>
        </div>
        <div class="row password">
            <div class="caption"><?php echo ucwords(USER_PASSWORD); ?></div>
            <div class="value"><input class="input-text" type="text" value="" id="password"/></div>
        </div>
        <div class="row name">
            <div class="caption"><?php echo ucwords(USER_NAME); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" placeholder="<?php echo ucwords(USER_NAME); ?>" id="name"/></div>
        </div>
        <div class="row email">
            <div class="caption"><?php echo ucwords(USER_EMAIL); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('email'); ?>" placeholder="<?php echo ucwords(USER_EMAIL); ?>" id="email"/></div>
        </div>
        <div class="row language">
            <div class="caption"><?php echo ucwords(USER_LANGUAGE); ?></div>
            <div class="value"><select class="select" id="language_id"><?php 
            foreach($lang as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("language_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
        <div class="row customer">
            <div class="caption"><?php echo ucwords(USER_CUSTOMER); ?></div>
            <div class="value"><select class="select" id="customer_id"><?php 
            foreach($customers as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("customer_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
        <div class="row token_timeout">
            <div class="caption"><?php echo ucwords(USER_TOKEN_TIMEOUT); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('token_timeout'); ?>" placeholder="<?php echo ucwords(USER_TOKEN_TIMEOUT); ?>" id="token_timeout"/></div>
        </div>
    </div>
    <div class="column roles">
        <div class="row roles">
            <div class="caption"><?php echo ucwords(USER_ROLES); ?></div>
            <div class="value"><?php
            $count = 0;
            foreach ($roles as $e) {
                echo "<div><div class=\"checkbox\"><input type=\"checkbox\" id=\"role_id[${count}]\" value=\"" .$e->id. "\"" .(in_array($e->id,$o->get("role_id"))?" checked":""). "><label for=\"role_id[${count}]\"></label></div>";
                echo "<label for=\"role_id[${count}]\">" .$e->name. "</label></div>";
                $count++;
            }
?></div>
        </div>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
</div>