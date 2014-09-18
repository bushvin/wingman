<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * role.php
 * 
 * class to manage role objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class role extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_auth_role");
        $this->setObjectType("role");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `a`.`id`,`a`.`name`, `a`.`description`
					FROM `".$this->table()."` AS `a`";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>=0) AND (" .$filter. ")";

		return parent::_ls($sql,$filter,$sort,$limit);
    }
    
    public function details() {
        global $DBO;
		$sql = "SELECT `a`.`id`,`a`.`disabled`,`a`.`name`, `a`.`description`
				FROM `".$this->table()."` AS `a`
				WHERE `a`.`deleted`=0 AND `a`.`id`=" .$this->get("id"). ";";
		$DBO->query($sql);
		$this->importFromDB($DBO->result('object'));
        
        $sql = "SELECT `auth_user_id` FROM `##_auth_user_role` WHERE `auth_role_id`='" .$this->get("id"). "' AND `deleted`='0';";
        $DBO->query($sql);
        $user_id = array();
        if ($DBO->result_count >0) {
            foreach ($DBO->result("objectlist") as $r) {
                $user_id[] = $r->auth_user_id;
            }
        }
        $this->set("user_id", $user_id);

        $sql = "SELECT `auth_ace_id` FROM `##_auth_acl` WHERE `auth_role_id`='" .$this->get("id"). "' AND `deleted`=0;";
        $DBO->query($sql);
        $ace_id = array();
        if ($DBO->result_count >0) {
            foreach ($DBO->result("objectlist") as $r) {
                $ace_id[] = $r->auth_ace_id;
            }
        }
        $this->set("ace_id", $ace_id);

    }

    public function updateFromObject($obj) {
        global $DBO;
        if (isset($obj->user_id)) {
            $user_id = $obj->user_id;
            unset($obj->user_id);
        } else {
            $user_id = array();
        }

        if (isset($obj->ace_id)) {
            $ace_id = $obj->ace_id;
            unset($obj->ace_id);
        } else {
            $ace_id = array();
        }

        $obj = parent::_updateFromObject($obj);
        foreach ($user_id as $k=>$v) {
            if (is_null($v)) {
                unset($user_id[$k]);
            }
        }

        $user_id = $this->cleanPropertyArray($user_id);
        $sql = "SELECT `auth_user_id` FROM `##_auth_user_role` WHERE `auth_role_id`='" .$this->get("id"). "' AND `deleted`='0';";
        $DBO->query($sql);
        $aur = array();
        foreach ($DBO->result("objectlist") as $v) {
            $aur[] = $v->auth_user_id;
        }
        $d = array_diff($aur,$user_id);
        if ( count($d) >0) {
            $sql = "UPDATE `##_auth_user_role` SET `deleted`='1' WHERE `auth_role_id`='" .$this->get("id"). "' AND `auth_user_id` IN ('" .implode("','",$d). "');";
            echo $sql;
            $DBO->query($sql);
        }
        $d = array_diff($user_id,$aur);
        if ( count($d) >0) {
            $change = array();
            foreach ($d as $did) {
                $change[] = "('" .$this->get("id"). "','${did}')";
            }
            
            $sql = "INSERT INTO `##_auth_user_role` (`auth_role_id`,`auth_user_id`) VALUES " . implode(",",$change). ";";
            echo $sql;
            $DBO->query($sql);
        }

        $ace_id = $this->cleanPropertyArray($ace_id);
        $sql = "SELECT `auth_ace_id` FROM `##_auth_acl` WHERE `auth_role_id`='" .$this->get("id"). "' AND `deleted`='0';";
        $DBO->query($sql);
        $ac = array();
        foreach ($DBO->result("objectlist") as $v) {
            $ac[] = $v->auth_ace_id;
        }

        $d = array_diff($ac,$ace_id);
        if ( count($d) >0) {
            $sql = "UPDATE `##_auth_acl` SET `deleted`='1' WHERE `auth_role_id`='" .$this->get("id"). "' AND `auth_ace_id` IN ('" .implode("','",$d). "');";
            $DBO->query($sql);
        }
        $d = array_diff($ace_id,$ac);
        if ( count($d) >0) {
            $change = array();
            foreach ($d as $did) {
                $change[] = "('" .$this->get("id"). "','${did}')";
            }
            
            $sql = "INSERT INTO `##_auth_acl` (`auth_role_id`,`auth_ace_id`) VALUES " . implode(",",$change). ";";
            $DBO->query($sql);
        }
        
        return $this->get("id");
    }    
}
?>