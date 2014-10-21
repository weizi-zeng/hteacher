<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$album_types = get_album_types();
	$smarty->assign("album_types", $album_types);
	$smarty->display('album_show_type.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'show')
{
	$album_type = empty($_REQUEST['type']) ? '' : trim($_REQUEST['type']);//编号
	$list = album_list($album_type, $class_code);
	
	$smarty->assign("albums", $list);
	$smarty->display('album_show.htm');
	exit;
}

function album_list($album_type, $class_code){
	$sql = "SELECT *  FROM " . $GLOBALS['ecs']->table("album")  ." where type='".$album_type."' and class_code='".$class_code."' ORDER by sort ";
	return $GLOBALS['db']->getAll($sql);
}


?>