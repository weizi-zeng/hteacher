<?php 

define('IN_ECS',true);

$school_code = "hteacher";
$database = $school_code?$school_code:'hteacher';
if($database!="hteacher" && !strpos($database, "_school")){
	$database = $database.'_school';
}

echo $database;
?>