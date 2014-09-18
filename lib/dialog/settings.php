<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * settings.php
 * 
 * settings dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
$user = new user($_SESSION["auth::id"]);
?><div class="dialog settings">
    <div class="overview">
        <div class="name">
            <div class="cell property"><?php echo ucwords(USER_NAME); ?></div>
            <div class="cell value"><?php echo $user->get('name'); ?></div>
        </div>
        <div class="customer">
            <div class="cell property"><?php echo ucwords(USER_CUSTOMER); ?></div>
            <div class="cell value"><?php echo $user->get('customer'); ?></div>
        </div>
        <div class="login">
            <div class="cell property"><?php echo ucwords(USER_LOGIN); ?></div>
            <div class="cell value"><?php echo $user->get('login'); ?></div>
        </div>
        <div class="email">
            <div class="cell property"><?php echo ucwords(USER_EMAIL); ?></div>
            <div class="cell value"><?php echo $user->get('email'); ?></div>
        </div>
        <div class="language">
            <div class="cell property"><?php echo ucwords(USER_LANGUAGE); ?></div>
            <div class="cell value"><?php echo $user->get('language'); ?></div>
        </div>
        <div class="locale">
            <div class="cell property"><?php echo ucwords(USER_LOCALE); ?></div>
            <div class="cell value"><?php echo $user->get('locale'); ?></div>
        </div>
        <div class="showitems">
            <div class="cell property"><?php echo ucwords(SHOW_ITEMCOUNT); ?></div>
            <div class="cell value"><a class="show20" href="javascript:void(0)" >20</a> - <a class="show50" href="javascript:void(0)" >50</a> - <a class="show100" href="javascript:void(0)" >100</a></div>
        </div>
    </div>
    <div class="actions">
        <div class="button passwd" onclick="app.control.hideSettingsDialog(); app.control.showPasswordDialog();"><?php echo ucwords(CHANGE_PASSWORD); ?></div>
        <div class="button signout" onclick="app.signout();"><?php echo ucwords(SIGNOUT); ?></div>
    </div>
</div>
<script>
$('.dialog.settings .showitems a[class^=show]').click(function() {
            $('.dialog.settings .showitems a[class^=show]').removeClass('enabled');
            $('.dialog.settings .showitems .show'+$(this).html()).addClass('enabled');
            app.cookie.set('settings:showitems',$(this).html());
            app.control.hideSettingsDialog();
            app.control.redrawView();
            });
$('.dialog.settings .showitems .show'+app.cookie.get('settings:showitems')).addClass('enabled')
</script>