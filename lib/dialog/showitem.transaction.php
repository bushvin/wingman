<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.transaction.php
 * 
 * showitem.transaction dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//


$o = new transaction($data->data->id);
$o->details();

$sql = "SELECT `id`,CONCAT(`name`, ' (',`uid`,')') `name` FROM `##_" .$_SESSION['space']. "_entity` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$cust = $DBO->result("objectlist");

if ($o->get("id") == -1) {
    $entity_id = $cust[0]->id;
} else {
    $entity_id = $o->get("entity_id");
}

$sql = "SELECT `id`,`reference` FROM `##_" .$_SESSION['space']. "_offer` WHERE `deleted`=0 AND `id`>0 AND `entity_id`='" .$entity_id. "' ORDER BY `reference`;";
$DBO->query($sql);
$offer = $DBO->result("objectlist");
if ($o->get("id") == -1) {
    $offer_id = $offer[0]->id;
    $OFFER= new offer($offer_id);
    $OFFER->details();
    $deadline = $OFFER->get("payment");
} else {
    $offer_id = $o->get('offer_id');
    $deadline = $o->get('deadline');
}

$sql = "SELECT `a`.`id`,CONCAT(`a`.`sku`,' - ',`a`.`name`,' - ',`a`.`price`) `name`
                FROM `##_" .$_SESSION['space']. "_offer_item` `a` 
                WHERE `offer_id`='" .$offer_id. "'
                AND `id`>-1
                ORDER BY `name`;";
$DBO->query($sql);
$items = $DBO->result("objectlist");

?><div class="title"><?php echo ucwords($o->get("id")==-1?TRANSACTION_NEW_TRANSACTION:TRANSACTION_EDIT_TRANSACTION); ?></div>
<div class="form">
    <div class="column">
