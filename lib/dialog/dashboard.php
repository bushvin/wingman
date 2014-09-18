<?php
/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * dashboard.php
 * 
 * dashboard dialog
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//

$sql = "SELECT `a`.`name`, `b`.`id`,`b`.`uid`,`b`.`reference`,DATE_FORMAT(`b`.`date`,'%Y-%m-%d') `date`, DATE_FORMAT(DATE_ADD(`b`.`date`,INTERVAL `b`.`deadline` DAY),'%Y-%m-%d') `duedate`, `b`.`id` `transaction_id`,`b`.`offer_id`
            FROM `##_" .$_SESSION['space']. "_entity` `a`
            LEFT JOIN `##_" .$_SESSION['space']. "_transaction` `b` ON (`a`.`id`=`b`.`entity_id`)
            WHERE `b`.`satisfied`=0 AND `b`.`id`>-1
            AND DATE_ADD(`b`.`date`,INTERVAL `b`.`deadline` DAY)<CURDATE()
            ORDER BY `a`.`name` asc, `duedate` desc;";
$DBO->query($sql);
$customer = "";
$count = 0;
?>
<div class="dashboard">
<?php foreach ($DBO->result("objectlist") as $row) {
    $TRANSACTION = new transaction($row->transaction_id);
    $TRANSACTION->details();
    $OFFER = new offer($row->offer_id);
    $OFFER->details();
    if ($row->name != $customer) { 
        $customer = $row->name;
    ?>
<?php if ($count != 0) {
    echo "</div>"; 
}?>
<div class="customer"><?php echo $row->name; ?></div>
<div class="itemlist">
<?php    } ?>
    <div class="transaction"  onclick="app.goto.transaction(<?php echo $row->id; ?>);">
        <div class="cell uid"><a href="#"><?php echo $row->uid; ?></a></div>
        <div class="cell duedate"><a href="#"><?php echo $row->duedate; ?></a></div>
        <div class="cell reference"><a href="#"><?php echo $row->reference; ?></a></div>
        <div class="cell amount"><a href="#"><?php echo number_format(array_sum($TRANSACTION->get("calculated_vat")),2,APP_DECIMAL_SEPARATOR,APP_THOUSAND_SEPARATOR). " " .$OFFER->get("currency_symbol"); ?></a></div>
    </div>
<?php
    $count ++;
}

?></div>
</div>
