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
 * nl-be/default.php
 * 
 * translations for nl-be (vlaams)
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
define("LOGIN_PAGE","Login Pagina");
define("USERNAME","login");
define("PASSWORD","wachtwoord");
define("SIGNIN","log in");
define("SIGNOUT","log uit");
define("GLOBAL_NEVER","nooit");
define("NEVER","nooit");
define("YES","ja");
define("NO","nee");
define("RES_SUCCESS","succes");
define("RES_FAILED","gefaald");
define("DASHBOARD","dashboard");
define("SETTINGS","instellingen");
define("CREATE","nieuw");
define("DELETE","verwijderen");
define("SAVE","bewaren");
define("APPLY","wijzigen");
define("CANCEL","annuleren");
define("MORE_ACTIONS","meer acties");
define("CLOSE","sluiten");
define("ITEM_NOT_FOUND","item niet gevonden");
define("ITEM_NOT_FOUND_DESC","het item dat u zoekt werd niet gevonden");
define("PAGE","pagina");
define("PAGE_OF","van de");
define("CHANGE_PASSWORD","verander wachtwoord");
define("ENTER_OLD_PASSWORD","huidig wachtwoord");
define("ENTER_NEW_PASSWORD","nieuw wachtwoord");
define("CONFIRM_NEW_PASSWORD","confirmeer wachtwoord");
define("PASSWORD_POLICY","het wachtwoord moet voldoen aan volgende criteria");
define("PASSWORD_POLICY_8CHARS","minimum 8 karakters");
define("PASSWORD_POLICY_ALPHABET","hoofd- en kleine letters: a-z, A-Z");
define("PASSWORD_POLICY_NUMBER","minimum 1 cijfer: 0-9");
define("PASSWORD_POLICY_SYMBOL","minimum 1 symbool:");
define("SHOW_ITEMCOUNT","aantal items per pagina:");
define("ERROR_USER_PASSWORD_INCORRECT","de gebruiker of wachtwoord dat u heeft ingegeven is verkeerd");
define("ERROR_USER_TOKEN_INVALID","uw security token is incorrect.");
define("ERROR_OLD_PASSWORD_INVALID","het oude wachtwoord is verkeerd.");
define("ERROR_ITEM_COULD_NOT_BE_REMOVED","het gekozen item kon niet verwijderd worden.");
define("ERROR_GENERAL_1000","er is een fout opgetreden.<br />Contacteer de sysadmin.");
define("ERROR_API_DOESNT_EXIST","De gevraagde API bestaat niet.");
define("RELOAD_PAGE","pagina herladen");
define("GO_TO_LOGIN_PAGE","ga naar de login pagina");
define("WARNING_THIS_DOES_NOT_COMPUTE","dit bericht zou niet getoond mogen worden.<br />Contacteer de sysadmin.");

define("ADVANCED_EXPORT_TO_PDF","exporteer naar PDF");
define("ADVANCED_EXPORT_TO_PDF_WITH_LETTERHEAD","exporteer naar PDF met briefhoofd");
define("ADVANCED_OFFER_TO_INVOICE","maak factuur aan");
define("ADVANCED_IMPORT_FROM_CSV","importeer van CSV");
define("ADVANCED_SELECT_FILE","kies bestand");
define("ADVANCED_TEXT_DELIMITER","text delimiter");
define("ADVANCED_DELIMITER","separator");

define("VIEW_ENTITY","klanten");
define("VIEW_ENTITYTYPE","bedrijfvormen");
define("VIEW_INVENTORY","inventaris");
define("VIEW_OFFER","offerte");
define("VIEW_TRANSACTION","transacties");
define("VIEW_USER","gebruikers");
define("VIEW_ROLE","rollen");
define("VIEW_VAT","BTW tarieven");
define("VIEW_LANGUAGE","Talen");
define("VIEW_CRONTAB","Crontab");
define("VIEW_CURRENCY","Munten");
define("VIEW_CUSTOMER","Klanten");

