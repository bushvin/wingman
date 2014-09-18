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
 * dbo.mysql.php
 * 
 * dbo class to manage MySQL/MariaSQL database connections
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled

class dbo {
	private $_dbo = null;
	private $_query = "";
	private $_record_set = null;
	
	private $_host = "";
	private $_user = "";
	private $_db = "";
	private $_port = 3306;
	
	private $_prefix = "";
	
	public $result_count = -1;
	public $fields = Array();
	public $tables = Array();
	
	public function __construct($host, $user, $pass, $db, $tbl_prefix = "", $port = 3306, $names = "utf8", $debug = false ) {
		$this->_host = $host;
		$this->_user = $user;
		$this->_db = $db;
		$this->_prefix = $tbl_prefix ."_";
		$this->_port = $port;

	    logger::debug("dbo::__construct: Connecting to $db on $host:$port;");
		$this->_dbo = new mysqli($host, $user, $pass, $db, $port);

		if ( $this->_dbo->errno != 0 ) {
			logger::error("dbo::__construct: An error occured connecting to database '$db' on host '$host:$port' using username '$user'.");
			logger::error("The error is: '" .$this->_dbo->error. "'");
			exit("Error: Can't connect to the to the database.");
		}
		
	    logger::debug("dbo::__construct: query: SET NAMES " .$names. ";");
		$this->query("SET NAMES " .$names. ";");

		if ( $this->_dbo->errno != 0 ) {
			logger::error("dbo::__construct: An error occured setting the collation to " .$names. ".");
			logger::error("The error is: '" .$this->_dbo->error. "'");
			exit("Error: Can't set the requested collation.");
		}
		
		return true;
	}
	
	public function setPrefix( $prefix ) {
		$this->_prefix = $prefix ."_";
		logger::debug("dbo::setPrefix: changed table prefix to '$prefix'");
		return $prefix;
	}
	
	public function query( $query = null, $debug = false ) {

		if ( $this->_dbo == null ) {
			logger::error("dbo::query: A connection needs to be made with the db server first!");
			exit("Error: A connection needs to be made with the db server first!");
		}
		
		if ( ($query === null) && ($this->_query != "") ) {
			$query = $this->_query;
		} elseif ($query === null) {
			if ($this->_debug || $debug) {
				logger::warning("dbo::query: No SQL query was provided to execute.");
			}
			return false;
		}
		
		$this->_query = $query;
		
		$query = str_replace("##_",$this->_prefix,$query);
		
		logger::debug("dbo::query: query: $query");

		$this->_record_set = $this->_dbo->query($query);

		if ( $this->_dbo->errno != 0 ) {
			logger::error("dbo::query: An error occured executing the query.");
			logger::error("The error is: '" .$this->_dbo->error. "'");
			exit("Error: The query failed. $query");
		}

		$this->result_count = $this->_dbo->affected_rows;

		if ( $this->_dbo->errno != 0 ) {
			logger::error("dbo::query: An error occured calculating the number of results.");
			logger::error("The error is: '" .$this->_dbo->error. "'");
			exit("Error: Couldn't count the number of records.");
		}

		$this->fields = Array();
		if (strtoupper(substr($query,0,6)) == "SELECT") {
			$temp  = $this->_record_set->fetch_fields();
			foreach ($temp as $t) {
				$this->fields[] = $t->name;
			}
		
			if ( $this->_dbo->errno != 0 ) {
				logger::error("dbo::query: An error occured fetching the fields of the result.");
			    logger::error("The error is: '" .$this->_dbo->error. "'");
				exit("Error: Couldn't fetch the fields.");
			}
		}
		return $this->_record_set;
	}
	
	public function result( $type, $var = null ) {
		$this->_record_set->data_seek(0);
		switch ( strtolower($type) ) {
			case "single":
			case "s":
				$index = $this->fields[0];
				if ( $var !== null ) {
					$i = array_search($var,$this->fields);
					if ( $i !== false ) $index = $var;
				}
				$return = $this->_record_set->fetch_assoc(); 
				return $return[$index];
				break;
			case "array":
			case "a":
				$temp = Array();
				while ($a = $this->_record_set->fetch_assoc() ) {
					$temp[] = $a;
				}
				if ( $var !== null ) {
					$index = $this->fields[0];
					$value = $this->fields[1];
					if (isset($var->index)) {
						$t = array_search($var->index, $this->fields);
						if ( $t !== false ) $index = $var->index;
					}
					if (isset($var->value)) {
						$t = array_search($var->value, $this->fields);
						if ( $t !== false ) $value = $var->value;
					}
					$ttemp = Array();
					foreach($temp as $row) {
						$ttemp[$row[$index]] = $row[$value];
					}
					$temp = $ttemp;
				}
				return $temp;
				break;
			case "object":
			case "o":
				return $this->_record_set->fetch_object();
				break;
			case "objectlist":
			case "l":
				$return = Array();
				while ( $o = $this->_record_set->fetch_object() ) {
					$return[] = $o;
				}
				return $return;
				break;
		}
				
	}
	
	public function nextId( $table = null,$debug = false ) {
		if ( $this->_dbo == null ) {
			logger::error("dbo::nextId: A connection needs to be made with the db server first!");
			exit("Error: A connection needs to be made with the db server first!");
		}

		if ( $table == null ) {
			logger::error("dbo::nextId: was not supplied with the required table name.");
			exit("Error: No table name specified.");
		}
        $sql = "SELECT IFNULL((MAX(`id`)+1),1) AS `max` FROM `$table`;";
		$this->query($sql);
		if ((int)$this->result("single","max") > 0) {
		    return (int)$this->result("single","max");
		} else {
		    return 1;
		}
	}

	public static function escape( $value = "" ) {
		if (is_array($value)) return array_map(__METHOD__,$value);
		if (!empty($value) && is_string($value)) {
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $value);
		}
		return $value;
	}

}
?>
