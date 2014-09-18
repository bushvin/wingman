<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * view.php
 * 
 * class to manage user view
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
class view {
    private $_user;
    private $_access = false;
    
    public function __construct($user_id) {
        $this->_user = new user($user_id);
    }
    
    public function access($force = false) {
        if ( $force === false && $this->_access !== false) return $this->_access;
        $this->_access = array();
//        $views = Array("entity", "inventory", "offer", "transaction", "currency","vat","language","crontab", "user", "role");
        $views = Array("entity", "entitytype", "inventory", "offer", "transaction", "currency","vat","language", "user", "role", "customer");
		foreach ($views as $v) {
			$acl = $this->_user->acl($v);
			if ($acl->list && $acl->read) $this->_access[] = $v;
		}
        return $this->_access;
    }
    
}
?>