<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * vat.php
 * 
 * class to manage vat objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class vat extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_vat");
        $this->setObjectType("vat");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `a`.`id`,`a`.`name`, `a`.`type`, `a`.`amount`
					FROM `".$this->table()."` AS `a`";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>=0) AND (" .$filter. ")";
		return parent::_ls($sql,$filter,$sort,$limit);
    }

    public function details() {
        global $DBO;
		$sql = "SELECT *
					FROM `".$this->table()."`
					WHERE `id`=" .$this->get("id"). " AND `deleted`=0";
		$DBO->query($sql);
		$this->importFromDB($DBO->result('object'));
    }
    
    public function calculate( $amount ) {
        $this->details();
        switch ($this->get("type")) {
            case "relative":
                return $amount/100*$this->get("amount");
                break;
            case "fixed":
                return $this->get("amount");
                break;
            default:
                return 0;
        }
    }
    
}
?>