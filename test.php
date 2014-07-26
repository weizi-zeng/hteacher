<?php 

define('IN_ECS',true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . '/includes/cls_sms.php');

$sms = new sms();

$re = $sms->getBalance();

print_r($re);

?>