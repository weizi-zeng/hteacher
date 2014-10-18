<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('exam_prj_list.htm');
	exit;
}

if ($_REQUEST['act'] == 'ajax_list')
{
	$list = exam_prj_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['prj_id'])        ? intval($_REQUEST['prj_id'])      : 0;
	
	//判断重复
	$isExit = $db->getRow("select * from ".$ecs->table("exam_prj")." where name='".$_REQUEST["name"]."' and class_code='".$_SESSION["class_code"]."' and prj_id!=".$id." limit 1 ");
	if($isExit){
		make_json_error("考试名称“".$_REQUEST["name"]."”已经存在！ID为：“".$isExit["prj_id"]."”");
		exit;
	}
	
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("exam_prj")
		." (name,class_code,created )
		values 
			('".$_REQUEST["name"]."','".$_SESSION["class_code"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'add', 'exam_prj');
		
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("exam_prj")
		." set name='".$_REQUEST["name"]."'
			where prj_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'update', 'exam_prj');
		
		make_json_result("修改“".$id.",".$_REQUEST["name"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['prj_id'])        ? intval($_REQUEST['prj_id'])      : 0;
	
	//如果做了考试安排，则不能删除
	$subjects = get_subjects($class_code, $id);
	if(count($subjects)>0){
		make_json_error("《".get_exam_prj_name($id)."》已经做了考试安排，不能直接删除！");
		exit;
	}
	
	$sql = "select count(1) from ".$ecs->table("score")." where prj_id=".$id;
	if($db->getOne($sql)){
		make_json_error("《".get_exam_prj_name($id)."》已经在成绩录入中已使用，不能直接删除！");
		exit;
	}
	
	$sql = "delete from ".$ecs->table("exam_prj")." where prj_id=".$id;
	$db->query($sql);
	
	admin_log($_REQUEST["prj_id"], 'delete', 'exam_prj');
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
function exam_prj_list()
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

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'prj_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['name'])
		{
			$ex_where .= " AND name like '" . mysql_like_quote($filter['name']) ."%'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("exam_prj") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("exam_prj")  . $ex_where .
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