define("ENTITY_ID","id");
define("ENTITY_UID","nummer");
define("ENTITY_SALUTATION","aanhef");
define("ENTITY_NAME","naam");
define("ENTITY_TYPE","type");
define("ENTITY_TEL","tel");
define("ENTITY_FAX","fax");
define("ENTITY_WWW","WWW");
define("ENTITY_EMAIL","email");
define("ENTITY_VAT_NUMBER","ON nummer");
define("ENTITY_DEFAULT_CURRENCY","standaard munt");
define("ENTITY_DEFAULT_VAT","standaard btw");
define("ENTITY_CORRESPONDENCE_LANGUAGE","taal briefwisseling");
define("ENTITY_NEW_ENTITY","nieuwe klant");
define("ENTITY_EDIT_ENTITY","bewerk klant");
define("ENTITY_ADDRESS_TYPE","adres type");
define("ENTITY_ADDRESS_ATTN","ter attentie van");
define("ENTITY_ADDRESS_STREET","straat");
define("ENTITY_ADDRESS_CODE","postcode");
define("ENTITY_ADDRESS_CITY","stad");
define("ENTITY_ADDRESS_PROVINCE","provincie");
define("ENTITY_ADDRESS_COUNTRY","land");
define("ENTITY_ADDRESS_INVOICING","facturatieadres");
define("ENTITY_NEW_ADDRESS","nieuw adres");
define("ENTITY_REF_PREFIX","referentie prefix");
define("ENTITY_NOTES","nota's");
define("ENTITY_OFFERS","offertes");
define("ENTITY_TRANSACTIONS","transacties");

define("ENTITYTYPE_ID","id");
define("ENTITYTYPE_NAME","naam");
define("ENTITYTYPE_DESCRIPTION","omschrijving");
define("ENTITYTYPE_VAT_NUMBER_REQUIRED","BTW nummer vereist");
define("ENTITYTYPE_NEW_ENTITYTYPE","nieuw bedrijfsvorm");
define("ENTITYTYPE_EDIT_ENTITYTYPE","bewerk bedrijfsvorm");

define("INVENTORY_ID","id");
define("INVENTORY_NAME","naam");
define("INVENTORY_SKU","SKU");
define("INVENTORY_CURRENCY","munt");
define("INVENTORY_PRICE","prijs");
define("INVENTORY_VAT","BTW");
define("INVENTORY_TYPE","type");
define("INVENTORY_DESCRIPTION","omschrijving");
define("INVENTORY_NEW_INVENTORY","nieuwe inventaris");
define("INVENTORY_EDIT_INVENTORY","bewerk inventaris");
define("INVENTORY_REFERENCE_PRICE","richtprijs");
define("INVENTORY_REFERENCE_CURRENCY","richtmunt");
define("INVENTORY_REFERENCE_VAT","richt BTW");

define("OFFER_ID","id");
define("OFFER_REFERENCE","referentie");
define("OFFER_REFERENCE_PREFIX","referentie prefix");
define("OFFER_DESCRIPTION","omschrijving");
define("OFFER_REMARK","opmerking");
define("OFFER_CONTACT","contactpersoon");
define("OFFER_CUSTOMER","klant");
define("OFFER_DATE","datum");
define("OFFER_PURCHASED","besteld");
define("OFFER_NEW_OFFER","nieuwe offerte");
define("OFFER_EDIT_OFFER","bewerk offerte");
define("OFFER_VAT","bewerk offerte");
define("OFFER_CURRENCY","munt");
define("OFFER_ITEM_VOLUME","aantal");
define("OFFER_ITEM_SKU","SKU");
define("OFFER_ITEM_NAME","naam");
define("OFFER_ITEM_PRICE","prijs");
define("OFFER_ITEM_TOTAL","totaal");
define("OFFER_ITEM_DESCRIPTION","omschrijving");
define("OFFER_ITEM_INVENTORY","inventaris");
define("OFFER_ITEM_VAT","BTW");
define("OFFER_NEW_ITEM","toevoegen");
define("OFFER_DELIVERY_ADDRESS","leveringsadres");
define("OFFER_INVOICE_ADDRESS","fakturatieadres");
define("OFFER_PAYMENT","betalingstermijn (d)");



define("TRANSACTION_ID","id");
define("TRANSACTION_DATE","datum");
define("TRANSACTION_TYPE","type");
define("TRANSACTION_UID","doc nr");
define("TRANSACTION_UID_PREFIX","doc nr prefix");
define("TRANSACTION_DOCUMENT_DATE","doc datum");
define("TRANSACTION_ENTITY","klant");
define("TRANSACTION_REFERENCE","referentie");
define("TRANSACTION_ACCOUNT","rekening");
define("TRANSACTION_SATISFIED","voldaan");
define("TRANSACTION_OVERDUE","achterstallig");
define("TRANSACTION_NEW_TRANSACTION","nieuwe transactie");
define("TRANSACTION_EDIT_TRANSACTION","bewerk transactie");
define("TRANSACTION_CUSTOMER","klant");
define("TRANSACTION_OFFER","offerte");
define("TRANSACTION_DISCOUNT_TYPE","kortingtype");
define("TRANSACTION_FIXED","vast");
define("TRANSACTION_RELATIVE","procentueel");
define("TRANSACTION_DISCOUNT","korting");
define("TRANSACTION_CURRENCY","munt");
define("TRANSACTION_VAT","BTW");
define("TRANSACTION_NOTES","nota's");
define("TRANSACTION_PERIOD","periode");
define("TRANSACTION_DEADLINE","deadline");
define("TRANSACTION_INVOICE","factuur");
define("TRANSACTION_CREDIT_NOTE","kredietnota");
define("TRANSACTION_OTHER","andere");


