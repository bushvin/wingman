<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * showitem.php
 * 
 * showitem dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
include_once(FS_DIALOG .$data->data->dialog. ".access.default.php" );
include_once(FS_DIALOG .$data->data->dialog. ".access." .$_SESSION["space"]. ".php" );
if ( file_exists(FS_DIALOG .$data->data->dialog. "." .$data->data->view. ".php") ) {
	include_once(FS_DIALOG .$data->data->dialog. "." .$data->data->view. ".php");
} else { ?>
<div class="title"><?php echo ucwords(ITEM_NOT_FOUND); ?></div>
<div class="form"><?php echo ucwords(ITEM_NOT_FOUND_DESC); ?></div>
<div class="buttons">
    <div class="button blue save" onclick="app.control._item.hide()"><?php echo ucwords(CLOSE); ?></div>
</div>
<?php } ?>