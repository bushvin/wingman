<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * index.php
 * 
 * landing page for the Wingman application
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

include("../lib/global.php");
include_once(FS_LANGUAGE.APP_LANGUAGE."/default.php");

if ( SSL_FORCE === true && !isset($_SERVER['HTTPS'])  ) {
    header("Location: https://" .$_SERVER['HTTP_HOST']."/index.php");
}
if (MAINTENANCE===true) {
?>
<!DOCTYPE HTML>
<html>
    <head manifest="<?php echo WW_ROOT; ?>manifest.appcache">
        <title><?php echo APP_NAME. " v" .APP_VERSION;?></title>
		<link href="<?php echo WW_CSS; ?>/general.css" rel="stylesheet" />
    <body>
        <div class="maintenance"><div><?php echo MAINTENANCE_MSG; ?></div></div>
    </body>
</html><?php
    exit();
}
if ($_SESSION["auth::id"] == -1) {
?><!DOCTYPE HTML>
<html>
    <head manifest="<?php echo WW_ROOT; ?>manifest.appcache">
        <title><?php echo APP_NAME. " v" .APP_VERSION;?> - <?php echo ucwords(LOGIN_PAGE); ?></title>
        <meta charset="utf-8">
		<link href="<?php echo WW_CSS; ?>/general.css" rel="stylesheet" />
		<link href="<?php echo WW_CSS; ?>/login.css" rel="stylesheet" />
		<script src="<?php echo WW_JS; ?>/com/jquery/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo WW_JS; ?>/be/critter/wingman.js" type="text/javascript"></script>
    <body>
        <div class="loginform">
            <div class="title"><?php echo APP_NAME. " v" .APP_VERSION;?></div>
            <form action="#" method="POST" onSubmit="return app.signin(); return false;">
                <div class="username">
                    <div class="caption"><label for="username"><?php echo ucwords(USERNAME); ?></label></div>
                    <div class="text"><input class="input-text" type="text" name="username" id="username" placeholder="<?php echo ucwords(USERNAME); ?>"/></div>
                </div>
                <div class="password">
                    <div class="caption"><label for="password"><?php echo ucwords(PASSWORD); ?></label></div>
                    <div class="text"><input class="input-password" type="password" name="password" id="password" placeholder="<?php echo ucwords(PASSWORD); ?>" /></div>
                </div>
                <div class="error" style="display: none;">
                    <?php echo ucfirst(ERROR_USER_PASSWORD_INCORRECT); ?>
                </div>
                <div class="buttons">
                    <input class="button blue" type="submit" value="<?php echo ucwords(SIGNIN) ?>" />
                </div>
            </form>
        </div>
        <div class="copyright"><span class="blue">Wingman</span> &copy; 2006-<?php echo date("Y"); ?> <a href="mailto:info@critter.be">critter bvba</a>
    </body>
</html><?php
} else {
    $view = new view($_SESSION["auth::id"]);
    $normal_views = $view->access();
    $special_views = array();
    if ( in_array("language",$normal_views)) $special_views[] = "language";
    if ( in_array("user",$normal_views)) $special_views[] = "user";
    if ( in_array("role",$normal_views)) $special_views[] = "role";
    if ( in_array("customer",$normal_views)) $special_views[] = "customer";
    $normal_views = array_diff($normal_views,$special_views);
?><!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo APP_NAME. " v" .APP_VERSION;?></title>
        <meta charset="utf-8">
		<link href="<?php echo WW_CSS; ?>/general.css" rel="stylesheet" />
		<link href="<?php echo WW_CSS; ?>/application.css" rel="stylesheet" />
		<link href="<?php echo WW_CSS; ?>/jquery/themes/smoothness/jquery-ui.css" rel="stylesheet">
		<script src="<?php echo WW_JS; ?>/com/jquery/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo WW_JS; ?>/com/jquery/ui/jquery-ui.js" type="text/javascript"></script>
		<script src="<?php echo WW_JS; ?>/io/github/fgnass/spin.min.js" type="text/javascript"></script>
		<script src="<?php echo WW_JS; ?>/io/github/fgnass/jquery.spin.js" type="text/javascript"></script>
		<script src="<?php echo WW_JS; ?>/be/critter/wingman.js" type="text/javascript"></script>
    <body>
            <div class="actionbar"></div>
            <div class="menu">
                <div class="block">
                    <div class="item dashboard" onclick="app.control.view('dashboard');"><?php echo ucwords(DASHBOARD); ?></div>
                </div>
                <hr />
                <div class="block"><?php 
                $count = 0;
                foreach ($normal_views as $view) { 
                ?>
                    <div class="item <?php echo $view; ?>" onclick="app.control.view('<?php echo $view; ?>');"><?php echo ucwords(constant("VIEW_" .strtoupper($view))); ?></div><?php 
                } ?></div>
                <hr />
<?php
if (count($special_views)>0) { ?>
                <div class="block"><?php 
                $count = 0;
                foreach ($special_views as $view) { 
                ?>
                    <div class="item <?php echo $view; ?>" onclick="app.control.view('<?php echo $view; ?>');"><?php echo ucwords(constant("VIEW_" .strtoupper($view))); ?></div><?php 
                } ?></div>
                <hr />
<?php } ?>
            </div>
            <div class="view">
            </div>
        <div class="copyright"><span class="blue">Wingman</span> &copy; 2006-<?php echo date("Y"); ?> <a href="mailto:info@critter.be">critter bvba</a></div>
        <div class="passwordshadow" style="display: none;"></div>
        <div class="passworddialog" style="display: none;">
            <div class="text">
                <div class="cell input"><input type="password" class="input-password" value="" id="oldpasswd" placeholder="<?php echo ucwords(ENTER_OLD_PASSWORD); ?> " onkeyup="app.verifypassword();" onchange="app.verifypassword();" /></div>
                <div class="cell input"><input type="password" class="input-password wrong" value="" id="newpasswd" placeholder="<?php echo ucwords(ENTER_NEW_PASSWORD); ?>" onkeyup="app.verifypassword();" onchange="app.verifypassword();" /></div>
                <div class="cell input"><input type="password" class="input-password wrong" value="" id="confirmpasswd" placeholder="<?php echo ucwords(CONFIRM_NEW_PASSWORD); ?>"  onkeyup="app.verifypassword();" onchange="app.verifypassword();"  /></div>
                <div class="cell"><?php echo PASSWORD_POLICY; ?>:
                    <ul class="policy">
                        <li class="chars wrong"><?php echo ucfirst(PASSWORD_POLICY_8CHARS);?></li>
                        <li class="alpha wrong"><?php echo ucfirst(PASSWORD_POLICY_ALPHABET);?></li>
                        <li class="numbr wrong"><?php echo ucfirst(PASSWORD_POLICY_NUMBER);?></li>
                        <li class="symbl wrong"><?php echo ucfirst(PASSWORD_POLICY_SYMBOL);?> !@#$%&amp;*()-_+[]:;.,<>/?~</li>
                    </ul>
                </div>
            </div>
            <div class="buttons">
                <div class="button disabled apply blue" onclick="app.changepassword();"><?php echo ucwords(APPLY); ?></div>
                <div class="button cancel" onclick="app.control.hidePasswordDialog();"><?php echo ucwords(CANCEL); ?></div>
            </div>
        </div>
    </body>
</html><?php
}
?>