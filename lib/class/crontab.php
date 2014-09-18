<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * crontab.php
 * 
 * class to manage crontab objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class crontab extends base_object {

    public function __construct($id = -1) {
        parent::__construct($id);
        $this->setTable("##_SPACE_crontab");
        $this->setObjectType("crontab");
        return $this;
    }

    public function ls($filter = 1, $sort = "", $limit = "") {
		$sql = "SELECT `a`.`id`,`a`.`name`, `a`.`export_to`, `a`.`recurrence`, `b`.`timestamp` AS `last_run`, IF(`b`.`exit_status`=0,'".RES_SUCCESS."','" .RES_FAILED. "') AS `exit_status`
					FROM `".$this->table()."` AS `a` 
					LEFT JOIN (SELECT * FROM `".$this->table()."_log` ORDER BY `timestamp` DESC LIMIT 0,1) AS `b` ON (`b`.`crontab_id`=`a`.`id`)";
		$filter = "(`a`.`deleted`=0 AND `a`.`id`>=0 AND `b`.`deleted`=0) AND (" .$filter. ")";
		return parent::_ls($sql,$filter,$sort,$limit);
    }
    
    
}
?>