<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * get_options.php
 * 
 * get_options dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

if ( file_exists(FS_DIALOG .$data->data->dialog. "." .$data->data->view. ".php") ) {
	include_once(FS_DIALOG .$data->data->dialog. "." .$data->data->view. ".php");
} else { ?>
<option><?php echo ucwords(ITEM_NOT_FOUND); ?></option>
<?php } 

?>