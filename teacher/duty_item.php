<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('duty_item_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = duty_item_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['duty_item_id'])        ? intval($_REQUEST['duty_item_id'])      : 0;
	//检查item是否唯一
	$sql = "select * from ".$ecs->table("duty_item")." where class_code='".$_SESSION["class_code"]."' and name='".$_REQUEST["name"]."' and duty_item_id!=".$id;
	if($db->getRow($sql)){
		make_json_result("“".$_REQUEST["name"]."”已经存在！添加失败！");
		exit;
	}
	
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("duty_item")
		." (name, score, class_code, created)
		values 
			('".$_REQUEST["name"]."','".$_REQUEST["score"]."','".$_SESSION["class_code"]."', now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'add', $sql);
		
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("duty_item")
		." set name='".$_REQUEST["name"]."',
			score='".$_REQUEST["score"]."'
			where duty_item_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'update', $sql);
		
		make_json_result("修改“".$id.",".$_REQUEST["name"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['duty_item_id'])        ? intval($_REQUEST['duty_item_id'])      : 0;
	$sql = "delete from ".$ecs->table("duty_item")." where duty_item_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["duty_item_id"], 'delete', 'duty_item');
	
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
function duty_item_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['name'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'duty_item_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['name'])
		{
			$ex_where .= " AND name like '" . mysql_like_quote($filter['name']) ."%'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("duty_item") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("duty_item")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

		$filter['name'] = stripslashes($filter['name']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



?>