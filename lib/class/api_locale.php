<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * api_locale.php
 * 
 * api to locale
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

class api_locale extends api {
    public static function get ($data, $id, $token) {
        if (!isset($data->id)) {
            $data->id = $_SESSION["auth::id"];
        }
        global $DBO;
        $r = new stdClass();
        $r->code = 0;
        $sql = "SELECT `b`.`decimal_separator` `decimal`,`b`.`thousand_separator` `thousand` FROM `##_auth_user` `a`
                        LEFT JOIN `##_locale` `b` ON (`a`.`locale_id`=`b`.`id`)
                        WHERE `a`.`id`='" .$data->id. "';";
        $DBO->query($sql);
        $r->separator = $DBO->result('object');
        
        
        return $r;
    }
}
?>