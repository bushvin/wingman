<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * api.php
 * 
 * API core class
 * 
 * API error codes:
 *      0 -  999 : OK
 *             0 : General OK
 * 
 *   1000 - 1999 : Not OK
 *          1000 : General Not OK
 *          1001 : User or Password incorrect
 *          1002 : User Token not validated
 *          1003 : Old Password wrong
 *          1004 : Object could not be removed
 *          1005 : API doesn't exist
 */
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class  api {
    public static function sortToSQL($sort) {
        $sqlSort = "";
        if (isset($sort->field)) $sqlSort .= $sort->field;
        if (isset($sort->direction)) $sqlSort .= " " .$sort->direction;
        return $sqlSort;
    }
    
    public static function limitToSQL($limit) {
        if ( ! isset($limit->offset) || (int)$limit->offset != $limit->offset) return "";
        if ( ! isset($limit->count)  || (int)$limit->count != $limit->count) return "";

        return "LIMIT " .$limit->offset. "," .$limit->count;
    }
    
    public static function translate( $termlist, $prefix = "") {
        if (is_array($termlist)) {
            $new = array();
            foreach($termlist as $term) {
                eval ("\$new[] = " .strtoupper(($prefix==""?"":$prefix."_").$term). ";");
            }
            return $new;
        } else {
            return $termlist;
        }
    }

    public static function _ls($data, $type) {
		$user = new user($_SESSION["auth::id"]);
		$acl = $user->acl($type);
		if ($acl->list !== true) {
        	header('HTTP/1.1 403 Forbidden');
            exit();
		}
        $o = new $type();
        if (! isset($data->sort)) {
            $data->sort= new stdClass();
            $data->sort->field = "id";
            $data->sort->direction = "asc";
        }
        $sort = self::sortToSQL($data->sort);

        if (! isset($data->limit)) {
            $data->limit= new stdClass();
            $data->limit->offset = 0;
            $data->limit->count = 20;
        }
        $limit = self::limitToSQL($data->limit);
        $r = new stdClass();

        list($r->records,$r->fields) = $o->ls(1,$sort,$limit);
        $r->fields_translated = self::translate($r->fields,$type);
        $r->recordcount = $o->recordcount();
        $r->sort = $data->sort;
        $r->limit = $data->limit;
        return $r;
    }

    public static function _set($data, $type) {
		$user = new user($_SESSION["auth::id"]);
		$acl = $user->acl($type);
		if ($acl->modify !== true) {
        	header('HTTP/1.1 403 Forbidden');
            exit();
		    return false;
		}
        if ($data->id==-1 && $acl->create !== true) {
        	header('HTTP/1.1 403 Forbidden');
            exit();
            return false;
        }
        
        $o = new $type();
        return $o->updateFromObject($data);
    }
    
    public static function _get($data, $type) {
		$user = new user($_SESSION["auth::id"]);
		$acl = $user->acl($type);
		if ($acl->read !== true) {
        	header('HTTP/1.1 403 Forbidden');
            exit();
		    return false;
		}
		$o = new $type($data->id);
		$o->details();
		return $o->getObject();
    }
    
    public static function _rm($data, $type) {
		$user = new user($_SESSION["auth::id"]);
		$acl = $user->acl($type);
		if ($acl->delete !== true) {
        	header('HTTP/1.1 403 Forbidden');
            exit();
		    return false;
		}
		$o = new $type();
		return $o->rm($data->id);
    }
}
?>