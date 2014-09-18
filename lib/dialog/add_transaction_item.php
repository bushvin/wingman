<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * add_transaction_item.php
 * 
 * add transaction item dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

include_once(FS_DIALOG . "showitem.access.default.php" );
include_once(FS_DIALOG . "showitem.access." .$_SESSION["space"]. ".php" );

if ( !isset($item)) {
    $item = new stdClass();
    $item->id = -1;
    $item->volume = 0;
    $item->sku = "";
    $item->name = "";
    $item->date = date("Y-m-d");
    $item->discount_type = "relative";
    $item->discount = 0;
    $item->price = 0;
    $item->offer_item_id = -1;
    $item->vat_id = -1;
    $item->field0 = -1;
    if ( isset($data->data->offer_item_id)) {
        $sql = "SELECT * FROM `##_" .$_SESSION['space']. "_offer_item` WHERE `id`='" .$data->data->offer_item_id. "';";
        $DBO->query($sql);
        $o = $DBO->result("object");
        $item->name = $o->name;
        $item->sku = $o->sku;
        $item->price = $o->price;
        $item->offer_item_id = $o->id;
        $item->vat_id = $o->vat_id;
        $item->field0 = $o->field0;
    }
}
if ( ! isset($count)) {
    $count = 0;
    if (isset($data->data->count)) {
        $count = $data->data->count;
    }
}
if ( ! isset($vat)) {
    $sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_vat` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
    $DBO->query($sql);
    $vat = $DBO->result("objectlist");
}

?>
            <input type="hidden" value="<?php echo $item->id ?>" id="item[<?php echo $count; ?>].id" />
            <input type="hidden" value="<?php echo $item->offer_item_id ?>" id="item[<?php echo $count; ?>].offer_item_id" />
            <div class="cell date">
                <input class="input-text datepicker" type="text" value="<?php echo date("Y-m-d",strtotime($item->date)); ?>" placeholder="<?php echo ucwords(TRANSACTION_ITEM_DATE); ?>" id="item[<?php echo $count ?>].date"/>
            </div>
<?php if ( $_inventory_sku === true ) { ?>
            <div class="cell sku"><?php echo $item->sku ?></div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"><?php echo $item->field0 ?></div>
<?php } ?>
<?php if ( $_inventory_name === true ) { ?>
            <div class="cell name"><?php echo $item->name ?></div>
<?php } ?>
            <div class="cell volume">
                <div class="input-number">
                    <input type="number" maxlength="7" min="0" class="whole" value="<?php echo intval($item->volume); ?>" onkeyup="app.event.input.decimal.change($(this).parent());app.transaction.item.recalculate($(this).parent().parent().parent());" />
                    <span class="separator"><?php echo APP_DECIMAL_SEPARATOR; ?></span>
                    <input type="number" maxlength="3" min="0" class="fraction" value="<?php echo intval(($item->volume-intval($item->volume))*1000); ?>" onkeyup="app.event.input.decimal.change($(this).parent());app.transaction.item.recalculate($(this).parent().parent().parent());" />
                    <input class="raw" type="hidden" value="<?php echo $item->volume; ?>"  id="item[<?php echo $count; ?>].volume"/>
                </div>
            </div>
<?php if ( $_inventory_reference_price === true ) { ?>
            <div class="cell price">
            <?php echo number_format($item->price,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR) ?>
            <div style="display: none;" class="rawprice"><?php echo $item->price ?></div>
            </div>
<?php } ?>
<?php if ( $_inventory_reference_vat === true ) { ?>
            <div class="cell vat">
                <select class="select" id="item[<?php echo $count; ?>].vat_id"><?php 
            foreach($vat as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$item->vat_id?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select>
            </div>
<?php } ?>
            <div class="cell total">
                <div class="itemtotal"><?php echo number_format($item->volume*$item->price,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR); ?></div>
                <div style="display: none" class="rawtotal"><?php echo $item->volume*$item->price; ?></div>
            </div>
            <div class="cell action">
                <div class="delete"></div>
            </div>
<script>
$(function() {
    $( ".form .datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $(".form .action .delete").click(function(){
        $(this).parent().parent().remove();
        app.transaction.item.recalculate();
    })

  });
</script>
