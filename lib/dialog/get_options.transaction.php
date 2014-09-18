<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * get_options.transaction.php
 * 
 * get_options.transaction dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

switch ($data->data->type) {
    case "offer":
        $sql = "SELECT `id`,`reference` FROM `##_" .$_SESSION['space']. "_offer` WHERE `entity_id`='" .$DBO->escape($data->data->entity_id). "' AND `deleted`='0';";
        $DBO->query($sql);
        foreach ($DBO->result("objectlist") as $row) {
            echo "<option value=\"" .$row->id. "\">" .$row->reference. "</option>";
        }
        break;
    case "offer_item":
        $sql = "SELECT `a`.`id`,CONCAT(`a`.`sku`,' - ',`a`.`name`,' - ',`a`.`price`) `name`
                        FROM `##_" .$_SESSION['space']. "_offer_item` `a` 
                        WHERE `offer_id`='" .$DBO->escape($data->data->offer_id). "'
                        AND `id`>-1 AND `deleted`='0'
                        ORDER BY `name`;";
        $DBO->query($sql);
        foreach ($DBO->result("objectlist") as $row) {
            echo "<option value=\"" .$row->id. "\">" .$row->name. "</option>";
        }
        break;
}
?>