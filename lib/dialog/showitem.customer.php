<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.customer.php
 * 
 * showitem.customer dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

$o = new customer($data->data->id);
$o->details();
?>
<div class="title"><?php echo ucwords($o->get("id")==-1?CUSTOMER_NEW_CUSTOMER:CUSTOMER_EDIT_NEW_CUSTOMER); ?></div>
<div class="form">
    <div class="column"><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" />
<?php if ( $_customer_name === true ) { ?>
        <div class="row name">
            <div class="caption"><?php echo ucwords(CUSTOMER_NAME); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" id="name"/></div>
        </div>
<?php } ?>
<?php if ( $_customer_space === true ) { ?>
        <div class="row space">
            <div class="caption"><?php echo ucwords(CUSTOMER_SPACE); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('space'); ?>" id="space"/></div>
        </div>
<?php } ?>
<?php if ( $_customer_transaction_uid_prefix === true ) { ?>
        <div class="row transaction_uid_prefix">
            <div class="caption"><?php echo ucwords(CUSTOMER_TRANSACTION_UID_PREFIX); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('transaction_uid_prefix'); ?>" id="transaction_uid_prefix"/></div>
        </div>
<?php } ?>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
</div>
