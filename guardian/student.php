<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('student_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = student_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'import')
{
	/* 将文件按行读入数组，逐行进行解析 */
	$line_number = 0;
	$students_list = array();
	$data = file($_FILES["importFile"]["tmp_name"]);
	
	$bigan_flag = false;
	foreach ($data AS $line)
	{
		// 转换编码
// 		if (($_POST['charset'] != 'UTF8') && (strpos(strtolower(EC_CHARSET), 'utf') === 0))
// 		{
// 			$line = ecs_iconv($_POST['charset'], 'UTF8', $line);
// 		}
		$line = mb_convert_encoding($line, "UTF-8", "GBK");
		
		// 初始化
		$arr    = array();
		$line_list = explode(",",$line);
		
		if($bigan_flag===false){
			foreach($line_list as $k=>$v){//学生姓名
				if(strpos($v, "学生姓名")>-1){
					$bigan_flag = true;
					$line_number++;
					break;
				}
			}
			if(!$bigan_flag){	//定位起始行
				$line_number++;
			}
			continue;
		}
		
		$i=1;
		$arr['code'] = replace_quote($line_list[$i++]);//学号
		$arr['name'] = trim($line_list[$i++]);//学生信息
		$arr['sexuality'] = trim($line_list[$i++])=="男"?"1":"0";
		$arr['birthday'] = replace_quote($line_list[$i++]);
		$arr['id_card'] = replace_quote($line_list[$i++]);
		$arr['national'] = trim($line_list[$i++]);
		$arr['address'] = trim($line_list[$i++]);
		$arr['phone'] = replace_quote($line_list[$i++]);
		$arr['email'] = trim($line_list[$i++]);
		
		$arr['guardian_name'] = trim($line_list[$i++]);//家长信息
		$arr['guardian_sexuality'] = trim($line_list[$i++])=="男"?"1":"0";
		$arr['guardian_birthday'] = replace_quote($line_list[$i++]);
		$arr['guardian_id_card'] = replace_quote($line_list[$i++]);
		$arr['guardian_phone'] = replace_quote($line_list[$i++]);
		$arr['guardian_email'] = trim($line_list[$i++]);
		$arr['guardian_unit'] = trim($line_list[$i++]);
		$arr['guardian_relation'] = trim($line_list[$i++]);
		
		$arr['class_code'] = $class_code;
		
		$students_list[] = $arr;
	}
	
	insert_datas($students_list);
	
	$smarty->display('student_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['student_id'])        ? intval($_REQUEST['student_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("student")
		." (code,name,sexuality,birthday,
		national,id_card,phone,email,address,class_code,
		guardian_name,guardian_relation,guardian_phone,has_left,
		created )
		values 
			('".$_REQUEST["code"]."','".$_REQUEST["name"]."','".$_REQUEST["sexuality"]."',
			'".$_REQUEST["birthday"]."','".$_REQUEST["national"]."',
			'".$_REQUEST["id_card"]."','".$_REQUEST["phone"]."','".$_REQUEST["email"]."',
			'".$_REQUEST["address"]."','".$_SESSION["class_code"]."',
			'".$_REQUEST["guardian_name"]."','".$_REQUEST["guardian_relation"]."','".$_REQUEST["guardian_phone"]."','".$_REQUEST["has_left"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'add', $sql);
		
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("student")
		." set name='".$_REQUEST["name"]."',
			code='".$_REQUEST["code"]."',
			sexuality='".$_REQUEST["sexuality"]."',
			birthday='".$_REQUEST["birthday"]."',
			national='".$_REQUEST["national"]."',
			id_card='".$_REQUEST["id_card"]."',
			phone='".$_REQUEST["phone"]."',
			email='".$_REQUEST["email"]."',
			address='".$_REQUEST["address"]."',
			guardian_name='".$_REQUEST["guardian_name"]."',
			guardian_relation='".$_REQUEST["guardian_relation"]."',
			guardian_phone='".$_REQUEST["guardian_phone"]."',
			has_left='".$_REQUEST["has_left"]."' 
			where student_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'update', $sql);
		
		make_json_result("修改“".$_REQUEST["name"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['student_id'])        ? intval($_REQUEST['student_id'])      : 0;
	$sql = "delete from ".$ecs->table("student")." where student_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["student_id"], 'delete', 'student');
	
	make_json_result("删除成功！");
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
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' AND code='".$_SESSION["student_code"]."' ";
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
// 	foreach ($list AS $key=>$val)
// 	{
// 		$list[$key]['created']     = local_date($GLOBALS['_CFG']['time_format'], $val['created']);
// 	}


	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



function insert_datas($students_list){
	
	foreach ($students_list as $k=>$v){
		$sql = "insert into ".$GLOBALS['ecs']->table("guardian")
		." (name,sexuality,birthday,national,id_card,phone,email,
		address,unit,relationship,class_code,student_name,created)
					values
	   ('".$v["guardian_name"]."','".$v["guardian_sexuality"]."',
		'".$v["guardian_birthday"]."','".$v["national"]."',
		'".$v["guardian_id_card"]."','".$v["guardian_phone"]."',
		'".$v["guardian_email"]."',
		'".$v["address"]."','".$v["guardian_unit"]."',
		'".$v["guardian_relation"]."','".$v["class_code"]."','".$v["name"]."',
		now()
		)  ";
		
// 		echo $sql."<br>";
		
		$GLOBALS['db']->query($sql);
		$guardian_id = $GLOBALS['db']->insert_id();
		
		
		$sql = "insert into ".$GLOBALS['ecs']->table("student")
		." (code, name,sexuality,birthday,national,id_card,
		phone,email,address,class_code,guardian_id,
		guardian_name,guardian_phone,guardian_relation,
		created)
				values 
		('".$v["code"]."','".$v["name"]."','".$v["sexuality"]."',
		'".$v["birthday"]."','".$v["national"]."',
		'".$v["id_card"]."','".$v["phone"]."',
		'".$v["email"]."',
		'".$v["address"]."','".$v["class_code"]."',
		'".$guardian_id."',
		'".$v["guardian_name"]."','".$v["guardian_phone"]."','".$v["guardian_relation"]."',
		now()
		)  ";
		
// 		echo $sql."<br>";
		
		$GLOBALS['db']->query($sql);
		$student_id = $GLOBALS['db']->insert_id();
		
		
		$sql = "update ".$GLOBALS['ecs']->table("guardian")." set student_id=".$student_id." where guardian_id=".$guardian_id;
		$GLOBALS['db']->query($sql);
		
	}
	
	
	
}
?>