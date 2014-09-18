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
 * api_auth.php
 * 
 * authentication API
 * 
 */
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class api_auth extends api {
    public static function signin($data) {
        global $AUTH;
		$return = new stdClass();
		$return->code = 1000;

		if ( ! isset($data->login) || ! isset($data->password) ) {
		    $return->code = 9999; //tbd
			return $return;
		}
        switch ($AUTH->signin($data->login,$data->password)) {
            case 0:
                $return->code = 0;
                $return->id = $AUTH->get('id');
                $return->token = $AUTH->get('token');
                break;
            case 1:
            case 2:
                $return->code = 1001; //User or password wrong
                break;
            default:
                $return->code = 1000; //something else is wrong
        }
        
        return $return;
        
    }
    
    public static function signout() {
        global $AUTH;
		$return = new stdClass();
		$return->code = 1000;
        if ( $AUTH->signout() ) {
        	$return->id = -1;
        	$return->token = "";
            $return->code = 0;
        }
        
        return $return;
    }
    
    public static function validateToken($data,$id,$token) {
        global $AUTH;
		$return = new stdClass();
		$return->id = -1;
		$return->token = "";
		$return->code = 1000;

        list($nId,$nToken) = $AUTH->verifyToken($id, $token);
        if ($id == $nId && $token == $nToken) {
            $return->code = 0;
    		$return->id = $id;
    		$return->token = $token;
        } else {
            $return->code = 1002; // token not validated
        }
        
        return $return;
    }
    
    public static function passwd($data,$id,$token) {
        global $AUTH;
		$return = new stdClass();
		$return->code = 1000;
        switch ($AUTH->passwd($data->oldpwd,$data->newpwd)) {
            case 0:
                $return->code = 0;
                break;
            case 1:
                $return->code = 1003; // old password wrong
                break;
            default:
                $return->code = 1000; //error
                break;
        }
        return $return;
    }
}
?>