<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * add_entity_address.php
 * 
 * add_entity_address dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

include_once(FS_DIALOG . "showitem.access.default.php" );
include_once(FS_DIALOG . "showitem.access." .$_SESSION["space"]. ".php" );

if ( !isset($address)) {
    $address = new stdClass();
    $address->id = -1;
    $address->address_type = "";
    $address->street = "";
    $address->code = "";
    $address->city = "";
    $address->province = "";
    $address->country = "";
    $address->invoicing = 0;
    $address->attn = "";
}
if ( ! isset($count)) {
    $count = 0;
//    print_r($data->data);
    if (isset($data->data->count)) {
        $count = $data->data->count;
    }
}
?>
            <div class="row type">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_TYPE); ?><input type="hidden" id="address[<?php echo $count ?>].id" value="<?php echo $address->id ?>" /></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->address_type ?>" id="address[<?php echo $count ?>].address_type"/><div class="delete" onclick="app.entity.address.del($(this).parent().parent().parent());"></div></div>
            </div>
            <div class="row attn">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_ATTN); ?></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->attn; ?>" id="address[<?php echo $count ?>].attn"/></div>
            </div>
            <div class="row street">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_STREET); ?></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->street; ?>" id="address[<?php echo $count ?>].street"/></div>
            </div>
            <div class="row code">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_CODE); ?></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->code; ?>" id="address[<?php echo $count ?>].code"/></div>
            </div>
            <div class="row city">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_CITY); ?></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->city; ?>" id="address[<?php echo $count ?>].city"/></div>
            </div>
            <div class="row province">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_PROVINCE); ?></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->province; ?>" id="address[<?php echo $count ?>].province"/></div>
            </div>
            <div class="row country">
                <div class="caption"><?php echo ucwords(ENTITY_ADDRESS_COUNTRY); ?></div>
                <div class="value"><input class="input-text" type="text" value="<?php echo $address->country; ?>" id="address[<?php echo $count ?>].country"/></div>
            </div>
