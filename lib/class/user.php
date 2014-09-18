<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * user.php
 * 
 * class to manage user objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class user extends base_object {
    private $_acl;

    
    public function __construct($id = -1) {
        parent::__construct($id);
        $this->_acl = new stdClass();
        $this->setTable("##_auth_user");
        $this->setObjectType("user");
        return $this;
    }
    
    public function details($force = false) {
        global $DBO;
		$sql = "SELECT `a`.`id`,`a`.`login`, `a`.`name`, `a`.`email`, `a`.`type`, 
		                `a`.`language_id`, `b`.`name` AS `language`, 
		                `a`.`locale_id`,`d`.`name` AS `locale`,
		                `a`.`token_timeout`, DATE_FORMAT(`a`.`last_login`,'%Y-%m-%d @ %k:%i:%s') AS `last_login`, `a`.`customer_id`,`c`.`name` `customer`, `c`.`space`
				FROM `" .$this->table(). "` AS `a`
				LEFT JOIN `##_language` `b` ON (`b`.`id`=`a`.`language_id` AND `b`.`deleted`=0)
				LEFT JOIN `##_customer` `c` ON (`a`.`customer_id`=`c`.`id`)
				LEFT JOIN `##_locale` `d` ON (`a`.`locale_id`=`d`.`id`)
				WHERE `a`.`deleted`=0 AND `a`.`id`=" .$this->get("id"). ";";
        $DBO->query($sql);
		$this->importFromDB($DBO->result('object'));

		$u = $DBO->result('object');
		
		$sql = "SELECT `auth_role_id` FROM `" .$this->table(). "_role`  WHERE `auth_user_id`=" .$this->get("id"). ";";
        $DBO->query($sql);
        $role_id = array();
        foreach ($DBO->result("objectlist") as $r) {
            $role_id[] = $r->auth_role_id;
        }
		$this->set("role_id",$role_id);
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `a`.`id`, `a`.`name`,`a`.`login`, `a`.`email`, `a`.`type`, `b`.`name` AS `language`, IF(STRCMP(`a`.`last_login`,'0000-00-00 00:00:00'),DATE_FORMAT(`a`.`last_login`,'%Y-%m-%d @ %k:%i:%s'),'" .NEVER. "') AS `last_login`
					FROM `".$this->table()."`AS `a` 
					LEFT JOIN `##_language` AS `b` ON (`b`.`id`=`a`.`language_id` AND `b`.`deleted`=0)";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>=0 AND `b`.`deleted`=0) AND (" .$filter. ")";

		return parent::_ls($sql,$filter,$sort,$limit);
    }
    
	public function acl($object_type, $force=false) {
		if ($force === false && isset($this->_acl->$object_type)) return $this->_acl->$object_type;
		$acl = new stdClass();
		$acl->read   = false;
		$acl->list   = false;
		$acl->create = false;
		$acl->delete = false;
		$acl->modify = false;
		    
		$valid_object_types = Array("entity", "entitytype", "inventory", "offer", "transaction", "user", "role", "currency","vat","language","crontab","customer");
		if (! in_array($object_type,$valid_object_types)) return $acl;

		global $DBO;
		$sql = "SELECT SUM(`d`.`read`) AS `read`, SUM(`d`.`list`) AS `list`, SUM(`d`.`create`) AS `create`, SUM(`d`.`delete`) AS `delete`, SUM(`d`.`modify`) AS `modify`
					FROM `" .$this->table(). "_role` AS `a`
					RIGHT JOIN `##_auth_role` AS `b` ON (`b`.`id`=`a`.`auth_role_id` AND `b`.`deleted`=0)
					LEFT JOIN `##_auth_acl` AS `c` ON (`c`.`auth_role_id`=`a`.`auth_role_id` AND `c`.`deleted`=0)
					LEFT JOIN `##_auth_ace` AS `d` ON (`d`.`id`=`c`.`auth_ace_id` AND `d`.`deleted`=0)
					WHERE `auth_user_id`=" .$this->get("id"). " AND `a`.`deleted`=0 AND `d`.`object`='" .$object_type. "';";
//		if ($object_type=="customer") {
//		    echo "$sql;";
//		}
		$DBO->query($sql);
		$result = $DBO->result("object");
		if ($result->read > 0)   $acl->read   = true;
		if ($result->list > 0)   $acl->list   = true;
		if ($result->create > 0) $acl->create = true;
		if ($result->delete > 0) $acl->delete = true;
		if ($result->modify > 0) $acl->modify = true;
		$this->_acl->$object_type = $acl;
		return $this->_acl->$object_type;
	}
	
	public function setPassword($password) {
        $salt = md5("wingman".time());
        $password = md5($password.$salt).$salt;
        $sql = "UPDATE `##_auth_user` SET `passphrase`='" .$password. "' WHERE `id`= '" .$DBO->escape($this->get("id")). "' AND `type`='internal' AND `deleted`=0;";
        $DBO->query($sql);
        return 0;
	}

    public function updateFromObject($obj) {
        global $DBO;
        if (isset($obj->role_id)) {
            $role_id = $obj->role_id;
            unset($obj->role_id);
        } else {
            $role_id = array();
        }
        $password = "";
        if ( isset($obj->password)) {
            $pass = $obj->password;
            unset($obj->password);
        }
        $obj = parent::_updateFromObject($obj);
        if ($password !== "") {
            $this->setPassword($password);
        }
        foreach ($role_id as $k=>$v) {
            if (is_null($v)) {
                unset($role_id[$k]);
            }
        }
        $sql = "SELECT `auth_role_id` FROM `##_auth_user_role` WHERE `auth_user_id`='" .$this->get("id"). "' AND `deleted`='0';";
        $DBO->query($sql);
        $aui = array();
        foreach($DBO->result("objectlist") as $r) {
            $aui[] = $r->auth_role_id;
        }
        $d = array_diff($aui,$role_id);
        if ( count($d) >0) {
            $sql = "UPDATE `##_auth_user_role` SET `deleted`='1' WHERE `auth_user_id`='" .$this->get("id"). "' AND `auth_role_id` IN ('" .implode("','",$d). "');";
            $DBO->query($sql);
        }
        $d = array_diff($role_id,$aui);
        if ( count($d) >0) {
            $change = array();
            foreach ($d as $did) {
                $change[] = "('" .$this->get("id"). "','${did}')";
            }
            
            $sql = "INSERT INTO `##_auth_user_role` (`auth_user_id`,`auth_role_id`) VALUES " . implode(",",$change). ";";
            $DBO->query($sql);
        }
        return $this->get("id");
    }	
}
?>