define("TRANSACTION_ITEM_VOLUME","aantal");
define("TRANSACTION_ITEM_NAME","naam");
define("TRANSACTION_ITEM_SKU","SKU");
define("TRANSACTION_ITEM_PRICE","prijs");
define("TRANSACTION_ITEM_DATE","datum");
define("TRANSACTION_ITEM_DISCOUNT_TYPE","kortingstype");
define("TRANSACTION_ITEM_DISCOUNT","korting");
define("TRANSACTION_ITEM_TOTAL","totaal");
define("TRANSACTION_ITEM_VAT","BTW");
define("TRANSACTION_CUSTOMER_NUMBER","klant nr");
define("TRANSACTION_VAT_NUMBER","BTW nr");
define("TRANSACTION_PRICE_PER","prijs per");
define("TRANSACTION_TOTAL","totaal");
define("TRANSACTION_UNIT","eenheid");
define("TRANSACTION_NET","netto");
define("TRANSACTION_SKU","SKU");
define("TRANSACTION_DESCRIPTION","omschrijving");
define("TRANSACTION_VOLUME","aantal");
define("TRANSACTION_AMOUNT","bedrag");
define("TRANSACTION_REMARK","opmerking");
define("TRANSLATE_TOTAL_AMOUNT","totaal bedrag");




define("CURRENCY_ID","id");
define("CURRENCY_NAME","naam");
define("CURRENCY_SHORT","afkorting");
define("CURRENCY_SIGN","symbool");
define("CURRENCY_NEW_CURRENCY","nieuwe munt");
define("CURRENCY_EDIT_CURRENCY","bewerk munt");

define("VAT_ID","id");
define("VAT_NAME","naam");
define("VAT_TYPE","type");
define("VAT_AMOUNT","aantal");
define("VAT_NEW_VAT","nieuw BTW regime");
define("VAT_EDIT_VAT","bewerk BTW regime");
define("VAT_DESCRIPTION","omschrijving");
define("VAT_REMARK","opmerking");
define("VAT_RELATIVE","procentueel");
define("VAT_FIXED","vast");

define("CRONTAB_ID","id");
define("CRONTAB_NAME","naam");
define("CRONTAB_EXPORT_TO","naar");
define("CRONTAB_RECURRENCE","herhaling");
define("CRONTAB_LAST_RUN","laatste");
define("CRONTAB_EXIT_STATUS","resultaat");

define("LANGUAGE_ID","id");
define("LANGUAGE_NAME","naam");
define("LANGUAGE_SHORT","kort");
define("LANGUAGE_NEW_LANGUAGE","nieuwe taal");
define("LANGUAGE_EDIT_LANGUAGE","bewerk taal");

define("USER_ID","id");
define("USER_NAME","naam");
define("USER_LOGIN","login");
define("USER_PASSWORD","wachtwoord");
define("USER_EMAIL","email");
define("USER_TYPE","type");
define("USER_LANGUAGE","taal");
define("USER_LOCALE","instellingen");
define("USER_LAST_LOGIN","laatse login");
define("USER_NEW_USER","nieuwe gebruiker");
define("USER_EDIT_USER","bewerk gebruiker");
define("USER_CUSTOMER","klant");
define("USER_TOKEN_TIMEOUT","token timeout");
define("USER_ROLES","rollen");

define("ROLE_ID","id");
define("ROLE_NAME","name");
define("ROLE_DESCRIPTION","description");
define("ROLE_NEW_ROLE","nieuwe rol");
define("ROLE_EDIT_ROLE","bewerk rol");
define("ROLE_USERS","gebruikers");
define("ROLE_ACL","ACL");

define("CUSTOMER_ID","id");
define("CUSTOMER_NAME","naam");
define("CUSTOMER_SPACE","space");
define("CUSTOMER_NEW_CUSTOMER","nieuwe klant");
define("CUSTOMER_EDIT_CUSTOMER","bewerk klant");
define("CUSTOMER_TRANSACTION_UID_PREFIX","transactie UID prefix");

