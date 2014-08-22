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
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("exam_prj")
		." (code,name,class_code,sdate,edate,closed,created )
		values 
			('".$_REQUEST["code"]."','".$_REQUEST["name"]."','".$_SESSION["class_code"]."',
			'".$_REQUEST["sdate"]."','".$_REQUEST["edate"]."','".$_REQUEST["closed"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["code"]), 'add', 'exam_prj');
		
		make_json_result("添加“".$_REQUEST["code"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("exam_prj")
		." set name='".$_REQUEST["name"]."',
			code='".$_REQUEST["code"]."',
			sdate='".$_REQUEST["sdate"]."',
			edate='".$_REQUEST["edate"]."',
			closed='".$_REQUEST["closed"]."' 
			where prj_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["code"]), 'update', 'exam_prj');
		
		make_json_result("修改“".$id.",".$_REQUEST["code"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['prj_id'])        ? intval($_REQUEST['prj_id'])      : 0;
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
		$filter['code'] = empty($_REQUEST['search_code']) ? '' : trim($_REQUEST['search_code']);//编号
		$filter['name'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'prj_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '15'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['code'])
		{
			$ex_where .= " AND code like '" . mysql_like_quote($filter['code']) ."%'";
		}
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



function insert_datas($exam_prjs_list){

	$sql = "insert into ".$GLOBALS['ecs']->table("exam_prj")
	." (code,name,class_code,subject,
				teacher,exam_prjdate,stime,etime,classroom,created )
			values ";
	;
	foreach ($exam_prjs_list as $k=>$v){
		$sql .= "('".$v["code"]."','".$v["name"]."','".$v["class_code"]."',
		'".$v["subject"]."','".$v["teacher"]."',
		'".$v["exam_prjdate"]."','".$v["stime"]."','".$v["etime"]."',
		'".$v["classroom"]."',
		now())";
		
		if($k<(sizeof($exam_prjs_list)-1)){
			$sql .= ",";
		}
	}
// 	print_r($sql);
	
	$GLOBALS['db']->query($sql);
}


?>