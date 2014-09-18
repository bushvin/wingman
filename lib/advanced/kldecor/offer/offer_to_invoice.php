<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * export_to_transaction.php
 * 
 * export offer to transaction
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

foreach ($ITEM_IDS as $oid) {
    $OFFER = new offer($oid);
    $OFFER->details();
    $TRANSACTION = new transaction(-1);
    $TRANSACTION->details();
    $TRANSACTION->set("offer_id",$OFFER->get("id"));
    $TRANSACTION->set("entity_id",$OFFER->get("entity_id"));
    $TRANSACTION->set("reference",$OFFER->get("reference"));
    $TRANSACTION->set("acount","income");
    $TRANSACTION->set("type","invoice");
    $titems = array();
    foreach ($OFFER->get("item") as $item) {
        $t = new stdClass();
        $t->id = -1;
        $t->date = date("Y-m-d");
        $t->volume = $item->volume;
        $t->offer_item_id = $item->id;
        $t->vat_id = $item->vat_id;
        $titems[] = $t;
    }
    $TRANSACTION->set("item",$titems);
    $TRANSACTION->save();
}
?>