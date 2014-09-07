<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$student = get_student($class_code, $_SESSION["student_code"]);
	$smarty->assign("student", $student);
	$smarty->display('student_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = student_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['student_id'])        ? intval($_REQUEST['student_id'])      : 0;
// 	print_r($_REQUEST);
	if($id>0){//insert
		$sql = "update ".$ecs->table("student")
		." set sexuality='".$_REQUEST["sexuality"]."',
			birthday='".$_REQUEST["birthday"]."',
			national='".$_REQUEST["national"]."',
			id_card='".$_REQUEST["id_card"]."',
			phone='".$_REQUEST["phone"]."',
			qq='".$_REQUEST["qq"]."',
			dorm='".$_REQUEST["dorm"]."',
			email='".$_REQUEST["email"]."',
			address='".$_REQUEST["address"]."',
			guardian_relation='".$_REQUEST["guardian_relation"]."'
			where student_id=".$id;
		
		$db->query($sql);
		admin_log(addslashes($class_code.",".$_SESSION["student_code"]), 'update', $sql);
		make_json_result("修改个人信息成功！");
	}
}

elseif ($_REQUEST['act'] == 'export')
{
	$list = student_list();
	
	$content = "序号,学号,姓名,性别,出生年月,民族,身份证,电话,邮箱,住址,是否已离校,家长,家长电话,与家长关系,创建日期\n";
	
	foreach ($list["rows"] as $k=>$v)
	{
		$sexuality = $v["sexuality"]==1?"男":"女";
		$has_left = $v["has_left"]==1?"是":"否";
		$content .= $v["student_id"] . ",'".$v["code"]. "',".$v["name"]. ",".$sexuality. ",".$v["birthday"]. ",".$v["national"]. ",'".$v["id_card"]. "','".$v["phone"]. "',".$v["email"]. ",".$v["address"]. ",".$has_left. ",".$v["guardian_name"]. ",'".$v["guardian_phone"] . "',".$v["guardian_relation"]. ",".$v["created"] . "\n";
	}
	
	$charset = empty($_POST['charset']) ? 'GBK' : trim($_POST['charset']);
	
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=students_list.csv");
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
function student_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		$filter['guardian_phone'] = empty($_REQUEST['search_guardian_phone']) ? '' : trim($_REQUEST['search_guardian_phone']);//电话
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'student_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."'  ";//AND code='".$_SESSION["student_code"]."'
		if ($filter['keywords'])
		{
			$ex_where .= " AND name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['guardian_phone'])
		{
			$ex_where .= " AND guardian_phone = '" . mysql_like_quote($filter['guardian_phone']) ."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("student") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("student")  . $ex_where .
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