<?php if ( $_transaction_type === true ) { ?>
        <div class="row type">
            <div class="caption"><?php echo ucwords(TRANSACTION_TYPE); ?></div>
            <div class="value"><?php 
            if ($data->data->id == -1) {
            ?><select class="select" id="type">
                            <option value="invoice"<?php echo ($o->get('type')=="invoice"?" selected":""); ?>><?php echo ucwords(TRANSACTION_INVOICE); ?></option>
                            <option value="credit note"<?php echo ($o->get('type')=="credit note"?" selected":""); ?>><?php echo ucwords(TRANSACTION_CREDIT_NOTE); ?></option>
                        </select><?php 
            } else {
                switch ($o->get('type')) {
                    case "invoice":
                        echo ucwords(TRANSACTION_INVOICE);
                        break;
                    case "credit note":
                        echo ucwords(TRANSACTION_CREDIT_NOTE);
                        break;
                    default:
                        echo ucwords(TRANSACTION_OTHER);
                }
            }
            ?></div>
        </div>
<?php } ?>
<?php if ( $_transaction_customer === true ) { ?>
        <div class="row customer">
            <div class="caption"><?php echo ucwords(TRANSACTION_CUSTOMER); ?><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" /></div>
            <div class="value"><?php 
            if ($data->data->id == -1) {
            ?><select class="select" id="entity_id" ><?php 
            foreach($cust as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("entity_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select><?php 
            } else {
                echo $o->get("entity");
            }
            ?></div>
        </div>
<?php } ?>
<?php if ( $_transaction_offer === true ) { ?>
        <div class="row offer">
            <div class="caption"><?php echo ucwords(TRANSACTION_OFFER); ?></div>
            <div class="value"><?php 
            if ($data->data->id == -1) {
            ?><select class="select" id="offer_id">
<?php
                foreach ($offer as $e) {
                    echo "<option value=\"" .$e->id. "\">" .$e->reference. "</option>";
                }
?>
            </select><?php 
            } else {
                echo $o->get("reference");
            }
            ?></div>
        </div>
<?php } ?>
<?php if ( $_transaction_uid_prefix === true ) { ?>
        <div class="row uid_prefix">
            <div class="caption"><?php echo ucwords(TRANSACTION_UID_PREFIX); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('uid_prefix'); ?>" id="uid_prefix"/></div>
        </div>
<?php } ?>
<?php if ( $_transaction_uid === true ) { ?>
        <div class="row uid">
            <div class="caption"><?php echo ucwords(TRANSACTION_UID); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('uid'); ?>" id="uid"/></div>
        </div>
<?php } ?>
<?php if ( $_transaction_date === true ) { ?>
        <div class="row date">
            <div class="caption"><?php echo ucwords(TRANSACTION_DATE); ?></div>
            <div class="value"><input class="input-text datepicker" type="text" value="<?php echo $o->get('date'); ?>" id="date"/></div>
        </div>
<?php } ?>
<?php if ( $_transaction_deadline === true ) { ?>
        <div class="row deadline">
            <div class="caption"><?php echo ucwords(TRANSACTION_DEADLINE); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $deadline; ?>" id="deadline"/></div>
        </div>
        <hr />
<?php } ?>
<?php if ( $_transaction_reference === true ) { ?>
        <div class="row reference">
            <div class="caption"><?php echo ucwords(TRANSACTION_REFERENCE); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('reference'); ?>" id="reference"/></div>
        </div>
<?php } ?>
<?php if ( $_transaction_field0 === true ) { ?>
        <div class="row field0">
            <div class="caption"><?php echo ucwords(TRANSACTION_FIELD0); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('field0'); ?>"  id="field0"/></div>
        </div>
<?php } ?>
<?php if ( $_transaction_discount_type === true ) { ?>
        <div class="row discount_type">
            <div class="caption"><?php echo ucwords(TRANSACTION_DISCOUNT_TYPE); ?></div>
            <div class="value"><select class="select" id="discount_type" onchange="app.transaction.item.recalculate();">
                            <option value="relative"<?php echo ($o->get('discount_type')=="relative"?" selected":""); ?>><?php echo ucwords(TRANSACTION_RELATIVE); ?></option>
                            <option value="fixed"<?php echo ($o->get('discount_type')=="fixed"?" selected":""); ?>><?php echo ucwords(TRANSACTION_FIXED); ?></option>
                        </select></div>
        </div>
<?php } ?>
<?php if ( $_transaction_discount === true ) { ?>
        <div class="row discount">
            <div class="caption"><?php echo ucwords(TRANSACTION_DISCOUNT); ?></div>
            <div class="value">
                <div class="input-number">
                    <input type="number" maxlength="7" min="0" class="whole" value="<?php echo intval($o->get('discount')); ?>" onkeyup="app.event.input.decimal.change($(this).parent());app.transaction.item.recalculate();" />
                    <span class="separator"><?php echo APP_DECIMAL_SEPARATOR; ?></span>
                    <input type="number" maxlength="3" min="0" class="fraction" value="<?php echo intval(($o->get('discount')-intval($o->get('discount')))*1000); ?>" onkeyup="app.event.input.decimal.change($(this).parent());app.transaction.item.recalculate();" />
                    <input class="raw" type="hidden" value="<?php echo $o->get('discount'); ?>" id="discount"/>
                </div>
            </div>
        </div>
<?php } ?>
<?php if ( $_transaction_notes === true ) { ?>
        <div class="row notes">
            <div class="caption"><?php echo ucwords(TRANSACTION_NOTES); ?></div>
            <div class="value"><textarea id="notes" class="textarea" ><?php echo $o->get('notes'); ?></textarea></div>
        </div>
<?php } ?>
<?php if ( $_transaction_satisfied === true ) { ?>
        <div class="row satisfied">
            <div class="caption"><?php echo ucwords(TRANSACTION_SATISFIED); ?></div>
            <div class="value"><select class="select" id="satisfied"><option value="1"<?php echo ($o->get("satisfied")==1?" selected":""); ?>><?php echo ucwords(YES); ?></option><option value="0"<?php echo ($o->get("satisfied")==0?" selected":""); ?>><?php echo ucwords(NO); ?></option></select></div>
        </div>
    </div>
<?php } ?>
    <div class="column itemlist">
        <div class="item header">
            <div class="cell date"><?php echo ucwords(TRANSACTION_ITEM_DATE); ?></div>
<?php if ( $_inventory_sku === true ) { ?>
            <div class="cell sku"><?php echo ucwords(TRANSACTION_ITEM_SKU); ?></div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"><?php echo ucwords(TRANSACTION_ITEM_FIELD0); ?></div>
<?php } ?>
<?php if ( $_inventory_name === true ) { ?>
            <div class="cell name"><?php echo ucwords(TRANSACTION_ITEM_NAME); ?></div>
<?php } ?>
            <div class="cell volume"><?php echo ucwords(TRANSACTION_ITEM_VOLUME); ?></div>
<?php if ( $_inventory_reference_price === true ) { ?>
            <div class="cell price"><?php echo ucwords(TRANSACTION_ITEM_PRICE); ?></div>
<?php } ?>
<?php if ( $_inventory_reference_vat === true ) { ?>
            <div class="cell vat"><?php echo ucwords(TRANSACTION_ITEM_VAT); ?></div>
<?php } ?>
            <div class="cell total"><?php echo ucwords(TRANSACTION_ITEM_TOTAL); ?></div>
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
<?php        include(FS_DIALOG."add_transaction_item.php"); ?>
        </div>
<?php
        $count++;
        }
?>
        </div>
        <div class="totals">
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"></div>
<?php } ?>
            <div class="cell total">
                <div class="listtotal"><?php echo number_format($total,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)?></div>
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
        <div class="button new_item" ><?php echo ucwords(OFFER_NEW_ITEM); ?></div>
    </div>
