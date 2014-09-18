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

$o = new inventory($data->data->id);
$o->details();

$sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_inventory_item_type` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$type = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_vat` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$vat = $DBO->result("objectlist");

$sql = "SELECT `id`,CONCAT(`name`,' (',`sign`,')') AS `name` FROM `##_" .$_SESSION['space']. "_currency` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$curr = $DBO->result("objectlist");

?>
<div class="title"><?php echo ucwords($o->get("id")==-1?INVENTORY_NEW_INVENTORY:INVENTORY_EDIT_INVENTORY); ?></div>
<div class="form">
    <div class="column"><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" />
<?php if ( $_inventory_name === true ) { ?>
        <div class="row name">
            <div class="caption"><?php echo ucwords(INVENTORY_NAME); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" id="name"/></div>
        </div>
<?php } ?>
<?php if ( $_inventory_description === true ) { ?>
        <div class="row description">
            <div class="caption"><?php echo ucwords(INVENTORY_DESCRIPTION); ?></div>
            <div class="value"><textarea id="description" class="textarea"><?php echo $o->get('description'); ?></textarea></div>
        </div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
        <div class="row field0">
            <div class="caption"><?php echo ucwords(INVENTORY_FIELD0); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('field0'); ?>" id="field0"/></div>
        </div>
<?php } ?>
<?php if ( $_inventory_sku === true ) { ?>
        <div class="row sku">
            <div class="caption"><?php echo ucwords(INVENTORY_SKU); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('sku'); ?>" placeholder="<?php echo ucwords(INVENTORY_SKU); ?>" id="sku"/></div>
        </div>
<?php } ?>
<?php if ( $_inventory_type === true ) { ?>
        <div class="row type">
            <div class="caption"><?php echo ucwords(INVENTORY_TYPE); ?></div>
            <div class="value"><select class="select" id="inventory_type_id"><?php 
            foreach($type as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("inventory_type_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
<?php if ( $_inventory_reference_price === true ) { ?>
        <div class="row price">
            <div class="caption"><?php echo ucwords(INVENTORY_REFERENCE_PRICE); ?></div>
            <div class="value">
                <div class="input-number">
                    <input type="number" maxlength="7" min="0" class="whole" value="<?php echo intval($o->get('indicative_price')); ?>" onkeyup="app.event.input.decimal.change($(this).parent())" />
                    <span class="separator"><?php echo APP_DECIMAL_SEPARATOR; ?></span>
                    <input type="number" maxlength="3" min="0" class="fraction" value="<?php echo intval(($o->get('indicative_price')-intval($o->get('indicative_price')))*1000); ?>" onkeyup="app.event.input.decimal.change($(this).parent())" />
                    <input class="raw" type="hidden" value="<?php echo $o->get('indicative_price'); ?>" placeholder="<?php echo ucwords(INVENTORY_PRICE); ?>" id="indicative_price"/>
                </div>
            </div>
        </div>
<?php } ?>
<?php if ( $_inventory_reference_currency === true ) { ?>
        <div class="row currency">
            <div class="caption"><?php echo ucwords(INVENTORY_REFERENCE_CURRENCY); ?></div>
            <div class="value"><select class="select" id="indicative_currency_id"><?php 
            foreach($curr as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("indicative_currency_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
<?php if ( $_inventory_reference_vat === true ) { ?>
        <div class="row vat">
            <div class="caption"><?php echo ucwords(INVENTORY_REFERENCE_VAT); ?></div>
            <div class="value"><select class="select" id="indicative_vat_id"><?php 
            foreach($vat as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("indicative_vat_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
</div>
