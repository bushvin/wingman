<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * api_crontab.php
 * 
 * api to crontab objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class api_crontab extends api {
    private $_list = array();

    public function ls($data, $id, $token) {
        $r = new stdClass();
        $r->code = 0;

        if (! isset($data->sort)) {
            $data->sort= new stdClass();
            $data->sort->field = "crontab";
            $data->sort->direction = "asc";
        }

        $r->data = parent::_ls($data, "crontab");
        
        return $r;
    }
    
/*    public function ls($data, $id, $token) {
		$user = new user($_SESSION["auth::id"]);
		$acl = $user->acl("crontab");
		if ($acl->list !== true) {
		    return false;
		}
        $obj = new crontab();
        $r = new stdClass();
        $r->data = new stdClass();
        $r->code = 0;
        if (! isset($data->sort)) {
            $data->sort= new stdClass();
            $data->sort->field = "name";
            $data->sort->direction = "asc";
        }
        $sort = self::sortToSQL($data->sort);
        list($r->data->records,$r->data->fields) = $obj->ls(1,$sort);
        $r->data->fields_translated = self::translate($r->data->fields,"crontab");
        return $r;
    } */

    public function set($data, $id, $token) {
        $r = new stdClass();
        $r->data = new stdClass();
        $r->data->id = parent::_set($data,"crontab");
        $r->code = 0;
		return $r;
    }

    public function rm($data,$id,$token) {
        $r = new stdClass();
        $r->code = 1004; // record could not be removed
        if ( parent::_rm($data,"crontab") ) $r->code = 0;
        return $r;
    }

}
?>