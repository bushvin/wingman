<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");

/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * en-uk/default.php
 * 
 * translations for en-uk (UK English)
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

if (file_exists(dirname(__FILE__)."/".$_SESSION["space"]. ".php")) {
    include_once(dirname(__FILE__)."/".$_SESSION["space"]. ".php");
}

//define("WORD","translation");
define("LOGIN_PAGE","login page");
define("USERNAME","login");
define("PASSWORD","passphrase");
define("SIGNIN","sign in");
define("SIGNOUT","sign out");
define("GLOBAL_NEVER","never");
define("NEVER","never");
define("YES","yes");
define("NO","no");
define("RES_SUCCESS","success");
define("RES_FAILED","failed");
define("DASHBOARD","dashboard");
define("SETTINGS","settings");
define("CREATE","new");
define("DELETE","remove");
define("SAVE","save");
define("APPLY","apply");
define("CANCEL","cancel");
define("MORE_ACTIONS","more actions");
define("CLOSE","close");
define("ITEM_NOT_FOUND","item not found");
define("ITEM_NOT_FOUND_DESC","the item you are looking for was not found");
define("PAGE","page");
define("PAGE_OF","of");
define("CHANGE_PASSWORD","change passphrase");
define("ENTER_OLD_PASSWORD","current passphrase");
define("ENTER_NEW_PASSWORD","new passphrase");
define("CONFIRM_NEW_PASSWORD","confirm passphrase");
define("PASSWORD_POLICY","the password must meet the following criteria");
define("PASSWORD_POLICY_8CHARS","minimum 8 characters");
define("PASSWORD_POLICY_ALPHABET","normal and capital letters: a-z, A-Z");
define("PASSWORD_POLICY_NUMBER","at least 1 numner: 0-9");
define("PASSWORD_POLICY_SYMBOL","at least one symbol:");
define("SHOW_ITEMCOUNT","items per page:");
define("ERROR_USER_PASSWORD_INCORRECT","The email or password you entered is incorrect.");
define("ERROR_USER_TOKEN_INVALID","your security token is incorrect.");
define("ERROR_OLD_PASSWORD_INVALID","the old password is incorrect.");
define("ERROR_ITEM_COULD_NOT_BE_REMOVED","the chosen item could not be removed.");
define("ERROR_GENERAL_1000","an unknown error has ocurred.<br />Contact a sysadmin.");
define("ERROR_API_DOESNT_EXIST","The API requested doesn't exist.");
define("RELOAD_PAGE","reload page");
define("GO_TO_LOGIN_PAGE","go to login page");
define("WARNING_THIS_DOES_NOT_COMPUTE","this message should not be displayed.<br />Contact a sysadmin.");

define("ADVANCED_EXPORT_TO_PDF","export to PDF");
define("ADVANCED_EXPORT_TO_PDF_WITH_LETTERHEAD","export to PDF with letterhead");
define("ADVANCED_OFFER_TO_INVOICE","create invoice");
define("ADVANCED_IMPORT_FROM_CSV","import from CSV");
define("ADVANCED_SELECT_FILE","select file");
define("ADVANCED_TEXT_DELIMITER","text delimiter");
define("ADVANCED_DELIMITER","separator");


define("VIEW_ENTITY","customers");
define("VIEW_ENTITYTYPE","company type");
define("VIEW_INVENTORY","inventory");
define("VIEW_OFFER","offer");
define("VIEW_TRANSACTION","transactions");
define("VIEW_USER","users");
define("VIEW_ROLE","roles");
define("VIEW_VAT","VAT");
define("VIEW_LANGUAGE","languages");
define("VIEW_CRONTAB","crontab");
define("VIEW_CURRENCY","currencies");
define("VIEW_CUSTOMER","customers");