define("STATUSMSG_CHANGES_APPLIED","veranderingen bewaard");
define("STATUSMSG_NO_ITEMS_SELECTED","geen items gekozen");
define("STATUSMSG_ITEM_DELETED","item verwijderd");
define("STATUSMSG_ITEMS_DELETED","items verwijderd");
define("STATUSMSG_ACTION_EXECUTED","actie uitgevoerd");

define("ACE_CRONTAB_CREATE","Rapport (aanmaken)");
define("ACE_CRONTAB_DELETE","Rapport (verwijderen)");
define("ACE_CRONTAB_FULL","Rapport (alle)");
define("ACE_CRONTAB_MODIFY","Rapport (veranderen)");
define("ACE_CRONTAB_READ","Rapport (lezen)");
define("ACE_CURRENCY_CREATE","Munt (aanmaken)");
define("ACE_CURRENCY_DELETE","Munt (verwijderen)");
define("ACE_CURRENCY_FULL","Munt (alle)");
define("ACE_CURRENCY_MODIFY","Munt (veranderen)");
define("ACE_CURRENCY_READ","Munt (lezen)");
define("ACE_ENTITY_CREATE","Klant (aanmaken)");
define("ACE_ENTITY_DELETE","Klant (verwijderen)");
define("ACE_ENTITY_FULL","Klant (alle)");
define("ACE_ENTITY_MODIFY","Klant (veranderen)");
define("ACE_ENTITY_READ","Klant (lezen)");
define("ACE_ENTITYTYPE_CREATE","Bedrijfstype (create)");
define("ACE_ENTITYTYPE_DELETE","Bedrijfstype (delete)");
define("ACE_ENTITYTYPE_FULL","Bedrijfstype (full)");
define("ACE_ENTITYTYPE_MODIFY","Bedrijfstype (modify)");
define("ACE_ENTITYTYPE_READ","Bedrijfstype (read)");
define("ACE_INVENTORY_CREATE","Inventaris (aanmaken)");
define("ACE_INVENTORY_DELETE","Inventaris (verwijderen)");
define("ACE_INVENTORY_FULL","Inventaris (alle)");
define("ACE_INVENTORY_MODIFY","Inventaris (veranderen)");
define("ACE_INVENTORY_READ","Inventaris (lezen)");
define("ACE_LANGUAGE_CREATE","Taal (aanmaken)");
define("ACE_LANGUAGE_DELETE","Taal (verwijderen)");
define("ACE_LANGUAGE_FULL","Taal (alle)");
define("ACE_LANGUAGE_MODIFY","Taal (veranderen)");
define("ACE_LANGUAGE_READ","Taal (lezen)");
define("ACE_OFFER_CREATE","Offerte (aanmaken)");
define("ACE_OFFER_DELETE","Offerte (verwijderen)");
define("ACE_OFFER_FULL","Offerte (alle)");
define("ACE_OFFER_MODIFY","Offerte (veranderen)");
define("ACE_OFFER_READ","Offerte (lezen)");
define("ACE_ROLE_CREATE","Rol (aanmaken)");
define("ACE_ROLE_DELETE","Rol (verwijderen)");
define("ACE_ROLE_FULL","Rol (alle)");
define("ACE_ROLE_MODIFY","Rol (veranderen)");
define("ACE_ROLE_READ","Rol (lezen)");
define("ACE_TRANSACTION_CREATE","Transactie (aanmaken)");
define("ACE_TRANSACTION_DELETE","Transactie (verwijderen)");
define("ACE_TRANSACTION_FULL","Transactie (alle)");
define("ACE_TRANSACTION_MODIFY","Transactie (veranderen)");
define("ACE_TRANSACTION_READ","Transactie (lezen)");
define("ACE_USER_CREATE","Gebruiker (aanmaken)");
define("ACE_USER_DELETE","Gebruiker (verwijderen)");
define("ACE_USER_FULL","Gebruiker (alle)");
define("ACE_USER_MODIFY","Gebruiker (veranderen)");
define("ACE_USER_READ","Gebruiker (lezen)");
define("ACE_VAT_CREATE","BTW (aanmaken)");
define("ACE_VAT_DELETE","BTW (verwijderen)");
define("ACE_VAT_FULL","BTW (alle)");
define("ACE_VAT_MODIFY","BTW (veranderen)");
define("ACE_VAT_READ","BTW (lezen)");
define("ACE_CUSTOMER_CREATE","Klant (aanmaken)");
define("ACE_CUSTOMER_DELETE","Klant (verwijderen)");
define("ACE_CUSTOMER_FULL","Klant (alle)");
define("ACE_CUSTOMER_MODIFY","Klant (veranderen)");
define("ACE_CUSTOMER_READ","Klant (lezen)");

?>