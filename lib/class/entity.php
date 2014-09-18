<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * entity.php
 * 
 * class to manage entity objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class entity extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_entity");
        $this->setObjectType("entity");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
        $sql = "SELECT `a`.`id`,`a`.`name`,`a`.`uid`,`b`.`name` AS `type`, `a`.`tel`, `a`.`fax`, `a`.`www`, `a`.`email`, `a`.`VAT_number` 
						FROM `".$this->table()."` `a` 
						LEFT JOIN `".$this->table()."_type` AS `b` ON (`a`.`entity_type_id`=`b`.`id`) ";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>0 AND `b`.`deleted`=0) AND (" .$filter. ")";
		list($list,$fields) = parent::_ls($sql,$filter,$sort,$limit);
		$list = $this->ls_numeric($list,array("uid"));
		return array($list,$fields);
    }
    
    public function details() {
        global $DBO;
		$sql = "SELECT `a`.`id`,`a`.`disabled`,`a`.`name`,`a`.`salutation`,`a`.`notes`,
		                `a`.`id`,
						`b`.`name` AS `type`, `a`.`entity_type_id`, 
						`a`.`tel`, `a`.`fax`, `a`.`www`, `a`.`email`, 
						`a`.`VAT_number`, `a`.`managed`,  
						`c`.`name` AS `currency`, `a`.`default_currency_id`, 
						`d`.`name` AS `vat`, `a`.`default_vat_id`, 
						`a`.`managed`, `a`.`reference_prefix`, 
						`e`.`name` AS `correspondence_language`, `e`.`short` AS `correspondence_language_short`, `a`.`correspondence_language_id`,
						`a`.`uid`
					FROM `".$this->table()."` AS `a` 
					LEFT JOIN `".$this->table()."_type` AS `b` ON (`a`.`entity_type_id`=`b`.`id`) 
					LEFT JOIN `##_".$this->space()."_currency` AS `c` ON (`a`.`default_currency_id`=`c`.`id`) 
					LEFT JOIN `##_".$this->space()."_vat` AS `d` ON (`a`.`default_vat_id`=`d`.`id` AND `d`.`deleted`=0) 
					LEFT JOIN `##_language` AS `e` ON (`e`.`id`=`a`.`correspondence_language_id`)
					WHERE `a`.`deleted`=0 AND `b`.`deleted`=0 AND `c`.`deleted`=0 AND `e`.`deleted`=0 AND 
					`a`.`id`=" .$this->get("id");
		$DBO->query($sql);
		$u = $DBO->result('object');
		$this->importFromDB($DBO->result('object'));

		if ($this->get("id") == -1) {
		    $sql = "SELECT IFNULL((MAX(`uid`)+1),1) `uid` FROM `".$this->table()."` WHERE `deleted`='0';";
		    $DBO->query($sql);
		    $this->set("uid", $DBO->result("single"));
		}
        
        $sql = "SELECT * FROM `".$this->table()."_address` WHERE `deleted`=0 AND `entity_id`=" .$this->get("id"). ";";
		$DBO->query($sql);
		$this->set("address",$DBO->result('objectlist'));
    }
    
    public function updateFromObject($obj) {
        global $DBO;
        if (isset($obj->address)) {
            $address = $obj->address;
            unset($obj->address);
        } else {
            $address = array();
        }
        $obj = parent::_updateFromObject($obj);
        $par = $obj;
        $ids = array();
        foreach ($address as $obj) {
            if (is_null($obj)) continue;
            $change_type = "modify";
            if ($obj->id == -1) {
                $obj->id = $DBO->nextId($this->table()."_address");
                $sql = "INSERT INTO `".$this->table()."_address` (`id`,`entity_id`) VALUES('" .$obj->id. "','" .$par->id. "');";
                $DBO->query($sql);
                $change_type="create";
            }
            $sql = "SELECT * FROM `".$this->table()."_address` WHERE `id`='" .$DBO->escape($obj->id). "';";
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
                    $change[] = "('" .$change_type. "','" .$this->table(). "_address','" .$field. "','" .$obj->id. "','" .$_SESSION["auth::id"]. "','" .$value. "','" .$obj->$field. "')";
                }
            }
            if (count($update) > 0) {
                $sql = "UPDATE `".$this->table()."_address` SET " .implode(",",$update). " WHERE `id`='" .$DBO->escape($obj->id). "';";
                $DBO->query($sql);
                
                $sql = "INSERT INTO `##_" .$this->space(). "_logging` (`type`,`table`,`column`,`entry_id`,`owner_id`,`old`,`new`) VALUES ".implode(",",$change). ";";
                $DBO->query($sql);
            }
            $ids[] = $DBO->escape($obj->id);
        }
        $ids[] = -1;
        $sql = "UPDATE `" .$this->table(). "_address` SET `deleted`='1' WHERE `entity_id`='" .$par->id. "' AND  `id` NOT IN ('" .implode("','",$ids). "');";
        $DBO->query($sql);
        
        $sql = "SELECT `id` FROM `" .$this->table(). "_address` WHERE `entity_id`='" .$par->id. "' AND  `id` NOT IN ('" .implode("','",$ids). "');";
        $DBO->query($sql);
        $dids = $DBO->result("objectlist");
        $change = array();
        foreach ($dids as $id) {
            $change[] = "('delete','" .$this->table(). "_address','n/a','" .$id->id. "','" .$_SESSION["auth::id"]. "','n/a','n/a')";
        }
        if ( count($change)>0 ) {
            $sql = "INSERT INTO `##_" .$this->space(). "_logging` (`type`,`table`,`column`,`entry_id`,`owner_id`,`old`,`new`) VALUES ".implode(",",$change). ";";
            $DBO->query($sql);
        }
        return $this->get("id");
    }
    
    public function rm($id) {
        global $DBO;
        $r = parent::_rm($id);
        if (is_array($id)) {
            $sql = "UPDATE `" .$this->table(). "_address` SET `deleted`='1' WHERE `entity_id` IN ('" .implode("','",$id). "');";
            $DBO->query($sql);
            return true;
        } elseif (is_string($id) || is_numeric($id)) {
            $sql = "UPDATE `" .$this->table(). "_address` SET `deleted`='1' WHERE `entity_id`='" .$id. "';";
            $DBO->query($sql);
            return true;
        }
        return false;    
    }
}
?>