</div>
<script>
$(function() {
    $(".form .datepicker").datepicker({ dateFormat: "yy-mm-dd" });
    $('.form #entity_id').change(function() { 
        var d = new app.cls.dialog();
        $('.object .offer #offer_id').empty();
        d.setDialog(app.cookie.get('current_view'),'get_options');
        d.setData('type','offer');
        d.setData('entity_id',$(this).val());
        d.setReturnTo($('.object .offer #offer_id'));
        d.load(function() {app.transaction.offerselect($('.object .offer #offer_id').val())});
        d.request();
        });
    $('.form #offer_id').change(function() {
        var d = new app.cls.dialog();
        $('.object .buttons .add_item select').empty();
        d.setDialog(app.cookie.get('current_view'),'get_options');
        d.setData('type','offer_item');
        d.setData('offer_id',$(this).val());
        d.setReturnTo($('.object .buttons .add_item select'));
        d.request();        
    
        var api = new app.cls.api();
        api.setAPI('offer_get');
        api.setData({id:$(this).val()});
        api.setReturnTo(function(data){$('#deadline').val(data.data.payment)});
        api.request();
        
        });
    $('.buttons .button.new_item').click(function() {
        var c = 0;
        if ( $('.itemview .object .form .column:last .list .item:last').attr('class')!==undefined ) {
            c = $('.itemview .object .form .column:last .list .item:last').attr('class').split(" ");
            for (var i=0; i<c.length;i++) {
                if (c[i].substr(0,2)=='id') {
                    c = parseInt(c[i].substr(2),10)+1;
                    break;
                }
            }
        }
        $('.itemview .object .form .column:last .list').append($('<div></div>')
                                                            .attr('class','item id'+c));
        var d = new app.cls.dialog();
        d.setDialog(app.cookie.get('current_view'),'add_transaction_item');
        d.setData('offer_item_id',$('.itemview .add_item select').val());
        d.setData('count',c);
        d.setReturnTo($('.itemview .object .form .column:last .item.id'+c));
        d.request();        
        });
        
  });
  </script>