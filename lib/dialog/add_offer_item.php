<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * add_offer_item.php
 * 
 * add offer item dialog
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
    $item->name = "";
    $item->description = "";
    $item->volume = 0;
    $item->sku = "";
    $item->field0 = "";
    $item->price = 0;
    $item->inventory_item_id = -1;
    $item->inventory_item_name = "";
    if ( isset($data->data->inventory_item_id)) {
        $inventory = new inventory($data->data->inventory_item_id);
        $inventory->details();
        $item->name = $inventory->get("name");
        $item->description = $inventory->get("description");
        $item->volume = 1;
        $item->field0 =  $inventory->get("field0");
        $item->sku = $inventory->get("sku");
        $item->price = $inventory->get("indicative_price");
        $item->inventory_item_id = $data->data->inventory_item_id;
        $item->vat_id = $inventory->get("vat_id");
        $item->inventory_item_name = $inventory->get("name");
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
<?php if ( $_inventory_sku === true ) { ?>
            <div class="cell sku">
                <input class="input-text" type="text" value="<?php echo $item->sku ?>" id="item[<?php echo $count ?>].sku"/>
            </div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0">
                <input class="input-text" type="text" value="<?php echo $item->field0 ?>" id="item[<?php echo $count ?>].field0"/>
            </div>
<?php } ?>
<?php if ( $_inventory_name === true ) { ?>
            <div class="cell name">
                <input class="input-text" type="text" value="<?php echo $item->name ?>" id="item[<?php echo $count ?>].name"/>
            </div>
<?php } ?>
            <div class="cell volume">
                <div class="input-number">
                    <input type="number" maxlength="7" min="0" class="whole" value="<?php echo intval($item->volume); ?>" onkeyup="app.event.input.decimal.change($(this).parent());app.offer.item.recalculate($(this).parent().parent().parent());" />
                    <span class="separator"><?php echo APP_DECIMAL_SEPARATOR; ?></span>
                    <input type="number" maxlength="3" min="0" class="fraction" value="<?php echo intval(($item->volume-intval($item->volume))*1000); ?>" onkeyup="app.event.input.decimal.change($(this).parent())" />
                    <input class="raw" type="hidden" value="<?php echo $item->volume; ?>"  id="item[<?php echo $count; ?>].volume"/>
                </div>
            </div>
<?php if ( $_inventory_reference_price === true ) { ?>
            <div class="cell price">
                <div class="input-number">
                    <input type="number" maxlength="7" min="0" class="whole" value="<?php echo intval($item->price); ?>" onkeyup="app.event.input.decimal.change($(this).parent());app.offer.item.recalculate($(this).parent().parent().parent());" />
                    <span class="separator"><?php echo APP_DECIMAL_SEPARATOR; ?></span>
                    <input type="number" maxlength="3" min="0" class="fraction" value="<?php echo intval(($item->price-intval($item->price))*1000); ?>" onkeyup="app.event.input.decimal.change($(this).parent())" />
                    <input class="raw" type="hidden" value="<?php echo $item->price; ?>"  id="item[<?php echo $count; ?>].price"/>
                </div>
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
                <div class="itemtotal">
                    <?php echo number_format($item->volume*$item->price,3,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR)?>
                </div>
                <div style="display: none" class="rawtotal"><?php echo number_format($item->volume*$item->price,3); ?></div>
            </div>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"></div>
<?php } ?>
<?php if ( $_inventory_description === true ) { ?>
            <div class="cell description">
                <textarea id="item[<?php echo $count; ?>].description" class="textarea" ><?php echo $item->description; ?></textarea>
            </div>
<?php } ?>
<?php if ( $_inventory_field0 === true ) { ?>
            <div class="cell field0"></div>
<?php } ?>
            <div class="cell inventory">
                <div><?php echo $item->inventory_item_name; ?></div>
                <input type="hidden" value="<?php echo $item->inventory_item_id ?>" id="item[<?php echo $count; ?>].inventory_item_id" />
            </div>
            <div class="cell action"><div class="delete" onclick="app.offer.item.del($(this).parent().parent());"></div></div>
