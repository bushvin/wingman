<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * api_offer.php
 * 
 * api to offer objects
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class api_offer extends api {
    private $_list = array();

    public static function ls($data, $id, $token) {
        $r = new stdClass();
        $r->code = 0;

        if (! isset($data->sort)) {
            $data->sort= new stdClass();
            $data->sort->field = "date";
            $data->sort->direction = "asc";
        }

        $r->data = parent::_ls($data, "offer");
        
        return $r;
    }
    
    public static function set($data, $id, $token) {
        $r = new stdClass();
        $r->code = 0;
        $r->data = new stdClass();
        $r->data->id = parent::_set($data,"offer");
		return $r;
    }

    public static function get($data, $id, $token) {
        $r = new stdClass();
        $r->code = 0;
        $r->data = parent::_get($data,"offer");
        return $r;
    }
    

    public static function rm($data,$id,$token) {
        $r = new stdClass();
        $r->code = 1004; // record could not be removed
        if ( parent::_rm($data,"offer") ) $r->code = 0;
        return $r;
    }

}
?>