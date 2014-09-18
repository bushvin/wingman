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
 * Advanced dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
$user = new user($_SESSION["auth::id"]);
$acl = $user->acl($data->data->view);
if ( $data->data->view !== "dashboard") {
    $cls = $DBO->escape($data->data->view);
    $o = new $cls();
    $count = $o->recordcount();
}
$sql = "SELECT `id`,`name`,`type` FROM `##_" .$_SESSION['space']. "_advanced` WHERE `deleted`=0 AND `view`='" .$DBO->escape($data->data->view). "' ORDER BY `order`,`name`;";
$DBO->query($sql);
$actions = $DBO->result("objectlist");
foreach ($actions as $k=>$o) {
    if (defined("ADVANCED_" .strtoupper($o->name))) {
        $actions[$k]->name = constant("ADVANCED_" .strtoupper($o->name));
    }
    $actions[$k]->name = ucwords($actions[$k]->name);
}
if ($DBO->result_count>0) { ?>
<script>
$(function() {
        app.control.actionlist({items:<?php echo json_encode($actions)?>});
})
</script><?php } 
if ($data->data->view != "dashboard") {?>
<div class="button<?php echo ($acl->create===true?" blue":" disabled"); ?>" onclick="app.item.create();"><?php echo ucwords(CREATE); ?></div>
<div class="button<?php echo ($acl->delete===true?"":" disabled"); ?>" onclick="app.item.delete();"><?php echo ucwords(DELETE); ?></div>
<?php }
if ($DBO->result_count>0) { ?>
<div class="button more" onclick="app.control.toggleActionlist();"><div class="image"><?php echo ucwords(MORE_ACTIONS); ?></div></div>
<?php } ?>
<div class="right">
<div class="button settings" onclick="app.control.toggleSettingsDialog();"><div class="gears"></div></div>
</div>
