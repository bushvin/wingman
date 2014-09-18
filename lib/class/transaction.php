<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * transaction.php
 * 
 * class to manage transaction objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class transaction extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_transaction");
        $this->setObjectType("transaction");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT 	DATE_FORMAT(`a`.`date`,'%Y-%m-%d') AS `date`, 
						`a`.`id`,
						`a`.`type`, 
						CONCAT(`a`.`uid_prefix`,`a`.`uid`) `uid`, 
						`b`.`name` as `entity`, 
						`a`.`reference`,
						`a`.`account`, 
						IF(`a`.`satisfied`>0,'" .YES. "','" .NO. "') AS `satisfied`,
						IF(`a`.`satisfied`>0,'n/a',IF(`a`.`deadline`>-1,IF((UNIX_TIMESTAMP()-UNIX_TIMESTAMP(DATE_ADD(`a`.`date`, INTERVAL `a`.`deadline` DAY)))>0,'" .YES. "','" .NO. "'),'" .NO. "')) AS `overdue`
					FROM `".$this->table()."`AS `a` 
						LEFT JOIN `##_SPACE_entity` AS `b` ON (`a`.`entity_id`=`b`.`id`) ";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>0 AND `b`.`deleted`=0) AND (" .$filter. ")";
		list($list,$fields) = parent::_ls($sql,$filter,$sort,$limit);
		$list = $this->ls_numeric($list,array("uid"));
		return array($list,$fields);
    }
    
    public function details() {
        global $DBO;
		$sql = "SELECT `a`.`id`,`a`.`disabled`, `a`.`entity_id`, 
		               `b`.`name` AS `entity`, `a`.`offer_id`, 
		               IF(STRCMP(`a`.`reference`,''),`a`.`reference`,`c`.`reference`) AS `reference`,
		               `a`.`account`,`a`.`type`,
		               `a`.`uid_prefix`,
		               IF(STRCMP(`a`.`id`,'-1'),`a`.`uid`,MAX(`a`.`uid`)) `uid`
		               , `a`.`field0`,
		               IF(STRCMP(`a`.`id`,'-1'),DATE_FORMAT(`a`.`date`,'%Y-%m-%d'),CURDATE()) AS `date`, 
		               `a`.`deadline`,`a`.`discount_type`,`a`.`discount`,
		               IFNULL(`c`.`currency_id`,1) AS `currency_id`, `d`.`name` AS `currency`, `d`.`sign` AS `currency_symbol`,
		               IFNULL(`c`.`vat_id`,1) AS `vat_id`, `e`.`name` AS `vat`,
		               `a`.`notes`, 
		               `a`.`satisfied`
				FROM `".$this->table()."` AS `a`
				LEFT JOIN `##_".$this->space()."_entity` AS `b` ON (`b`.`id`=`a`.`entity_id` AND `b`.`deleted`=0)
				LEFT JOIN `##_".$this->space()."_offer` AS `c` ON (`c`.`id`=`a`.`offer_id` AND `c`.`deleted`=0)
				LEFT JOIN `##_".$this->space()."_currency` AS `d` ON (`d`.`id`=`c`.`currency_id` AND `d`.`deleted`=0)
				LEFT JOIN `##_".$this->space()."_vat` AS `e` ON (`e`.`id`=`c`.`vat_id` AND `e`.`deleted`=0)
				WHERE `a`.`deleted`=0 AND `a`.`id`=" .$this->get("id"). ";";        
		$DBO->query($sql);
		$this->importFromDB($DBO->result('object'));

		if ($this->get("id")==-1) {
            $sql = "SELECT `a`.`transaction_uid_prefix` FROM `##_customer` `a`
                        LEFT JOIN `##_auth_user` `b` ON (`a`.`id`=`b`.`customer_id`)
                        WHERE `b`.`id`='" .$_SESSION["auth::id"]. "';";
            $DBO->query($sql);
            if ($DBO->result_count==1) {
                $this->set("uid_prefix", $DBO->result("single"));
            }
            $sql = "SELECT IFNULL((MAX(`uid`)+1),1) AS `max` FROM `".$this->table()."` WHERE `deleted`='0' AND `disabled`='0';";
            $DBO->query($sql);
            if ($DBO->result_count==1) {
                $this->set("uid", $DBO->result("single"));
            }
            if ($this->get("uid")==0) $this->set("uid", 1);
            
		}
		
		$sql = "SELECT `a`.*, `b`.`sku`,`b`.`name`,`b`.`price`,`b`.`description`,`b`.`field0`
		                    FROM `".$this->table()."_item` `a`
		                    LEFT JOIN `##_".$this->space()."_offer_item` `b` ON (`a`.`offer_item_id`=`b`.`id`)
		                    WHERE `a`.`transaction_id`='" .$this->get("id"). "'
		                    ORDER BY `a`.`date`;";
		$DBO->query($sql);
		$items = $DBO->result('objectlist');
		$calculated = array();
		$vCalculated = array();
		foreach ($items as $k=>$o) {
		    $items[$k]->vat = $this->calculateVat($o->vat_id,$o->price);
		    if ( ! isset($calculated[$o->offer_item_id.":".$o->vat_id]) ) {
		        $calculated[$o->offer_item_id.":".$o->vat_id] = clone $o;
		    } else {
		        $calculated[$o->offer_item_id.":".$o->vat_id]->volume += $o->volume;
		    }
		    if ( ! isset($vCalculated[$o->vat_id])) {
		        $vCalculated[$o->vat_id] = ($o->price*$o->volume);
		    } else {
		        $vCalculated[$o->vat_id] += ($o->price*$o->volume);
		    }
		}
		$this->set("calculated_vat",$vCalculated);
		$this->set("calculated",$calculated);
		$this->set("item", $items);

    }

    private function calculateVat($vat_id, $amount) {
        $vat = new vat($vat_id);
        return $vat->calculate($amount);
    }

	public function save() {
/*
	    $obj = new stdClass();
	    // id	entity_id	offer_id	reference	account	type	uid_prefix	uid	period	field0	date	deadline	discount_type	discount	notes	satisfied
	    $obj->id = $this->get("id");
	    $obj->entity_id = $this->get("entity_id");
	    $obj->offer_id = $this->get("offer_id");
	    $obj->reference = $this->get("reference");
	    $obj->account = $this->get("account");
	    $obj->type = $this->get("type");
	    $obj->uid_prefix = $this->get("uid_prefix");
	    $obj->uid = $this->get("uid");
	    $obj->period = $this->get("period");
	    $obj->field0 = $this->get("field0");
	    $obj->date = $this->get("date");
	    $obj->deadline = $this->get("deadline");
	    $obj->discount_type = $this->get("discount_type");
	    $obj->discount = $this->get("discount");
	    $obj->notes = $this->get("notes");
	    $obj->satisfied = $this->get("satisfied");
	    $obj->item = $this->get("item");
*/	    
	    $this->updateFromObject($this->getObject());
	    $this->details();
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
                $sql = "INSERT INTO `".$this->table()."_item` (`id`,`transaction_id`) VALUES('" .$obj->id. "','" .$par->id. "');";
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
        $sql = "UPDATE `" .$this->table(). "_item` SET `deleted`='1' WHERE `transaction_id`='" .$par->id. "' AND  `id` NOT IN ('" .implode("','",$ids). "');";
        $DBO->query($sql);
        
        $sql = "SELECT `id` FROM `" .$this->table(). "_item` WHERE `transaction_id`='" .$par->id. "' AND  `id` NOT IN ('" .implode("','",$ids). "');";
        $DBO->query($sql);
        $dids = $DBO->result("objectlist");
        $change = array();
        foreach ($dids as $id) {
            $change[] = "('delete','" .$this->table(). "_item','n/a','" .$id->id. "','" .$_SESSION["auth::id"]. "','n/a','n/a')";
        }
        if (count($change)>0) {
            $sql = "INSERT INTO `##_" .$this->space(). "_logging` (`type`,`table`,`column`,`entry_id`,`owner_id`,`old`,`new`) VALUES ".implode(",",$change). ";";
            $DBO->query($sql);
        }
        return $this->get("id");
    }

    public function rm($id) {
        global $DBO;
        $r = parent::_rm($id);
        if (is_array($id)) {
            $sql = "UPDATE `" .$this->table(). "_item` SET `deleted`='1' WHERE `transaction_id` IN ('" .implode("','",$id). "');";
            $DBO->query($sql);
            return true;
        } elseif (is_string($id) || is_numeric($id)) {
            $sql = "UPDATE `" .$this->table(). "_item` SET `deleted`='1' WHERE `transaction_id`='" .$id. "';";
            $DBO->query($sql);
            return true;
        }
        return false;    
    }
    
}
?>