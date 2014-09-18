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

class customer extends base_object {
    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_customer");
        $this->setObjectType("customer");
        return $this;
    }
    
    public function details($force = false) {
        global $DBO;
		$sql = "SELECT `a`.`id`, `a`.`name`,`a`.`space`,`a`.`transaction_uid_prefix`
				FROM `" .$this->table(). "` AS `a`
				WHERE `a`.`deleted`=0 AND `a`.`id`=" .$this->get("id"). ";";
        $DBO->query($sql);
		$this->importFromDB($DBO->result('object'));    
    }
    
    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `a`.`id`,`a`.`name`, `a`.`space`
					FROM `".$this->table()."` AS `a`";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>=0) AND (" .$filter. ")";

		return parent::_ls($sql,$filter,$sort,$limit);
    }
    
    public function updateFromObject($obj) {
        global $DBO;
        if ($obj->id == -1) {
            $sql = file_get_contents(FS_VAR.'new_customer.sql');
            $sql = str_replace("_SPACE_","_".$DBO->escape($obj->space)."_",$sql);
            $DBO->query($sql);
        }
        $obj = parent::_updateFromObject($obj);
        return $this->get("id");
    }
    
}
?>