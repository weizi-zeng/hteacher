<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('forum_list.htm');
	exit;
}

if ($_REQUEST['act'] == 'ajax_list')
{
	$list = forum_list();
	make_json($list);
	exit;
}

elseif ($_REQUEST['act'] == 'view')
{
	$id    = !empty($_REQUEST['forum_id'])        ? intval($_REQUEST['forum_id'])      : 0;
	$sql = "select * from ".$ecs->table("forum")." where is_active=1 and forum_id=".$id;
	$row = $db->getRow($sql);
	$smarty->assign("forum_title",$row);
	
	$sql = "select * from ".$ecs->table("forum")." where is_active=1 and parent_id=".$id." order by flow ";
	$rows = $db->getAll($sql);

	$smarty->assign("forum_replys",$rows);
	$smarty->display('forum.htm');
	exit;
}


elseif ($_REQUEST['act'] == 'ajax_save')
{
	$sql = "insert into ".$ecs->table("forum")
	." (title,content,class_code,creator,created )
	values 
		('".$_REQUEST["title"]."','".$_REQUEST["content"]."','".$_SESSION["class_code"]."',
		'".$_SESSION["admin_name"]."',
		now())";
	
	$db->query($sql);
	
	admin_log(addslashes($_REQUEST["title"]), 'add', $sql);
	
	make_json_result("添加“".$_REQUEST["title"]."”成功！");
		
}

elseif ($_REQUEST['act'] == 'reply')
{
	$id    = !empty($_REQUEST['forum_id'])        ? intval($_REQUEST['forum_id'])      : 0;
	if($id!=0){
		//获取楼层
		$sql = "select max(flow) from ".$ecs->table("forum")." where parent_id=".$id;
		$flow = $db->getOne($sql);
		
		$sql = "insert into ".$ecs->table("forum")
		." (parent_id, flow, title,content,class_code,creator,created )
		values 
			('".$id."','".(++$flow)."','".$_REQUEST["title"]."','".$_REQUEST["content"]."','".$_SESSION["class_code"]."',
			'".$_SESSION["admin_name"]."',
			now())";

		$db->query($sql);

		admin_log(addslashes($_REQUEST["content"]), 'add', $sql);

		header("Location: forum.php?act=view&forum_id=$id\n");
		exit;
	}
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['forum_id'])        ? intval($_REQUEST['forum_id'])      : 0;
	$sql = "delete from ".$ecs->table("forum")." where parent_id='".$id."' or forum_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["forum_id"], 'delete', 'forum');
	
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
function forum_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keyword'] = empty($_REQUEST['search_keyword']) ? '' : trim($_REQUEST['search_keyword']);//主体
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keyword'] = json_str_iconv($filter['keyword']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'forum_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE parent_id=0 and class_code='".$_SESSION["class_code"]."' ";
		if ($filter['keyword'])
		{
			$ex_where .= " AND (title like '%" . mysql_like_quote($filter['keyword']) ."%'";
			$ex_where .= " OR content like '%" . mysql_like_quote($filter['keyword']) ."%')";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("forum") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("forum")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

		$filter['keyword'] = stripslashes($filter['keyword']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);
	foreach($list as $key=>$val){
		$list[$key]['setime']     = $val['stime']."-".$val['etime'];
	}

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}




?>