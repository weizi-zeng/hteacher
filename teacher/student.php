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
	
	$begin_flag = false;
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
		
		if($begin_flag===false){
			foreach($line_list as $k=>$v){//学生姓名
				if(strpos($v, "学生姓名")>-1){
					$begin_flag = true;
					$line_number++;
					break;
				}
			}
			if(!$begin_flag){	//定位起始行
				$line_number++;
			}
			continue;
		}
		
		$i=0;
		$arr['code'] = replace_quote($line_list[$i++]);//学号
		$arr['name'] = trim($line_list[$i++]);//学生信息
		$arr['guardian_name'] = trim($line_list[$i++]);//家长信息
		$arr['guardian_phone'] = replace_quote($line_list[$i++]);
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
		
		$sql = "select * from ".$ecs->table("student")." where code='".$_REQUEST["code"]."' and class_code='".$_SESSION["class_code"]."'";
		$s = $db->getRow($sql);
		if($s){
			make_json_result("添加失败！学号“".$_REQUEST["code"]."”已被“".$s["name"]."”同学占用!");
			exit;
		}
		
		$sql = "insert into ".$GLOBALS['ecs']->table("guardian")
		." (name,phone,class_code,student_code,student_name,created)
							values
			   ('".$_REQUEST["guardian_name"]."','".$_REQUEST["guardian_phone"]."','".$_SESSION["class_code"]."','".$_REQUEST["code"]."','".$_REQUEST["name"]."',
				now()
				)  ";
		
		$db->query($sql);
		$guardian_id = $db->insert_id();
		
		
		$sql = "insert into ".$ecs->table("student")
		." (code,name,dorm,sexuality,birthday,
		national,id_card,phone,qq,email,address,class_code,guardian_id,
		guardian_name,guardian_relation,guardian_phone,has_left,
		created )
		values 
			('".$_REQUEST["code"]."','".$_REQUEST["name"]."','".$_REQUEST["dorm"]."','".$_REQUEST["sexuality"]."',
			'".$_REQUEST["birthday"]."','".$_REQUEST["national"]."',
			'".$_REQUEST["id_card"]."','".$_REQUEST["phone"]."','".$_REQUEST["qq"]."','".$_REQUEST["email"]."',
			'".$_REQUEST["address"]."','".$_SESSION["class_code"]."','".$guardian_id."',
			'".$_REQUEST["guardian_name"]."','".$_REQUEST["guardian_relation"]."','".$_REQUEST["guardian_phone"]."','".$_REQUEST["has_left"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'add', $sql);
		
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "select * from ".$ecs->table("student")." where code='".$_REQUEST["code"]."' and class_code='".$_SESSION["class_code"]."' and student_id!=".$id;
		$s = $db->getRow($sql);
		if($s){
			make_json_result("修改失败！学号“".$_REQUEST["code"]."”已被“".$s["name"]."”同学占用!");
			exit;
		}
		
		$sql = "update ".$ecs->table("student")
		." set name='".$_REQUEST["name"]."',
			code='".$_REQUEST["code"]."',
			dorm='".$_REQUEST["dorm"]."',
			sexuality='".$_REQUEST["sexuality"]."',
			birthday='".$_REQUEST["birthday"]."',
			national='".$_REQUEST["national"]."',
			id_card='".$_REQUEST["id_card"]."',
			phone='".$_REQUEST["phone"]."',
			qq='".$_REQUEST["qq"]."',
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

elseif ($_REQUEST['act'] == 'ajax_changePwd')
{
	$id    = !empty($_REQUEST['student_id'])        ? intval($_REQUEST['student_id'])      : 0;
	$new_password    = !empty($_REQUEST['new_password'])        ? trim($_REQUEST['new_password'])      : "";
	
	$sql = "update ".$ecs->table("student")." set password='".md5($new_password)."' where student_id=".$id;
	$db->query($sql);
	admin_log($_REQUEST["student_id"], 'ajax_changePwd', 'student');
	
	//发送短信提醒
	$guardian = $db->getRow("select * from ".$ecs->table("student")." where student_id=".$id);
	require_once(ROOT_PATH . '/includes/cls_sms.php');
	$content = sms_tmp_change_pwd_by_classAdmin($guardian, $new_password, $_SESSION["admin_name"]);
	$sms = new sms();
	$res = $sms->send($guardian["guardian_phone"], $content, $school_code, $guardian["class_code"], $_SESSION["admin_name"]);
	
	if($res["error"]!=0){
		make_json_error("密码更新成功！但是短信发送失败："+$res["msg"]);
		exit;
	}
	make_json_result("密码更新成功！");
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
	
	$charset = empty($_REQUEST['charset']) ? 'GBK' : trim($_REQUEST['charset']);
	
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
		$filter['is_active'] = ($_REQUEST['search_is_active']=='')? '' : intval($_REQUEST['search_is_active']);//是否注册
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'student_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['keywords'])
		{
			$ex_where .= " AND name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['guardian_phone'])
		{
			$ex_where .= " AND guardian_phone = '" . mysql_like_quote($filter['guardian_phone']) ."'";
		}
		if ($_REQUEST['search_is_active']!='')
		{
			$ex_where .= " AND is_active = '" . $filter['is_active'] ."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("student") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("student")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

//  		echo $sql; echo '<br>';

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
	
	//先删后存
	$student_codes = '';
	foreach ($students_list as $k=>$v){
		$student_codes.="'".$v["code"]."'";
		if($k<count($students_list)-1){
			$student_codes.=",";
		}
	}
	
	$GLOBALS['db']->query("delete from ".$GLOBALS['ecs']->table("guardian")." where class_code='".$_SESSION["class_code"]."' and  student_code in (".$student_codes.")");
	$GLOBALS['db']->query("delete from ".$GLOBALS['ecs']->table("student")." where class_code='".$_SESSION["class_code"]."' and code in (".$student_codes.")");
	
	foreach ($students_list as $k=>$v){
		//先删后存
		$sql = "insert into ".$GLOBALS['ecs']->table("guardian")
		." (name,phone,class_code,student_code,student_name,created)
					values
	   ('".$v["guardian_name"]."','".$v["guardian_phone"]."','".$v["class_code"]."','".$v["code"]."','".$v["name"]."',
		now()
		)  ";
		
// 		echo $sql."<br>";
		
		$GLOBALS['db']->query($sql);
		$guardian_id = $GLOBALS['db']->insert_id();
		
		
		$sql = "insert into ".$GLOBALS['ecs']->table("student")
		." (code, name, class_code,guardian_id,
		guardian_name,guardian_phone,
		created)
				values 
		('".$v["code"]."','".$v["name"]."','".$v["class_code"]."',
		'".$guardian_id."',
		'".$v["guardian_name"]."','".$v["guardian_phone"]."',
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