<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.access.default.php
 * 
 * default showitem access definition
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
$_entity_name = true;
$_entity_uid = true;
$_entity_salutation = true;
$_entity_type = true;
$_entity_vat_number = true;
$_entity_tel = true;
$_entity_fax = true;
$_entity_email = true;
$_entity_www = true;
$_entity_correspondence_language = true;
$_entity_notes = true;

$_entitytype_name = true;
$_entitytype_description = true;
$_entitytype_vat_number_required = true;

$_inventory_name = true;
$_inventory_description = true;
$_inventory_field0 = true;
$_inventory_sku = true;
$_inventory_type = true;
$_inventory_reference_price = true;
$_inventory_reference_currency = true;
$_inventory_reference_vat = true;

$_offer_customer = true;
$_offer_reference = true;
$_offer_reference_prefix = true;
$_offer_description = true;
$_offer_remark = true;
$_offer_date = true;
$_offer_invoice_address = true;
$_offer_delivery_address = true;
$_offer_contact = true;
$_offer_currency = true;
$_offer_purchased = true;
$_offer_payment = true;

$_transaction_type = true;
$_transaction_customer = true;
$_transaction_offer = true;
$_transaction_uid_prefix = true;
$_transaction_uid = true;
$_transaction_date = true;
$_transaction_deadline = true;
$_transaction_reference = true;
$_transaction_field0 = true;
$_transaction_discount_type = true;
$_transaction_discount = true;
$_transaction_notes = true;
$_transaction_satisfied = true;



$_customer_name = true;
$_customer_space = true;
$_customer_transaction_uid_prefix = true;

?>