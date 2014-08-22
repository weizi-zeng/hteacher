<?php 

define('IN_ECS',true);

require(dirname(__FILE__) . '/includes/init.php');

$today = date("Y-m-d");

$sdate = "2014-08-30";


print_r($today>$sdate);



?>