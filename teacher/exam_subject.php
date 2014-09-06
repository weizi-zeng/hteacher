<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$table = "hteacher.ht_subject";
if ($_REQUEST['act'] == 'list')
{
	$smarty->display('exam_subject_list.htm');
	exit;
}

if ($_REQUEST['act'] == 'ajax_list')
{
	$list = exam_subject_list($table);
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['subject_id'])        ? intval($_REQUEST['subject_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$table
		." (subject,user_id,created )
		values 
			('".$_REQUEST["subject"]."','".$_SESSION["admin_id"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["subject"]), 'add', 'exam_subject');
		
		make_json_result("添加“".$_REQUEST["subject"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$table
		." set subject='".$_REQUEST["subject"]."'
			where subject_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["subject"]), 'update', 'exam_subject');
		
		make_json_result("修改“".$id.",".$_REQUEST["subject"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['subject_id'])        ? intval($_REQUEST['subject_id'])      : 0;
	$sql = "delete from ".$table." where subject_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["subject_id"], 'delete', 'exam_subject');
	
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
function exam_subject_list($table)
{
	$result = get_filter();
	if ($result === false)
	{
		$filter['sort']    = empty($_REQUEST['sort'])    ? 'subject_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$sql = "SELECT COUNT(*) FROM " . $table;
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $table .
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

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}


?>