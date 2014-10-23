<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');


if ($_REQUEST['act'] == 'list')
{
	$resource_types = get_resource_types();
	$smarty->assign("admin_id", $_SESSION['admin_id']);
	$smarty->assign("resource_types", $resource_types);
	$smarty->display('resource_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = resource_list();
	make_json($list);
}


elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['resource_id'])        ? intval($_REQUEST['resource_id'])      : 0;
	if($id==0){
		$sql = "insert into ".$ecs->table("resource")." (type, title, content, class_code, creator, created) 
				values ('".$_REQUEST["rtype_id"]."','".$_REQUEST["title"]."','".$_REQUEST["content"]."','".$class_code."','".$_SESSION['admin_id']."',now())";
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["title"]), 'add', $sql);
		
		make_json_result("添加“".$_REQUEST["title"]."”成功！");
	}
	else 	
	{
		$sql = "update ".$ecs->table("resource")
		." set title='".$_REQUEST["title"]."',
			type='".$_REQUEST["rtype_id"]."',
			content='".$_REQUEST["content"]."'
			where resource_id=".$id;

		$db->query($sql);

		admin_log(addslashes($_REQUEST["title"].$id), 'update', $sql);

		make_json_result("修改“".$_REQUEST["title"]."”成功！");
	}
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$ids    = !empty($_REQUEST['resource_id'])        ? trim($_REQUEST['resource_id'])      : "";
	$ids_tmp = explode(",", $ids);
	foreach($ids_tmp as $id){
		$sql = "select * from ".$ecs->table("resource")." where resource_id = (".$id.")";
		$resource = $db->getRow($sql);
		@unlink($resource["path"]);
		$sql = "delete from ".$ecs->table("resource")." where resource_id = (".$id.")";
		$db->query($sql);
	}

	$db->query($sql);
	admin_log($_REQUEST["resource_id"], 'delete', 'resource');

	make_json_result("删除成功！");
}



/**
 *  返回班级管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function resource_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['resource_type'] = empty($_REQUEST['search_resource_type']) ? '' : trim($_REQUEST['search_resource_type']);//编号
		$filter['name'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		$filter['sdate'] = empty($_REQUEST['search_sdate']) ? '' : trim($_REQUEST['search_sdate']);//起始日期
		$filter['edate'] = empty($_REQUEST['search_edate']) ? '' : trim($_REQUEST['search_edate']);//截止日期

		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'resource_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);

		$ex_where = " WHERE d.class_code='".$_SESSION["class_code"]."' ";
		if ($filter['resource_type'])
		{
			$ex_where .= " AND d.type = '" . mysql_like_quote($filter['resource_type']) ."'";
		}
		if ($filter['name'])
		{
			$ex_where .= " AND d.name like '" . mysql_like_quote($filter['name']) ."%'";
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND d.created >='" . mysql_like_quote($filter['sdate']) ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND d.created <='" . mysql_like_quote($filter['edate']) ."'";
		}
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("resource") ." d ". $ex_where;

		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT d.*, s.name as resource_type ".
                " FROM " . $GLOBALS['ecs']->table("resource")  ." d left join ". $GLOBALS['ecs']->table("resource_type")." s on d.type=s.rtype_id ". $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

		// 		echo $sql; echo '<br>';

		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);
	foreach($list as $k=>$v){
		$list[$k]['creator_name'] = get_user_name($v["creator"],'admin');
	}

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}

?>
