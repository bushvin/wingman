<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.entity.php
 * 
 * showitem.entity dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
$USER = new user($_SESSION["auth::id"]);
$USER->acl("entity");
$USER->acl("currency");
$USER->acl("vat");
/* fix!
if ($USER->get("acl")->entity->read !== true || $USER->get("acl")->currency->list !== true || $USER->get("acl")->vat->list !== true) {
	header('HTTP/1.1 401 Unauthorized');
    exit();
}
*/
$o = new entity($data->data->id);
$o->details();

$sql = "SELECT `id`,`name` FROM `##_" .$_SESSION['space']. "_entity_type` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$ot = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_language` WHERE `deleted`=0 AND `id`>0 ORDER BY `name`;";
$DBO->query($sql);
$lang = $DBO->result("objectlist");

$sql = "SELECT `id`,`reference` FROM `##_".$_SESSION['space']."_offer` WHERE `entity_id`='" .$o->get("id"). "';";
$DBO->query($sql);
$offers = $DBO->result("objectlist");

$sql = "SELECT `id`,CONCAT(`type`,' - ',DATE_FORMAT(`date`,'%Y-%m-%d'),' - ',`uid`,' - ', `reference`) `reference` FROM `##_".$_SESSION['space']."_transaction` WHERE `entity_id`='" .$o->get("id"). "' AND `deleted`='0' ORDER BY `date` desc;";
$DBO->query($sql);
$transactions = $DBO->result("objectlist");

?>
<div class="title"><?php echo ucwords($o->get("id")==-1?ENTITY_NEW_ENTITY:ENTITY_EDIT_ENTITY); ?></div>
<div class="form">
    <div class="column"><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" />
<?php if ( $_entity_name === true ) { ?>
        <div class="row name">
            <div class="caption"><?php echo ucwords(ENTITY_NAME); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" id="name"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_uid === true ) { ?>
        <div class="row uid">
            <div class="caption"><?php echo ucwords(ENTITY_UID); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('uid'); ?>" id="uid"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_salutation === true ) { ?>
        <div class="row salutation">
            <div class="caption"><?php echo ucwords(ENTITY_SALUTATION); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('salutation'); ?>" id="salutation"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_type === true ) { ?>
        <div class="row type">
            <div class="caption"><?php echo ucwords(ENTITY_TYPE); ?></div>
            <div class="value"><select class="select" id="entity_type_id"><?php 
            foreach($ot as $type) {
                echo "<option value=\"" .$type->id. "\"" .($type->id==$o->get("entity_type_id")?" selected":""). ">" .$type->name. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
<?php if ( $_entity_vat_number === true ) { ?>
        <div class="row vat_number">
            <div class="caption"><?php echo ucwords(ENTITY_VAT_NUMBER); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('VAT_number'); ?>" id="VAT_number"/></div>
        </div>
<?php } ?>
        <hr />
<?php if ( $_entity_tel === true ) { ?>
        <div class="row tel">
            <div class="caption"><?php echo ucwords(ENTITY_TEL); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('tel'); ?>" id="tel"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_fax === true ) { ?>
        <div class="row fax">
            <div class="caption"><?php echo ucwords(ENTITY_FAX); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('fax'); ?>" id="fax"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_email === true ) { ?>
        <div class="row email">
            <div class="caption"><?php echo ucwords(ENTITY_EMAIL); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('email'); ?>" id="email"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_www === true ) { ?>
        <div class="row www">
            <div class="caption"><?php echo ucwords(ENTITY_WWW); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('www'); ?>" id="www"/></div>
        </div>
<?php } ?>
<?php if ( $_entity_correspondence_language === true ) { ?>
        <div class="row language">
            <div class="caption"><?php echo ucwords(ENTITY_CORRESPONDENCE_LANGUAGE); ?></div>
            <div class="value"><select class="select" id="correspondence_language_id"><?php 
            foreach($lang as $e) {
                echo "<option value=\"" .$e->id. "\"" .($e->id==$o->get("correspondence_language_id")?" selected":""). ">" .$e->name. "</option>";
            }
            ?></select></div>
        </div>
<?php } ?>
        <hr />
<?php if ( $_entity_notes === true ) { ?>
        <div class="row notes">
            <div class="caption"><?php echo ucwords(ENTITY_NOTES); ?></div>
            <div class="value"><textarea id="notes" class="textarea"><?php echo $o->get('notes'); ?></textarea></div>
        </div>
<?php } ?>
    </div>
    <div class="column addresslist">
    <?php
    $count = 0;
    foreach ($o->get("address") as $address) { 
        ;?>
        <div class="address id<?php echo $count ?>">
<?php        include(FS_DIALOG."add_entity_address.php"); ?>
        </div>
<?php
        $count++;
    }
    ?>
    </div>
    <div class="column offerlist">
        <div class="row offers">
            <div class="caption"><?php echo ucwords(ENTITY_OFFERS); ?> (<?php echo count($offers); ?>)</div>
            <div class="value"><?php
        foreach ($offers as $e) {
            echo "<div onclick=\"app.control._item.hide();app.goto.offer(" .$e->id. ");\">";
            echo "<a href=\"#\">" . $e->reference. "</a>";
            echo "</div>";
        }
            ?></div>
        </div>
    </div>
    <div class="column transactionlist">
        <div class="row transactions">
            <div class="caption"><?php echo ucwords(ENTITY_TRANSACTIONS); ?> (<?php echo count($transactions); ?>)</div>
            <div class="value"><?php
        foreach ($transactions as $e) {
            echo "<div onclick=\"app.control._item.hide();app.goto.transaction(" .$e->id. ");\">";
            echo "<a href=\"#\">" . $e->reference. "</a>";
            echo "</div>";
        }
            ?></div>
        </div>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
    <div class="button new_address" onclick="app.entity.address.add();"><?php echo ucwords(ENTITY_NEW_ADDRESS); ?></div>
</div>
