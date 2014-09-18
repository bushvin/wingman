<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.vat.php
 * 
 * showitem.vat dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//


$o = new vat($data->data->id);
$o->details();
?><div class="title"><?php echo ucwords($o->get("id")==-1?VAT_NEW_VAT:VAT_EDIT_VAT); ?></div>
<div class="form">
    <div class="column">
        <div class="row name">
            <div class="caption"><?php echo ucwords(VAT_NAME); ?><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" /></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" placeholder="<?php echo ucwords(VAT_NAME); ?>" id="name"/></div>
        </div>
        <div class="row type">
            <div class="caption"><?php echo ucwords(VAT_TYPE); ?></div>
            <div class="value"><select class="select" id="type">
                            <option value="relative"<?php echo ($o->get('type')=="relative"?" selected":""); ?>><?php echo ucwords(VAT_RELATIVE); ?></option>
                            <option value="fixed"<?php echo ($o->get('type')=="fixed"?" selected":""); ?>><?php echo ucwords(VAT_FIXED); ?></option>
                        </select></div>
        </div>
        <div class="row amount">
            <div class="caption"><?php echo ucwords(VAT_AMOUNT); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('amount'); ?>" placeholder="<?php echo ucwords(VAT_AMOUNT); ?>" id="amount"/></div>
        </div>
        <div class="row description">
            <div class="caption"><?php echo ucwords(VAT_DESCRIPTION); ?></div>
            <div class="value"><textarea id="description" class="textarea" placeholder="<?php echo ucwords(VAT_DESCRIPTION); ?>"><?php echo $o->get('description'); ?></textarea></div>
        </div>
        <div class="row remark">
            <div class="caption"><?php echo ucwords(VAT_REMARK); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('remark'); ?>" placeholder="<?php echo ucwords(VAT_REMARK); ?>" id="remark"/></div>
        </div>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
</div>