define("ENTITY_ID","id");
define("ENTITY_UID","number");
define("ENTITY_SALUTATION","salutation");
define("ENTITY_NAME","name");
define("ENTITY_TYPE","type");
define("ENTITY_TEL","tel");
define("ENTITY_FAX","fax");
define("ENTITY_WWW","WWW");
define("ENTITY_EMAIL","email");
define("ENTITY_VAT_NUMBER","ON number");
define("ENTITY_DEFAULT_CURRENCY","standard currency");
define("ENTITY_DEFAULT_VAT","standard vat");
define("ENTITY_CORRESPONDENCE_LANGUAGE","correspondence language");
define("ENTITY_NEW_ENTITY","new customer");
define("ENTITY_EDIT_ENTITY","edit customer");
define("ENTITY_ADDRESS_TYPE","address type");
define("ENTITY_ADDRESS_ATTN","for the attention of");
define("ENTITY_ADDRESS_STREET","street");
define("ENTITY_ADDRESS_CODE","postal code");
define("ENTITY_ADDRESS_CITY","cisty");
define("ENTITY_ADDRESS_PROVINCE","province");
define("ENTITY_ADDRESS_COUNTRY","country");
define("ENTITY_ADDRESS_INVOICING","invoice address");
define("ENTITY_NEW_ADDRESS","new address");
define("ENTITY_REF_PREFIX","reference prefix");
define("ENTITY_NOTES","notes");
define("ENTITY_OFFERS","offers");
define("ENTITY_TRANSACTIONS","transactions");

define("ENTITYTYPE_ID","id");
define("ENTITYTYPE_NAME","name");
define("ENTITYTYPE_DESCRIPTION","description");
define("ENTITYTYPE_VAT_NUMBER_REQUIRED","VAT number required");
define("ENTITYTYPE_NEW_ENTITYTYPE","new company type");
define("ENTITYTYPE_EDIT_ENTITYTYPE","edit company type");

define("INVENTORY_ID","id");
define("INVENTORY_NAME","name");
define("INVENTORY_SKU","SKU");
define("INVENTORY_CURRENCY","currency");
define("INVENTORY_PRICE","price");
define("INVENTORY_VAT","VAT");
define("INVENTORY_TYPE","type");
define("INVENTORY_DESCRIPTION","description");
define("INVENTORY_NEW_INVENTORY","new inventory");
define("INVENTORY_EDIT_INVENTORY","edit inventory");
define("INVENTORY_REFERENCE_PRICE","target price");
define("INVENTORY_REFERENCE_CURRENCY","target currency");
define("INVENTORY_REFERENCE_VAT","target VAT");

define("OFFER_ID","id");
define("OFFER_REFERENCE","reference");
define("OFFER_REFERENCE_PREFIX","reference prefix");
define("OFFER_DESCRIPTION","description");
define("OFFER_REMARK","remark");
define("OFFER_CONTACT","contact");
define("OFFER_CUSTOMER","customer");
define("OFFER_DATE","date");
define("OFFER_PURCHASED","ordered");
define("OFFER_NEW_OFFER","new offer");
define("OFFER_EDIT_OFFER","edit offer");
define("OFFER_VAT","VAT");
define("OFFER_CURRENCY","currency");
define("OFFER_ITEM_VOLUME","volume");
define("OFFER_ITEM_SKU","SKU");
define("OFFER_ITEM_NAME","name");
define("OFFER_ITEM_PRICE","price");
define("OFFER_ITEM_TOTAL","total");
define("OFFER_ITEM_DESCRIPTION","description");
define("OFFER_ITEM_INVENTORY","inventory");
define("OFFER_ITEM_VAT","VAT");
define("OFFER_NEW_ITEM","add");
define("OFFER_DELIVERY_ADDRESS","delivery address");
define("OFFER_INVOICE_ADDRESS","invoice address");
define("OFFER_PAYMENT","payment (d)");



define("TRANSACTION_ID","id");
define("TRANSACTION_DATE","date");
define("TRANSACTION_TYPE","type");
define("TRANSACTION_UID","doc no");
define("TRANSACTION_UID_PREFIX","doc no prefix");
define("TRANSACTION_DOCUMENT_DATE","doc date");
define("TRANSACTION_ENTITY","customer");
define("TRANSACTION_REFERENCE","reference");
define("TRANSACTION_ACCOUNT","account");
define("TRANSACTION_SATISFIED","satisfied");
define("TRANSACTION_OVERDUE","overdue");
define("TRANSACTION_EDIT_TRANSACTION","edit transaction");
define("TRANSACTION_NEW_TRANSACTION","new transaction");
define("TRANSACTION_CUSTOMER","customer");
define("TRANSACTION_OFFER","offer");
define("TRANSACTION_DISCOUNT_TYPE","discount type");
define("TRANSACTION_FIXED","fixed");
define("TRANSACTION_RELATIVE","relative");
define("TRANSACTION_DISCOUNT","discount");
define("TRANSACTION_CURRENCY","currency");
define("TRANSACTION_VAT","VAT");
define("TRANSACTION_NOTES","notes");
define("TRANSACTION_PERIOD","period");
define("TRANSACTION_DEADLINE","deadline");
define("TRANSACTION_INVOICE","invoice");
define("TRANSACTION_CREDIT_NOTE","credit note");
define("TRANSACTION_OTHER","other");


