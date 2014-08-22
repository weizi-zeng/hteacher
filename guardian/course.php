<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('course_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = course_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['course_id'])        ? intval($_REQUEST['course_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("course")
		." (semster,weekday,class_code,stage,subject,
			teacher,stime,etime,classroom,created )
		values 
			('".$_REQUEST["semster"]."','".$_REQUEST["weekday"]."','".$_SESSION["class_code"]."',
			'".$_REQUEST["stage"]."',
			'".$_REQUEST["subject"]."','".$_REQUEST["teacher"]."',
			'".$_REQUEST["stime"]."','".$_REQUEST["etime"]."',
			'".$_REQUEST["classroom"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["subject"]), 'add', 'course');
		
		make_json_result("添加成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("course")
		." set semster='".$_REQUEST["semster"]."',
			weekday='".$_REQUEST["weekday"]."',
			stage='".$_REQUEST["stage"]."',
			subject='".$_REQUEST["subject"]."',
			teacher='".$_REQUEST["teacher"]."',
			stime='".$_REQUEST["stime"]."',
			etime='".$_REQUEST["etime"]."',
			classroom='".$_REQUEST["classroom"]."'
			where course_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["subject"]), 'update', 'course');
		
		make_json_result("修改“".$id.",".$_REQUEST["subject"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['course_id'])        ? intval($_REQUEST['course_id'])      : 0;
	$sql = "delete from ".$ecs->table("course")." where course_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["course_id"], 'delete', 'course');
	
	make_json_result("删除成功！");
}

elseif ($_REQUEST['act'] == 'export')
{
	$list = course_list();
	
	$content = "序号,学期,星期,课程,上课起止时间,科目,科教老师,所在教室,创建日期\n";
	
	foreach ($list["rows"] as $k=>$v)
	{
		$content .= $v["course_id"] . ",".$v["semster"]. ",".$v["weekname"]. ",".$v["stage"]. ",".$v["setime"]. ",".$v["subject"]. ",".$v["teacher"]. ",".$v["classroom"]. ",".$v["created"] . "\n";
	}
	
	$charset = empty($_POST['charset']) ? 'UTF8' : trim($_POST['charset']);
	
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=courses_list.csv");
	header("Content-Type: application/unknown");
	die($file);
	
}
/**
 *  返回班级管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function course_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['semster'] = empty($_REQUEST['search_semster']) ? '' : trim($_REQUEST['search_semster']);//名称
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['semster'] = json_str_iconv($filter['semster']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'course_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '15'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['semster'])
		{
			$ex_where .= " AND semster like '" . mysql_like_quote($filter['semster']) ."%'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("course") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("course")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

		$filter['semster'] = stripslashes($filter['semster']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);
	foreach ($list AS $key=>$val)
	{
		$list[$key]['setime']     = $val['stime']."-".$val['etime'];
		$list[$key]['weekname'] = getWeekName($val['weekday']);
	}


	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



?>