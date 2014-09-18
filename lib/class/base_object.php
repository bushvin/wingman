<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * base_object.php
 * 
 * base object definitions inherited by
 *              - entity
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class base_object {
    public $_id = -1;
    public $_table = "";
    public $_list = array();
    public $_object_type = "";
    public $_space = "";
    public $_data;

    public function __construct($id = -1) {
        global $DBO;
        $this->_data = new stdClass();
        $this->set("id", $id);
        $sql = "SELECT `a`.`space` FROM `##_customer` `a`
                        LEFT JOIN `##_auth_user` `b` ON (`a`.`id`=`b`.`customer_id`)
                        WHERE `a`.`deleted`=0 AND `b`.`id`='" .$_SESSION["auth::id"]. "';";
        $DBO->query($sql);
        if ($DBO->result_count==1) {
            $this->setSpace($DBO->result("single"));
        }
        return $this;
    }
    
    public function importFromDB($data) {
        if (! is_object($data)) {
            $data = new stdClass();
        }
        $data->id = $this->get("id");
        $this->_data = $data;
        return $this;
    }
    
    public function getObject() {
        return $this->_data;
    }
    
    public function setTable($table) {

        $this->_table = str_replace("_SPACE_", "_".$this->space()."_", $table);;
        return true;
    }
    
    public function setSpace($space) {
        $this->_space = $space;
        return true;
    }
    
    public function setObjectType($ot) {
        $this->_object_type = $ot;
        return true;
    }
    
    public function space() {
        return $this->_space;
    }
    
    public function table() {
        return $this->_table;
    }

    public function objectType() {
        return $this->_object_type;
    }

    public function _ls($query, $filter = 1, $sort = "", $limit = "") {
        global $DBO;
		$return = new stdClass();
		$sql = $query." WHERE ".$filter;
		$sql = str_replace("_SPACE_","_".$this->space()."_",$sql);
		$DBO->query($sql);
		$list = $DBO->result("objectlist");
		$list = $this->ls_numeric($list,array("id"));
		return array($list,$DBO->fields);
    }
    
    public function ls_numeric($list,$fields) {
		if (! is_array($list) ) return $list;
		if (! is_array($fields)) return $list;

		foreach ($list as $idx=>$row) {
		    foreach ($row as $k=>$v) {
    		    if (in_array($k,$fields)) {
    		        $list[$idx]->$k = (float)$v;
    		    }
		    }
		}
		return $list;
    }
    
    public function ls_money($list,$fields) {
		if (! is_array($list) ) return $list;
		if (! is_array($fields)) return $list;

		foreach ($list as $idx=>$row) {
		    foreach ($row as $k=>$v) {
    		    if (in_array($k,$fields)) {
    		        $list[$idx]->$k = number_format($v,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR);
    		    }
		    }
		}
		return $list;
    }
    
    public function recordcount() {
        global $DBO;
        $sql = "SELECT count(`id`) FROM `" .$this->table(). "` WHERE `id`>-1 AND `deleted`='0';";
        $DBO->query($sql);
        return (int)$DBO->result("single");
    }

	public function get($key) {
	    if ( isset($this->_data->$key)) {
	        return $this->_data->$key;
	    }
	    return null;
	}
	
	public function set($key,$value) {
	    $this->_data->$key = $value;
	    return true;
	}

	public function save() {
	    return false;
	}
	
	public function _updateFromObject($obj) {
        global $DBO;
        $change_type = "modify";
        if ($obj->id == -1) {
            $obj->id = $DBO->nextId($this->table());
            $sql = "INSERT INTO `".$this->table()."` (`id`) VALUES('" .$obj->id. "');";
            $DBO->query($sql);
            $change_type="create";
        }
        
        $sql = "SELECT * FROM `".$this->table()."` WHERE `id`='" .$DBO->escape($obj->id). "';";
        $DBO->query($sql);
        if ($DBO->result_count !== 1) {
            return false;
        }
        $original = $DBO->result("object");
        $update = array();
        $change = array();
        $exclude = array("id","deleted","disabled","managed");
        foreach ($original as $field=>$value) {
            if ( in_array($field,$exclude) ) {
                continue;
            }
            if (isset($obj->$field) && $DBO->escape($obj->$field) != $value) {
                $update[] = "`" .$field. "`='" .$obj->$field. "'";
                $change[] = "('" .$change_type. "','" .$this->table(). "','" .$field. "','" .$obj->id. "','" .$_SESSION["auth::id"]. "','" .$value. "','" .$obj->$field. "')";
            }
        }
        if (count($update) > 0) {
            $sql = "UPDATE `".$this->table()."` SET " .implode(",",$update). " WHERE `id`='" .$DBO->escape($obj->id). "';";
            $DBO->query($sql);
            
            $sql = "INSERT INTO `##_" .$this->space(). "_logging` (`type`,`table`,`column`,`entry_id`,`owner_id`,`old`,`new`) VALUES ".implode(",",$change). ";";
            $DBO->query($sql);
        }
        $this->set("id", $obj->id);
        return $obj;
	}

    public function updateFromObject($obj) {
        $obj = $this->_updateFromObject($obj);
        return $this->get("id");
    }
    
    public function cleanPropertyArray($arr) {
        $t = array();
        foreach ($arr as $el) {
            if ($el !== null) $t[] = $el;
        }
        return $t;
    }

    public function _rm($id) {
        global $DBO;
        if (is_array($id)) {
            $sql = "UPDATE `".$this->table()."` SET `deleted`='1' WHERE `id` IN ('" .implode("','",$id). "');";
            $DBO->query($sql);
            return true;
        } elseif (is_string($id) || is_numeric($id)) {
            $sql = "UPDATE `".$this->table()."` SET `deleted`='1' WHERE `id`='" .$id. "';";
            $DBO->query($sql);
            return true;
        }
        return false;
    }
    
    public function rm($id) {
        return $this->_rm($id);
    }
}
?>