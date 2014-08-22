<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$file = !empty($_REQUEST['path'])        ? trim($_REQUEST['path'])      : "";
$file = ROOT_PATH.$file;

if(is_file($file)) {
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".basename($file));
	readfile($file);
	exit;
}else{
	echo "文件不存在！";
	exit;
}

?>
