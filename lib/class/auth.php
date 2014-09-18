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
 * auth.php
 * 
 * authentication class
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//


class auth {
    private $_id = -1;
    private $_token = "";
	private $_type = "internal";
	private $_language_id = -1;
	private $_token_timeout = 0;
	private $_name = "";
	private $_email = "";
	private $_customer_id = -1;
    
    private function reset() {
        $this->_id = -1;
        $this->_token = "";
    	$this->_type = "internal";
        $this->_language_id = -1;
    	$this->_token_timeout = 0;
    	$this->_name = "";
    	$this->_email = "";
    	$this->_customer_id = -1;
    }
    
    public function verifyToken($id, $token) {
        global $DBO;
        $sql = "SELECT id FROM `wmn_auth_token` 
                      WHERE `auth_user_id`='" .$DBO->escape($id). "' AND `token` = '" .$DBO->escape($token). "' AND `deleted`=0 
                      AND (`ip_address`='" .$_SERVER["REMOTE_ADDR"]. "' OR `ip_address`='-1') 
                      AND (DATE_ADD(`last_used`, INTERVAL `timeout` SECOND) > NOW() OR `timeout`='-1') 
                      LIMIT 0,1;";
        $DBO->query($sql);
        if ($DBO->result_count == 1) {
            $this->_id = $id;
            $this->_token = $token;
            $this->updateToken();
        } else {
            $this->reset();
        }
        return array($this->_id,$this->_token);
    }
    
    public function signin($login,$password) {
        global $DBO;
        $sql = "SELECT * FROM `##_auth_user` WHERE `login`= '" .$DBO->escape($login). "' AND `type`='internal' AND `deleted`=0;";
        $DBO->query($sql);
        if ( $DBO->result_count == 1 ) {
			$temp = $DBO->result("object");
			if ( md5($password . substr($temp->passphrase,32)).substr($temp->passphrase,32) === $temp->passphrase ) {
				$this->_id = (int)$temp->id;
				$this->_type = $temp->type;
				$this->_language_id = $temp->language_id;
				$this->_token_timeout = $temp->token_timeout;
				$this->_name = $temp->name;
				$this->_email = $temp->email;
				$this->_customer_id = $temp->customer_id;
				$this->mkToken();
				$_SESSION["auth::id"] = $this->_id;
				$_SESSION["auth::token"] = $this->_token;
				
				// and add some more stuff...
			} else {
			    return 1; // wrong password
			}
		} else {
		    return 2; // user doesn't exist
		}
		
		return 0; // yay!
        
    }
    
    public function signout() {
        global $DBO;
        $sql = "DELETE FROM `##_auth_token` WHERE `auth_user_id`='" .$this->_id. "' AND `token`='" .$this->_token. "' AND `ip_address`='" .$_SERVER["REMOTE_ADDR"]. "';";
        $DBO->query($sql);
        
        return true;
    }
    
    private function mkToken() {
        global $DBO;
        $this->_token = "";
		$tokenchars = "0123456789abcdef";
		for ($i=0;$i<64;$i++) {
			$this->_token.= substr($tokenchars, rand(0,strlen($tokenchars)),1);
		}
		$sql = "DELETE FROM `##_auth_token` WHERE `auth_user_id`=" .$this->_id. " AND `ip_address`='" .$_SERVER["REMOTE_ADDR"]. "';";
		$DBO->query($sql);
		$token_id = $DBO->nextId("##_auth_token");
		
		$sql = "INSERT INTO `##_auth_token` (`id`,`auth_user_id`, `token`, `ip_address`, `issued`,`timeout`) VALUES (" .$token_id. ", " .(int)$this->_id. ", '" .$this->_token. "','" .$_SERVER["REMOTE_ADDR"]. "','" .date("Y-m-d H:i:s",time()). "', $this->_token_timeout);";
		$DBO->query($sql);
		
		$sql = "UPDATE `##_auth_user` AS `a`,`##_auth_token` AS `b` SET `a`.`last_login`=`b`.`issued` WHERE `a`.`id`=" .$this->_id. " AND `b`.`id`=" .$token_id. ";";
		$DBO->query($sql);

		return $this->_token;
    }

    public function updateToken() {
        global $DBO;
        $DBO->query("UPDATE `##_auth_token` SET `last_used` = '" .date("Y-m-d H:i:s",time()). "' WHERE `auth_user_id` = " .$this->_id. " AND `token` = '" .$this->_token. "' AND `deleted`=0 AND `disabled`=0;");
        return true;
    }
    
    public function get($key) {
        switch ($key) {
            case "id":
                return $this->_id;
                break;
            case "token":
                return $this->_token;
                break;
        }
        return false;
    }
    
    public function passwd($oldpwd, $newpwd) {
        global $DBO;
        $sql = "SELECT `passphrase` FROM `##_auth_user` WHERE `id`= '" .$DBO->escape($this->_id). "' AND `type`='internal' AND `deleted`=0;";
        $DBO->query($sql);
        if ($DBO->result_count != 1) return 2;
        $t = $DBO->result("single");
        if ( md5($oldpwd . substr($t,32)).substr($t,32) === $t) {
            $salt = md5("wingman".time());
            $newpwd = md5($newpwd.$salt).$salt;
            $sql = "UPDATE `##_auth_user` SET `passphrase`='" .$newpwd. "' WHERE `id`= '" .$DBO->escape($this->_id). "' AND `type`='internal' AND `deleted`=0;";
            $DBO->query($sql);
            return 0;
        }
        return 1;
    }
}
?>