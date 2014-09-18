<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * api_transaction.php
 * 
 * api to transaction objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class api_transaction extends api {
    private $_list = array();

    public static function ls($data, $id, $token) {
        $r = new stdClass();
        $r->code = 0;

        if (! isset($data->sort)) {
            $data->sort= new stdClass();
            $data->sort->field = "uid";
            $data->sort->direction = "desc";
        }

        $r->data = parent::_ls($data, "transaction");
        
        return $r;
    }
    
    public static function set($data, $id, $token) {
        $r = new stdClass();
        $r->code = 0;
        $r->data = new stdClass();
        $r->data->id = parent::_set($data,"transaction");
		return $r;
    }

    public static function rm($data,$id,$token) {
        $r = new stdClass();
        $r->code = 1004; // record could not be removed
        if ( parent::_rm($data,"transaction") ) $r->code = 0;
        return $r;
    }

}
?>