<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.offer.php
 * 
 * showitem.offer dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

$o = new offer($data->data->id);
$o->details();

$sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_entity` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$cust = $DBO->result("objectlist");
if ($o->get("entity_id") == null) {
    $o->set("entity_id",$cust[0]->id);
}
$sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_vat` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$vat = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_currency` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$curr = $DBO->result("objectlist");

$sql = "SELECT `a`.`id`,CONCAT(`a`.`name`,' - ',`a`.`sku`,' - ',`b`.`sign`,' ',`a`.`indicative_price`) `name` 
                FROM `##_" .$_SESSION['space']. "_inventory_item` `a` 
                LEFT JOIN `##_" .$_SESSION['space']. "_currency` `b` ON (`a`.`indicative_currency_id` = `b`.`id`)
                WHERE `a`.`deleted`=0 AND `a`.`id`>0 ORDER BY `name`;";
$DBO->query($sql);
$items = $DBO->result("objectlist");

$sql = "SELECT `id`,CONCAT(`address_type`,' (', `street`,', ',`code`,' ',`city`,')') `address_type` FROM `##_" .$_SESSION['space']. "_entity_address` WHERE `deleted`=0 AND `id`>0 AND `entity_id`='" .$o->get("entity_id"). "' ORDER BY `address_type`;";
$DBO->query($sql);
$eaddr = $DBO->result("objectlist");


?><div class="title"><?php echo ucwords($o->get("id")==-1?OFFER_NEW_OFFER:OFFER_EDIT_OFFER); ?></div>
<div class="form">
    <div class="column"><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" />
