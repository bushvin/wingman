<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * entity_type.php
 * 
 * class to manage entity_type objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class entitytype extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_entity_type");
        $this->setObjectType("entitytype");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `id`,`name`,`description` FROM `".$this->table()."`";
		$filter = "(`deleted`=0 AND `id`>0) AND (" .$filter. ")";
		list($list,$fields) = parent::_ls($sql,$filter,$sort,$limit);
		$list = $this->ls_numeric($list,array("uid"));
		return array($list,$fields);
    }
    
    public function details() {
        global $DBO;
        
		$sql = "SELECT `id`,`name`,`description`,`vat_number_required` FROM `".$this->table()."` WHERE `deleted`=0 AND `id`=" .$this->get("id");
		$DBO->query($sql);
		$u = $DBO->result('object');
		$this->importFromDB($DBO->result('object'));
    }
  

}
?>