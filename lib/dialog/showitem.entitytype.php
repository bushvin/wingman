<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.inventory.php
 * 
 * showitem.inventory dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

$o = new entitytype($data->data->id);
$o->details();
?>
<div class="title"><?php echo ucwords($o->get("id")==-1?ENTITYTYPE_NEW_ENTITYTYPE:ENTITYTYPE_EDIT_ENTITYTYPE); ?></div>
<div class="form">
    <div class="column"><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" />
<?php if ( $_entitytype_name === true ) { ?>
        <div class="row name">
            <div class="caption"><?php echo ucwords(ENTITYTYPE_NAME); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" id="name"/></div>
        </div>
<?php } ?>
<?php if ( $_entitytype_description === true ) { ?>
        <div class="row description">
            <div class="caption"><?php echo ucwords(ENTITYTYPE_DESCRIPTION); ?></div>
            <div class="value"><textarea id="description" class="textarea"><?php echo $o->get('description'); ?></textarea></div>
        </div>
<?php } ?>
<?php if ( $_entitytype_vat_number_required === true ) { ?>
        <div class="row vat_number_required">
            <div class="caption"><?php echo ucwords(ENTITYTYPE_VAT_NUMBER_REQUIRED); ?></div>
            <div class="value"><select class="select" id="vat_number_required"><option value="1"<?php echo ($o->get("vat_number_required")==1?" selected":""); ?>><?php echo ucwords(YES); ?></option><option value="0"<?php echo ($o->get("vat_number_required")==0?" selected":""); ?>><?php echo ucwords(NO); ?></option></select></div>
        </div>
<?php } ?>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
</div>