<?php if ( $_offer_customer === true ) { ?>
        <div class="row customer">
            <div class="caption"><?php echo ucwords(OFFER_CUSTOMER); ?></div>
            <div class="value"><?php 
            if ($data->data->id == -1) {
            ?><select class="select" id="entity_id" onchange="app.offer.customerselect($(this).val());"><?php 
            foreach($cust as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("entity_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select><?php 
            } else {
                echo $o->get("customer");
            }
            ?></div>
        </div>
<?php } ?>
<?php if ( $_offer_reference_prefix === true ) { ?>
        <div class="row reference_prefix">
            <div class="caption"><?php echo ucwords(OFFER_REFERENCE_PREFIX); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('reference_prefix'); ?>" id="reference_prefix"/></div>
        </div>
<?php } ?>
<?php if ( $_offer_reference === true ) { ?>
        <div class="row reference">
            <div class="caption"><?php echo ucwords(OFFER_REFERENCE); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('reference'); ?>" id="reference"/></div>
        </div>
<?php } ?>
<?php if ( $_offer_description === true ) { ?>
        <div class="row description">
            <div class="caption"><?php echo ucwords(OFFER_DESCRIPTION); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('description'); ?>"  id="description"/></div>
        </div>
<?php } ?>
<?php if ( $_offer_remark === true ) { ?>
        <div class="row remark">
            <div class="caption"><?php echo ucwords(OFFER_REMARK); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('remark'); ?>"  id="remark"/></div>
        </div>
<?php } ?>
<?php if ( $_offer_date === true ) { ?>
        <div class="row date">
            <div class="caption"><?php echo ucwords(OFFER_DATE); ?></div>
            <div class="value"><input class="input-text datepicker" type="text" value="<?php echo $o->get('date'); ?>" id="date"/></div>
        </div>
<?php } ?>
<?php if ( $_offer_invoice_address === true || $_offer_delivery_address === true || $_offer_contact === true) { ?>
        <hr />
<?php } ?>
<?php if ( $_offer_invoice_address === true ) { ?>
        <div class="row invoice_address">
            <div class="caption"><?php echo ucwords(OFFER_INVOICE_ADDRESS); ?></div>
            <div class="value"><select class="select" id="invoice_address_id"><?php 
            foreach($eaddr as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("invoice_address_id")?" selected":""). ">" .$e->address_type. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
<?php if ( $_offer_delivery_address === true ) { ?>
        <div class="row delivery_address">
            <div class="caption"><?php echo ucwords(OFFER_DELIVERY_ADDRESS); ?></div>
            <div class="value"><select class="select" id="delivery_address_id"><?php 
            foreach($eaddr as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("delivery_address_id")?" selected":""). ">" .$e->address_type. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
<?php if ( $_offer_contact === true ) { ?>
        <div class="row contact">
            <div class="caption"><?php echo ucwords(OFFER_CONTACT); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('contact'); ?>"  id="contact"/></div>
        </div>
<?php } ?>
<?php if ( $_offer_currency === true || $_offer_purchased === true) { ?>
        <hr />
<?php } ?>
<?php if ( $_offer_currency === true ) { ?>
        <div class="row currency">
            <div class="caption"><?php echo ucwords(OFFER_CURRENCY); ?></div>
            <div class="value"><select class="select" id="currency_id"><?php 
            foreach($curr as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("currency_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
<?php if ( $_offer_purchased === true ) { ?>
        <div class="row purchased">
            <div class="caption"><?php echo ucwords(OFFER_PURCHASED); ?></div>
            <div class="value"><select class="select" id="purchased"><option value="1"<?php echo ($o->get("purchased")==1?" selected":""); ?>><?php echo ucwords(YES); ?></option><option value="0"<?php echo ($o->get("purchased")==0?" selected":""); ?>><?php echo ucwords(NO); ?></option></select></div>
        </div>
<?php } ?>
<?php if ( $_offer_payment === true ) { ?>
        <div class="row payment">
            <div class="caption"><?php echo ucwords(OFFER_PAYMENT); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('payment'); ?>"  id="payment"/></div>
        </div>
<?php } ?>
    </div>
    <div class="column itemlist">
        <div class="item header">
<?php if ( $_inventory_sku === true ) { ?>
            <div class="cell sku"><?php echo ucwords(OFFER_ITEM_SKU); ?></div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"><?php echo ucwords(OFFER_ITEM_FIELD0); ?></div>
<?php } ?>
<?php if ( $_inventory_name === true ) { ?>
            <div class="cell name"><?php echo ucwords(OFFER_ITEM_NAME); ?></div>
<?php } ?>
            <div class="cell volume"><?php echo ucwords(OFFER_ITEM_VOLUME); ?></div>
<?php if ( $_inventory_reference_price === true ) { ?>
            <div class="cell price"><?php echo ucwords(OFFER_ITEM_PRICE); ?></div>
<?php } ?>
<?php if ( $_inventory_reference_vat === true ) { ?>
            <div class="cell vat"><?php echo ucwords(OFFER_ITEM_VAT); ?></div>
<?php } ?>
            <div class="cell total"><?php echo ucwords(OFFER_ITEM_TOTAL); ?></div>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"></div>
<?php } ?>
<?php if ( $_inventory_description === true ) { ?>
            <div class="cell description"><?php echo ucwords(OFFER_ITEM_DESCRIPTION); ?></div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"></div>
<?php } ?>
            <div class="cell inventory"><?php echo ucwords(OFFER_ITEM_INVENTORY); ?></div>
            <div class="cell action"></div>
        </div>
        <div class="list">
<?php
        $count = 0;
        $total = 0;
        foreach ($o->get("item") as $item) {
            $total += $item->volume*$item->price;
            ?>
        <div class="item id<?php echo $count ?>">
<?php        include(FS_DIALOG."add_offer_item.php"); ?>
        </div>
<?php
        $count++;
        }
?>
        </div>
        <div class="totals">
            <div class="cell total">
                <div class="listtotal" onclick="app.offer.item.recalculate();"><?php echo number_format($total,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)?></div>
            </div>
        </div>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
    <div class="add_item">
        <select class="select"><?php 
            foreach($items as $e) {
                echo "<option value=\"" .$e->id. "\">" .$e->name. "</option>";
            }
            ?></select>
        <div class="button new_item" onclick="app.offer.item.add();"><?php echo ucwords(OFFER_NEW_ITEM); ?></div>
    </div>
</div>
<script>
$(function() {
    $( ".form .datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
</script>
