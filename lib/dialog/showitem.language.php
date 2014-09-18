<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.language.php
 * 
 * showitem.language dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//


$o = new language($data->data->id);
$o->details();
?><div class="title"><?php echo ucwords(LANGUAGE_EDIT_LANGUAGE); ?></div>
<div class="form">
    <div class="column">
        <div class="row name">
            <div class="caption"><?php echo ucwords(LANGUAGE_NAME); ?><input type="hidden" id="id" value="<?php echo $o->get('id'); ?>" /></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('name'); ?>" placeholder="<?php echo ucwords(LANGUAGE_NAME); ?>" id="name"/></div>
        </div>
        <div class="row short">
            <div class="caption"><?php echo ucwords(LANGUAGE_SHORT); ?></div>
            <div class="value"><input class="input-text" type="text" value="<?php echo $o->get('short'); ?>" placeholder="<?php echo ucwords(LANGUAGE_SHORT); ?>" id="short"/></div>
        </div>
    </div>
</div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.save()"><?php echo ucwords(SAVE); ?></div>
    <div class="button apply" onclick="app.control._item.apply()"><?php echo ucwords(APPLY); ?></div>
</div>
