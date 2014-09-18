<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.entity.php
 * 
 * showitem.entity dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
$o = new role($data->data->id);
$o->details();

$sql = "SELECT `id`,CONCAT(`name`,' (',`login`,')') `name` FROM `##_auth_user` WHERE `deleted`='0' ORDER BY `name`;";
$DBO->query($sql);
$users = $DBO->result("objectlist");

$sql = "SELECT `a`.*, IF(IFNULL(`b`.`auth_role_id`,-1)>0,1,0) `applied`
            FROM `##_auth_ace` `a`
            LEFT JOIN `##_auth_acl` `b` ON (`b`.`auth_ace_id`=`a`.`id` AND `b`.`deleted`=0 AND `b`.`auth_role_id`=".$o->get("id").")
            WHERE `a`.`deleted`=0
            ORDER BY `applied` desc, `a`.`name`;";
$DBO->query($sql);
$acl = $DBO->result("objectlist");
?>
<div class="title"><?php echo ucwords(ROLE_EDIT_ROLE); ?></div>
<div class="form">
    <div class="column">
        <div class="row name">
            <div class="caption"><?php echo ucwords(ROLE_NAME); ?><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" /></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" placeholder="<?php echo ucwords(ROLE_NAME); ?>" id="name"/></div>
        </div>
        <div class="row description">
            <div class="caption"><?php echo ucwords(ROLE_DESCRIPTION); ?></div>
            <div class="value"><textarea id="notes" class="textarea" placeholder="<?php echo ucwords(ROLE_DESCRIPTION); ?>"><?php echo $o->get('description'); ?></textarea></div>
        </div>
    </div>
    <div class="column members">
        <div class="row users">
            <div class="caption"><?php echo ucwords(ROLE_USERS); ?></div>
            <div class="value"><?php
            $count = 0;
            foreach ($users as $e) {
                echo "<div><div class=\"checkbox\"><input type=\"checkbox\" id=\"user_id[${count}]\" value=\"" .$e->id. "\"" .(in_array($e->id,$o->get("user_id"))?" checked":""). "><label for=\"user_id[${count}]\"></label></div>";
                echo "<label for=\"user_id[${count}]\">" .$e->name. "</label></div>";
                $count++;
            }
?></div>
        </div>
    </div>
    <div class="column acl">
        <div class="row acl">
            <div class="caption"><?php echo ucwords(ROLE_ACL); ?></div>
            <div class="value"><?php 
            $count = 0;
            foreach ($acl as $e) {
                echo "<div><div class=\"checkbox\"><input type=\"checkbox\" id=\"ace_id[${count}]\" value=\"" .$e->id. "\"" .(in_array($e->id,$o->get("ace_id"))?" checked":""). "><label for=\"ace_id[${count}]\"></label></div>";
                echo "<label for=\"ace_id[${count}]\">" .constant("ACE_" .strtoupper($e->name)). "</label></div>";
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