define("TRANSACTION_ITEM_VOLUME","volume");
define("TRANSACTION_ITEM_NAME","name");
define("TRANSACTION_ITEM_SKU","SKU");
define("TRANSACTION_ITEM_PRICE","price");
define("TRANSACTION_ITEM_DATE","date");
define("TRANSACTION_ITEM_DISCOUNT_TYPE","discount type");
define("TRANSACTION_ITEM_DISCOUNT","discount");
define("TRANSACTION_ITEM_TOTAL","total");
define("TRANSACTION_ITEM_VAT","VAT");
define("TRANSACTION_CUSTOMER_NUMBER","customer no");
define("TRANSACTION_VAT_NUMBER","VAT no");
define("TRANSACTION_PRICE_PER","price per");
define("TRANSACTION_TOTAL","total");
define("TRANSACTION_UNIT","unit");
define("TRANSACTION_NET","net");
define("TRANSACTION_SKU","SKU");
define("TRANSACTION_DESCRIPTION","description");
define("TRANSACTION_VOLUME","volume");
define("TRANSACTION_AMOUNT","amount");
define("TRANSACTION_REMARK","remark");
define("TRANSLATE_TOTAL_AMOUNT","total amount");




define("CURRENCY_ID","id");
define("CURRENCY_NAME","name");
define("CURRENCY_SHORT","abbreviation");
define("CURRENCY_SIGN","symbol");
define("CURRENCY_NEW_CURRENCY","new currency");
define("CURRENCY_EDIT_CURRENCY","edit currency");

define("VAT_ID","id");
define("VAT_NAME","name");
define("VAT_TYPE","type");
define("VAT_AMOUNT","volume");
define("VAT_NEW_VAT","new VAT");
define("VAT_EDIT_VAT","edit VAT");
define("VAT_DESCRIPTION","description");
define("VAT_REMARK","remark");
define("VAT_RELATIVE","relative");
define("VAT_FIXED","fixed");

define("CRONTAB_ID","id");
define("CRONTAB_NAME","name");
define("CRONTAB_EXPORT_TO","export to");
define("CRONTAB_RECURRENCE","recurrence");
define("CRONTAB_LAST_RUN","last run");
define("CRONTAB_EXIT_STATUS","result");

define("LANGUAGE_ID","id");
define("LANGUAGE_NAME","name");
define("LANGUAGE_SHORT","short");
define("LANGUAGE_NEW_LANGUAGE","new language");
define("LANGUAGE_EDIT_LANGUAGE","edit language");

define("USER_ID","id");
define("USER_NAME","name");
define("USER_LOGIN","login");
define("USER_PASSWORD","password");
define("USER_EMAIL","email");
define("USER_TYPE","type");
define("USER_LANGUAGE","language");
define("USER_LOCALE","settings");
define("USER_LAST_LOGIN","last login");
define("USER_NEW_USER","new user");
define("USER_EDIT_USER","edit user");
define("USER_CUSTOMER","customer");
define("USER_TOKEN_TIMEOUT","token timeout");
define("USER_ROLES","roles");

define("ROLE_ID","id");
define("ROLE_NAME","name");
define("ROLE_DESCRIPTION","description");
define("ROLE_NEW_ROLE","new role");
define("ROLE_EDIT_ROLE","edit role");
define("ROLE_USERS","users");
define("ROLE_ACL","ACL");

define("CUSTOMER_ID","id");
define("CUSTOMER_NAME","name");
define("CUSTOMER_SPACE","space");
define("CUSTOMER_NEW_CUSTOMER","new customer");
define("CUSTOMER_EDIT_CUSTOMER","edit customer");
define("CUSTOMER_TRANSACTION_UID_PREFIX","transaction UID prefix");

