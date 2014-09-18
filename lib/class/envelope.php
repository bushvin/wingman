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
 * envelope.php
 * 
 * envelope for api requests
 * 
 */
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class envelope {
	private $_id = -1;
	private $_token = "";
	private $_transaction = 0;
	private $_api = "";
	private $_data ;
	private $_format = "json";
	private $_tbl_space = "";

	public function envelope($data) {
	    global $AUTH,$DBO;
		$valid_formats = Array("json");
		$this->_data = new stdClass();

		if ( isset($data->id) )    $this->_id = (int)$data->id;
		if ( isset($data->token) ) $this->_token = dbo::escape($data->token);
		if ( isset($data->transaction) ) $this->_transaction = (int)$data->transaction;
		if ( isset($data->api) )   $this->_api = dbo::escape($data->api);
		if ( isset($data->data) )  $this->_data = $data->data;
		if ( isset($data->format) && in_array(strtolower($data->format), $valid_formats)) $this->_format = strtolower($data->format);

        if ($this->_id ==-1) {
            return false;
        }

		global $DBO;
		$sql = "SELECT `a`.`space` FROM `##_customer` `a`
		                LEFT JOIN `##_auth_user` `b` ON (`b`.`customer_id`=`a`.`id`)
		                WHERE `a`.`deleted`=0 AND `b`.`id`='" .$this->_id. "' LIMIT 0,1;";
	    $DBO->query($sql);
	    if ($DBO->result_count !=1) {
	        exit("Error: Table space could not be found!");
	    }
	    $this->_tbl_space = $DBO->result("single");
	}
	
	public function returnResult($format_override = null) {
		$return = new stdClass();
		$return->code = 1000;
        
        if ( $this->_id == -1 && $this->_api !== "auth_signin") {
            $_SESSION["auth::id"] = -1;
            $_SESSION["auth::token"] = "";
        	header('HTTP/1.1 401 Unauthorized');
            exit();
            return $return;
        }
        
		if ($this->_api == "") {
			exit("This shouldn't be happening! An error occurred!");
			echo $this->_id ."\n";
			echo $this->_token ."\n";
			echo $this->_api ."\n";
			echo $this->_format ."\n";
		}
		
		$temp = explode("::",preg_replace('/_/', '::', $this->_api, 1));
		if (count($temp) < 2 || !class_exists("api_" .$temp[0]) || !method_exists("api_" .$temp[0],$temp[1])) {
			$return->code = 1005;
			$return->reason = "API (" .$this->_api. ") doesn't exist (yet)";
			return obj::to($this->_format,$return);
		}
		
		$this->_data->tbl_space = $this->_tbl_space;
		$e = ("\$return = api_" .preg_replace('/_/', '::', $this->_api, 1). "(\$this->_data, \$this->_id, \$this->_token);");
		eval($e);
		return obj::to($this->_format,$return);
	}
	
}

?>
