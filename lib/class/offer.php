<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * offer.php
 * 
 * class to manage offer objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class offer extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_offer");
        $this->setObjectType("offer");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql= "SELECT `a`.`id`, CONCAT(`a`.`reference_prefix`,`a`.`reference`) `reference`, `a`.`description`,`b`.`name` AS `customer`, DATE_FORMAT(`a`.`date`,'%Y-%m-%d') AS `date`, IF(`a`.`purchased`>0,'" .YES. "','" .NO. "') AS `purchased`
					FROM `".$this->table()."`AS `a` 
					LEFT JOIN `##_SPACE_entity` AS `b` ON (`a`.`entity_id`=`b`.`id`) ";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>0 AND `b`.`deleted`=0) AND (" .$filter. ")";

		return parent::_ls($sql,$filter,$sort,$limit);
    }
    
    public function details() {
        global $DBO;
		$sql = "SELECT `a`.`id`,`a`.`disabled`,
						`b`.`name` AS `customer`, `a`.`entity_id`, 
						`a`.`reference_prefix`,`a`.`reference`, `a`.`description`, `a`.`remark`, `a`.`contact`,
						IF(STRCMP(`a`.`id`,'-1'),DATE_FORMAT(`a`.`date`,'%Y-%m-%d'),CURDATE()) AS `date`, 
						`a`.`vat_id`, `c`.`name` AS `vat`, `c`.`type` AS `vat_type`, `c`.`amount` AS `vat_amount`,
						`a`.`currency_id`, `d`.`name` AS `currency`, `d`.`sign` AS `currency_symbol`,
						`a`.`purchased`,
						`a`.`delivery_address_id`,
						`a`.`invoice_address_id`,
						`a`.`payment`
					FROM `".$this->table()."`AS `a`
					LEFT JOIN `##_".$this->space()."_entity` AS `b` ON (`a`.`entity_id`=`b`.`id` AND `b`.`deleted`=0) 
					LEFT JOIN `##_".$this->space()."_vat` AS `c` ON (`c`.`id`=`a`.`vat_id` and `c`.`deleted`=0)
					LEFT JOIN `##_".$this->space()."_currency` AS `d` ON (`d`.`id`=`a`.`currency_id` and `d`.`deleted`=0)
					WHERE `a`.`deleted`=0 AND `a`.`id`=" .$this->get("id"). ";";
		$DBO->query($sql);
		$this->importFromDB($DBO->result('object'));
		
		if ($this->get("id")==-1) {
            $sql = "SELECT `a`.`offer_reference_prefix`,`a`.`offer_reference_auto_increment` FROM `##_customer` `a`
                        LEFT JOIN `##_auth_user` `b` ON (`a`.`id`=`b`.`customer_id`)
                        WHERE `b`.`id`='" .$_SESSION["auth::id"]. "';";
            $DBO->query($sql);
            if ($DBO->result_count==1) {
                $res = $DBO->result("object");
                $this->set("reference_prefix", $res->offer_reference_prefix);
                
                if ( $res->offer_reference_auto_increment === 1 ) {
                    
                }

                $sql = "SELECT IFNULL((MAX(`reference`)+1),1) AS `max` FROM `".$this->table()."` WHERE `deleted`='0' AND `disabled`='0';";
                $DBO->query($sql);
                if ($DBO->result_count==1) {
                    $this->set("reference", $DBO->result("single"));
                }
                if ($this->get("reference")==0) $this->set("reference", 1);
            }
		    
		}

        $sql = "SELECT `a`.* ,
                        `b`.`name` `inventory_item_name`
                FROM `".$this->table()."_item` `a` 
                LEFT JOIN `##_".$this->space()."_inventory_item` `b` ON (`a`.`inventory_item_id`=`b`.`id`)
                WHERE `a`.`deleted`=0 AND `a`.`offer_id`=" .$this->get("id"). ";";
		$DBO->query($sql);
        $items = $DBO->result('objectlist');
		$calculated = array();
		$vCalculated = array();
		foreach ($items as $k=>$o) {
		    $items[$k]->vat = $this->calculateVat($o->vat_id,$o->price);
		    if ( ! isset($calculated[$o->id.":".$o->vat_id]) ) {
		        $calculated[$o->id.":".$o->vat_id] = clone $o;
		    } else {
		        $calculated[$o->id.":".$o->vat_id]->volume += $o->volume;
		    }
		    if ( ! isset($vCalculated[$o->vat_id])) {
		        $vCalculated[$o->vat_id] = ($o->price*$o->volume);
		    } else {
		        $vCalculated[$o->vat_id] += ($o->price*$o->volume);
		    }
		}
		$this->set("calculated_vat",$vCalculated);
		$this->set("calculated",$calculated);
		$this->set("item",$items);
		
		$sql = "SELECT * FROM `##_".$this->_space."_entity_address`  WHERE `id`='" .$this->get("invoice_address_id"). "';";
		$DBO->query($sql);
		$this->set("invoice_address", $DBO->result("object"));
		
		$sql = "SELECT * FROM `##_".$this->_space."_entity_address`  WHERE `id`='" .$this->get("delivery_address_id"). "';";
		$DBO->query($sql);
		$this->set("delivery_address", $DBO->result("object"));
		
    }
    
    private function calculateVat($vat_id, $amount) {
        $vat = new vat($vat_id);
        return $vat->calculate($amount);
    }

    public function updateFromObject($obj) {
        global $DBO;
        if (isset($obj->item)) {
            $items = $obj->item;
            unset($obj->item);
        } else {
            $items = array();
        }
        $obj = parent::_updateFromObject($obj);
        $par = $obj;
        $ids = array();
        foreach ($items as $obj) {
            if (is_null($obj)) continue;
            $change_type = "modify";
            if ($obj->id == -1) {
                $obj->id = $DBO->nextId($this->table()."_item");
                $sql = "INSERT INTO `".$this->table()."_item` (`id`,`offer_id`) VALUES('" .$obj->id. "','" .$par->id. "');";
                $DBO->query($sql);
                $change_type="create";
            }
            $sql = "SELECT * FROM `".$this->table()."_item` WHERE `id`='" .$DBO->escape($obj->id). "';";
            $DBO->query($sql);
            if ($DBO->result_count !== 1) {
                return false;
            }
            
            $original = $DBO->result("object");
            $update = array();
            $change = array();
            $exclude = array("id","deleted","disabled");
            foreach ($original as $field=>$value) {
                if ( in_array($field,$exclude) ) {
                    continue;
                }
                if (isset($obj->$field) && $DBO->escape($obj->$field) != $value) {
                    $update[] = "`" .$field. "`='" .$obj->$field. "'";
                    $change[] = "('" .$change_type. "','" .$this->table(). "_item','" .$field. "','" .$obj->id. "','" .$_SESSION["auth::id"]. "','" .$value. "','" .$obj->$field. "')";
                }
            }
            if (count($update) > 0) {
                $sql = "UPDATE `".$this->table()."_item` SET " .implode(",",$update). " WHERE `id`='" .$DBO->escape($obj->id). "';";
                $DBO->query($sql);
                
                $sql = "INSERT INTO `##_" .$this->space(). "_logging` (`type`,`table`,`column`,`entry_id`,`owner_id`,`old`,`new`) VALUES ".implode(",",$change). ";";
                $DBO->query($sql);
            }
            $ids[] = $DBO->escape($obj->id);
        }
        $ids[] = -1;
        $sql = "UPDATE `" .$this->table(). "_item` SET `deleted`='1' WHERE `offer_id`='" .$par->id. "' AND  `id` NOT IN ('" .implode("','",$ids). "');";
        $DBO->query($sql);
        
        $sql = "SELECT `id` FROM `" .$this->table(). "_item` WHERE `offer_id`='" .$par->id. "' AND  `id` NOT IN ('" .implode("','",$ids). "');";
        $DBO->query($sql);
        $dids = $DBO->result("objectlist");
        $change = array();
        foreach ($dids as $id) {
            $change[] = "('delete','" .$this->table(). "_item','n/a','" .$id->id. "','" .$_SESSION["auth::id"]. "','n/a','n/a')";
        }
        if ( count($change)>0 )  {
            $sql = "INSERT INTO `##_" .$this->space(). "_logging` (`type`,`table`,`column`,`entry_id`,`owner_id`,`old`,`new`) VALUES ".implode(",",$change). ";";
            $DBO->query($sql);
        }
        return $this->get("id");
    }

    public function rm($id) {
        global $DBO;
        $r = parent::_rm($id);
        if (is_array($id)) {
            $sql = "UPDATE `" .$this->table(). "_item` SET `deleted`='1' WHERE `offer_id` IN ('" .implode("','",$id). "');";
            $DBO->query($sql);
            return true;
        } elseif (is_string($id) || is_numeric($id)) {
            $sql = "UPDATE `" .$this->table(). "_item` SET `deleted`='1' WHERE `offer_id`='" .$id. "';";
            $DBO->query($sql);
            return true;
        }
        return false;    
    }
}
?>