define("STATUSMSG_CHANGES_APPLIED","changes applied");
define("STATUSMSG_NO_ITEMS_SELECTED","no items selected");
define("STATUSMSG_ITEM_DELETED","item removed");
define("STATUSMSG_ITEMS_DELETED","items removed");
define("STATUSMSG_ACTION_EXECUTED","action executed");

define("ACE_CRONTAB_CREATE","Report (create)");
define("ACE_CRONTAB_DELETE","Report (delete)");
define("ACE_CRONTAB_FULL","Report (full)");
define("ACE_CRONTAB_MODIFY","Report (modify)");
define("ACE_CRONTAB_READ","Report (read)");
define("ACE_CURRENCY_CREATE","Currency (create)");
define("ACE_CURRENCY_DELETE","Currency (delete)");
define("ACE_CURRENCY_FULL","Currency (full)");
define("ACE_CURRENCY_MODIFY","Currency (modify)");
define("ACE_CURRENCY_READ","Currency (read)");
define("ACE_ENTITY_CREATE","Customer (create)");
define("ACE_ENTITY_DELETE","Customer (delete)");
define("ACE_ENTITY_FULL","Customer (full)");
define("ACE_ENTITY_MODIFY","Customer (modify)");
define("ACE_ENTITY_READ","Customer (read)");
define("ACE_ENTITYTYPE_CREATE","Company Type (create)");
define("ACE_ENTITYTYPE_DELETE","Company Type (delete)");
define("ACE_ENTITYTYPE_FULL","Company Type (full)");
define("ACE_ENTITYTYPE_MODIFY","Company Type (modify)");
define("ACE_ENTITYTYPE_READ","Company Type (read)");
define("ACE_INVENTORY_CREATE","Inventory (create)");
define("ACE_INVENTORY_DELETE","Inventory (delete)");
define("ACE_INVENTORY_FULL","Inventory (full)");
define("ACE_INVENTORY_MODIFY","Inventory (modify)");
define("ACE_INVENTORY_READ","Inventory (read)");
define("ACE_LANGUAGE_CREATE","Language (create)");
define("ACE_LANGUAGE_DELETE","Language (delete)");
define("ACE_LANGUAGE_FULL","Language (full)");
define("ACE_LANGUAGE_MODIFY","Language (modify)");
define("ACE_LANGUAGE_READ","Language (read)");
define("ACE_OFFER_CREATE","Offer (create)");
define("ACE_OFFER_DELETE","Offer (delete)");
define("ACE_OFFER_FULL","Offer (full)");
define("ACE_OFFER_MODIFY","Offer (modify)");
define("ACE_OFFER_READ","Offer (read)");
define("ACE_ROLE_CREATE","Role (create)");
define("ACE_ROLE_DELETE","Role (delete)");
define("ACE_ROLE_FULL","Role (full)");
define("ACE_ROLE_MODIFY","Role (modify)");
define("ACE_ROLE_READ","Role (read)");
define("ACE_TRANSACTION_CREATE","Transaction (create)");
define("ACE_TRANSACTION_DELETE","Transaction (delete)");
define("ACE_TRANSACTION_FULL","Transaction (full)");
define("ACE_TRANSACTION_MODIFY","Transaction (modify)");
define("ACE_TRANSACTION_READ","Transaction (read)");
define("ACE_USER_CREATE","User (create)");
define("ACE_USER_DELETE","User (delete)");
define("ACE_USER_FULL","User (full)");
define("ACE_USER_MODIFY","User (modify)");
define("ACE_USER_READ","User (read)");
define("ACE_VAT_CREATE","VAT (create)");
define("ACE_VAT_DELETE","VAT (delete)");
define("ACE_VAT_FULL","VAT (full)");
define("ACE_VAT_MODIFY","VAT (modify)");
define("ACE_VAT_READ","VAT (read)");
define("ACE_CUSTOMER_CREATE","Customer (create)");
define("ACE_CUSTOMER_DELETE","Customer (delete)");
define("ACE_CUSTOMER_FULL","Customer (full)");
define("ACE_CUSTOMER_MODIFY","Customer (modify)");
define("ACE_CUSTOMER_READ","Customer (read)");


?>