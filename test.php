<?php 

define('IN_ECS',true);
require (dirname(__FILE__) . '/includes/sinit.php');

$sql = "select * from ".$ecs->table("education")." where type=1 and education_id not in (19) order by education_id ";
$rows = $db->getAll($sql);

foreach ($rows as $row ){
	
	$add_time = gmtime();
	$sql_insert = "INSERT INTO ".$ecs->table('article')."(title, cat_id, is_open, author, ".
	                "keywords, content, add_time,  school_code, class_code, user_id) ".
	            "VALUES ('$row[title]', 13, '$row[is_active]', ".
	                "'$row[author]', '', '$row[content]', ".
	                "'$add_time', '$row[school_code]', '$row[class_code]','$row[user_id]')";
	
	$db->query($sql_insert);
	
	$db->query("update ".$ecs->table("education")." set type=2 where education_id=".$row[education_id]);
}


$sql = "select * from ".$ecs->table("problems")." where type=1 and problems_id not in (8) order by problems_id ";
$rows = $db->getAll($sql);

foreach ($rows as $row ){

	$add_time = gmtime();
	$sql_insert = "INSERT INTO ".$ecs->table('article')."(title, cat_id, is_open, author, ".
	                "keywords, content, add_time,  school_code, class_code, user_id) ".
	            "VALUES ('$row[title]', 14, '$row[is_active]', ".
	                "'$row[author]', '', '$row[content]', ".
	                "'$add_time', '', '','$row[user_id]')";

	$db->query($sql_insert);

	$db->query("update ".$ecs->table("problems")." set type=2 where problems_id=".$row[problems_id]);
}


?>