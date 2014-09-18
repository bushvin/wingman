<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * inventory.php
 * 
 * class to manage inventory objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class inventory extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_inventory_item");
        $this->setObjectType("inventory");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `a`.`id`,`a`.`name`, `a`.`sku`, `d`.`sign` AS `currency`, `a`.`indicative_price` AS `price`, `c`.`name` AS `vat`, `b`.`name` AS `type`
					FROM `".$this->table()."`AS `a` 
					LEFT JOIN `".$this->table()."_type` AS `b` ON (`a`.`inventory_type_id`=`b`.`id` AND `b`.`deleted`=0) 
					LEFT JOIN `##_SPACE_vat` AS `c` ON (`a`.`indicative_vat_id`=`c`.`id`)
					LEFT JOIN `##_SPACE_currency` AS `d` ON (`a`.`indicative_currency_id`=`d`.`id`)";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>0 AND `b`.`deleted`=0 AND `c`.`deleted`=0 AND `d`.`deleted`=0) AND (" .$filter. ")";

		list($list,$fields) = parent::_ls($sql,$filter,$sort,$limit);
		$list = $this->ls_money($list,array("price"));
		return array($list,$fields);
    }
    
    public function details() {
        global $DBO;
		$sql = "SELECT `a`.`id`,`a`.`disabled`,`a`.`sku`, `a`.`name`, `a`.`description`, `b`.`name` AS `type`, `a`.`inventory_type_id`, `a`.`indicative_price`, `a`.`indicative_vat_id`, `c`.`name` AS `indicative_vat`, `a`.`indicative_currency_id`, `d`.`name` AS `indicative_currency`, `a`.`field0`
				FROM `".$this->table()."` AS `a`
				LEFT JOIN `".$this->table()."_type` AS `b` ON (`b`.`deleted`=0 AND `b`.`id`=`a`.`inventory_type_id`)
				LEFT JOIN `##_".$this->space()."_vat` AS `c` ON (`a`.`indicative_vat_id`=`c`.`id` AND `c`.`deleted`=0) 
				LEFT JOIN `##_".$this->space()."_currency` AS `d` ON (`a`.`indicative_currency_id`=`d`.`id` AND `d`.`deleted`=0) 
				WHERE `a`.`deleted`=0 AND `a`.`id`=" .$this->get("id"). ";";
		$DBO->query($sql);
		$this->importFromDB($DBO->result('object'));
    }

}
?>