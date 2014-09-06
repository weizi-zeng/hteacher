<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('teacher_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = teacher_list();
	make_json($list);
}


elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['teacher_id'])        ? intval($_REQUEST['teacher_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("teacher")
		." (name,sexuality,birthday,
		national,id_card,phone,email,address,
		title,is_header,level,class_code,
		has_left,created )
		values 
			('".$_REQUEST["name"]."','".$_REQUEST["sexuality"]."',
			'".$_REQUEST["birthday"]."','".$_REQUEST["national"]."',
			'".$_REQUEST["id_card"]."','".$_REQUEST["phone"]."','".$_REQUEST["email"]."',
			'".$_REQUEST["address"]."','".$_REQUEST["title"]."','".$_REQUEST["is_header"]."',
			'".$_REQUEST["level"]."','".$_SESSION["class_code"]."','".$_REQUEST["has_left"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'add', 'teacher');
		
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("teacher")
		." set name='".$_REQUEST["name"]."',
			sexuality='".$_REQUEST["sexuality"]."',
			birthday='".$_REQUEST["birthday"]."',
			national='".$_REQUEST["national"]."',
			id_card='".$_REQUEST["id_card"]."',
			phone='".$_REQUEST["phone"]."',
			email='".$_REQUEST["email"]."',
			address='".$_REQUEST["address"]."',
			title='".$_REQUEST["title"]."',
			is_header='".$_REQUEST["is_header"]."',
			level='".$_REQUEST["level"]."',
			has_left='".$_REQUEST["has_left"]."' 
			where teacher_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'update', 'teacher');
		
		make_json_result("修改“".$_REQUEST["name"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['teacher_id'])        ? intval($_REQUEST['teacher_id'])      : 0;
	$sql = "delete from ".$ecs->table("teacher")." where teacher_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["teacher_id"], 'delete', 'teacher');
	
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
function teacher_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		$filter['phone'] = empty($_REQUEST['search_phone']) ? '' : trim($_REQUEST['search_phone']);//电话
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'teacher_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['keywords'])
		{
			$ex_where .= " AND name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['phone'])
		{
			$ex_where .= " AND phone = '" . mysql_like_quote($filter['phone']) ."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("teacher") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("teacher")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

		$filter['keywords'] = stripslashes($filter['keywords']);
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