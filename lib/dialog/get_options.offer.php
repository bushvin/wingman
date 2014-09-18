<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * get_options.offer.php
 * 
 * get_options.offer dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
switch ($data->data->type) {
    case "address":
        $sql = "SELECT `id`,CONCAT(`address_type`,' (', `street`,', ',`code`,' ',`city`,')') `address_type` FROM `##_" .$_SESSION['space']. "_entity_address` WHERE `deleted`=0 AND `id`>0 AND `entity_id`='" .$DBO->escape($data->data->entity_id). "' ORDER BY `address_type`;";
        $DBO->query($sql);
        $eaddr = $DBO->result("objectlist");
        foreach($eaddr as $e) {
            echo "<option value=\"" .$e->id. "\">" .$e->address_type. "</option>";
        }
        break;
}
?>