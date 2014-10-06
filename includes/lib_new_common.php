<?php 
if (!defined('IN_ECS'))
{
	die('Hacking attempt');
}


/**
 * status_id=0 : 超级管理员
 * status_id=1 : 学校管理员
 * status_id=2 : 班级管理员
 * status_id=3 : 教师
 * status_id=4 : 家长
 */
function get_status($status_id)
{
	$statuss = get_status_list();

	foreach($statuss as $k=>$v){
		if($v["status_id"]==$status_id)
		{
			return $v["status_name"];
		}
	}

	return "未知";
}


/* 获取角色列表 */
function get_status_list()
{
	$list = array();

	$list[0]["status_id"] = 0;
	$list[0]["status_name"] = "系统管理员";

	$list[1]["status_id"] = 1;
	$list[1]["status_name"] = "学校管理员";

	$list[2]["status_id"] = 2;
	$list[2]["status_name"] = "班级管理员";

	$list[3]["status_id"] = 3;
	$list[3]["status_name"] = "教师";

	$list[4]["status_id"] = 4;
	$list[4]["status_name"] = "家长";

	$list[5]["status_id"] = 5;
	$list[5]["status_name"] = "学生";

	return $list;
}

/**
 * 根据数据库id获取学校信息
 *
 * @access  public
 * @param
 *
 * @return void
 */
function get_school_by_id($id)
{
	$sql = "SELECT * ".
                " FROM hteacher.ht_school " .
                " WHERE school_id= " . $id;

	$school = $GLOBALS['db']->GetRow($sql);
	return $school;
}


/* 获取学校列表 */
function get_school_list()
{
	$list = array();
	$sql  = 'SELECT * '.
            'FROM hteacher.ht_school where removed=0 ORDER BY school_id DESC';
	$list = $GLOBALS['db']->getAll($sql);

	return $list;
}

function get_school_name($school_code)
{
	$sql = "select name from hteacher.ht_school where code='".$school_code."'";
	return $GLOBALS['db']->getOne($sql);
}

function is_only($table, $col, $name, $where='')
{
	$sql = 'SELECT COUNT(*) FROM ' .$table. " WHERE $col = '$name'";
	$sql .= empty($where) ? '' : ' AND ' .$where;

	return ($GLOBALS['db']->getOne($sql) == 0);
}

//根据id获取用户名
function get_admin_by_id($id)
{
	$sql = "SELECT * ".
	                " FROM hteacher.ht_admin_user ".
	                " WHERE user_id= " . $id;
	
	return $GLOBALS['db']->GetRow($sql);
}

/**
*  获取年级列表信息
*
* @access  public
* @param
* @return void
*/
function grade_list($sort_by="", $sort_order="")
{
	$sort_by = $sort_by==""?"grade_id":trim($sort_by);
	$sort_order = $sort_order==""?"desc":trim($sort_order);

	$sql = "select * from ".$GLOBALS['ecs']->table('grade') ." order by ".$sort_by." ".$sort_order;
	$row = $GLOBALS['db']->getAll($sql);
	return $row;
}

/**
*  获取班级列表信息
*
* @access  public
* @param
* @return void
*/
function class_list($sort_by="", $sort_order="")
{
	$sort_by = $sort_by==""?"class_id":trim($sort_by);
	$sort_order = $sort_order==""?"desc":trim($sort_order);

	$sql = "select * from ".$GLOBALS['ecs']->table('class') ." where removed=0 and is_active=1 and has_left=0 order by ".$sort_by." ".$sort_order;
	$row = $GLOBALS['db']->getAll($sql);
	return $row;
}


function get_class_name($class_code)
{
	$sql = "select name from ".$GLOBALS['ecs']->table('class') ." where code='".$class_code."'";
	return $GLOBALS['db']->getOne($sql);
}

function getWeekName($weekday){
	$week = array(
		"星期日",
		"星期一",
		"星期二",
		"星期三",
		"星期四",
		"星期五",
		"星期六"
	);
	return $week[$weekday];
}

/**
* 根据班级代码获取所有学生
*
* @access  public
* @param
*
* @return void
*/
function get_students($class_code)
{
	$sql = "SELECT * ".
                " FROM ".$GLOBALS['ecs']->table("student")." " .
                " WHERE class_code= '" . $class_code."'";
	return $GLOBALS['db']->getAll($sql);
}

function get_student_name($class_code, $student_code)
{
	$sql = "select name from ".$GLOBALS['ecs']->table("student")." where class_code= '" . $class_code."' and code='".$student_code."'";
	return $GLOBALS['db']->getOne($sql);
}

function get_student($class_code, $student_code)
{
	$sql = "select * from ".$GLOBALS['ecs']->table("student")." where class_code= '" . $class_code."' and code='".$student_code."'";
	return $GLOBALS['db']->getRow($sql);
}


function get_guardian($class_code, $student_code)
{
	$sql = "select * from ".$GLOBALS['ecs']->table("guardian")." where class_code= '" . $class_code."' and student_code='".$student_code."'";
	return $GLOBALS['db']->getRow($sql);
}

/**
* 根据班级代码获取所有考试
*
* @access  public
* @param
*
* @return void
*/
function get_exams($class_code)
{
	$sql = "SELECT * ".
                " FROM ".$GLOBALS['ecs']->table("exam")." " .
                " WHERE class_code= '" . $class_code."' order by code";

	return $GLOBALS['db']->getAll($sql);
}

/**
* 根据班级代码获取所有考试
*
* @access  public
* @param
*
* @return void
*/
function get_subjects($class_code)
{
	$sql = "SELECT distinct subject ".
                " FROM ".$GLOBALS['ecs']->table("exam")." " .
                " WHERE class_code= '" . $class_code."'";

	return $GLOBALS['db']->getAll($sql);
}


/**
 * 处理掉双引号
 * Enter description here ...
 */
function replace_quote($data){
	$date_1 = str_replace('"', '', trim($data));
	return str_replace("'", "", $date_1);
}


/**
* 获取这个项目所有的考试科目
* @param unknown_type $exam_name
*/
function get_subjects_by_exam($class_code, $exam_name){
	$sql = "SELECT code, subject ".
	                " FROM ".$GLOBALS['ecs']->table("exam")." " .
	                " WHERE class_code= '" . $class_code."' and prj_code='".$exam_name."' order by subject ";
	
	return $GLOBALS['db']->getAll($sql);
}

function get_scores_by_exam($class_code, $exam_name="", $exam_code="", $student_code="", $order_by="", $exam_subject=""){
	
	$ex_where = " WHERE s.class_code='".$class_code."' ";
	if ($exam_name)
	{
		$ex_where .= " AND  e.prj_code='".$exam_name."'";
	}
	if ($exam_code)
	{
		$ex_where .= " AND  e.code='".$exam_code."'";
	}
	if ($student_code)
	{
		$ex_where .= " AND  s.student_code='".$student_code."'";
	}
	if ($exam_subject)
	{
		$ex_where .= " AND  e.subject='".$exam_subject."'";
	}
	if ($order_by)
	{
		$ex_where .= " order by ".$order_by;
	}
	
	$sql = "SELECT s.score_id, s.exam_code, e.prj_code as prj_code,
			         	e.subject as exam_subject, s.student_code, s.score,
			         	st.name as student_name, s.add_score, s.created ".
	                " FROM " . $GLOBALS['ecs']->table("score")  ." s 
					left join ".$GLOBALS['ecs']->table("exam") ." e on s.exam_code=e.code 
					left join ".$GLOBALS['ecs']->table("student") ." st on s.student_code=st.code
					". $ex_where ;
	
	return $GLOBALS['db']->getAll($sql);
}


/**
* 根据班级代码获取所有值日项目
*
* @access  public
* @param
*
* @return void
*/
function get_duty_items($class_code)
{
	$sql = "SELECT * ".
                " FROM ".$GLOBALS['ecs']->table("duty_item")." " .
                " WHERE class_code= '" . $class_code."'";
	return $GLOBALS['db']->getAll($sql);
}

/**
* 根据班级代码获取所有值日项目
*
* @access  public
* @param
*
* @return void
*/
function get_exam_prjs($class_code)
{
	$sql = "SELECT * ".
                " FROM ".$GLOBALS['ecs']->table("exam_prj")." " .
                " WHERE class_code= '" . $class_code."'";
	return $GLOBALS['db']->getAll($sql);
}

/**
* 根据班级代码获取所有值日项目
*
* @access  public
* @param
*
* @return void
*/
function get_exam_subjects()
{
	$sql = "SELECT * ".
                " FROM hteacher.ht_subject order by subject_id ";
	return $GLOBALS['db']->getAll($sql);
}


/**
 * 获取所有量化数据
 */
function get_dutys($class_code, $student_code, $duty_item="", $stime="", $etime="", $order_by="", $group=""){

	$ex_where = " WHERE d.class_code='".$class_code."' ";
	if ($student_code)
	{
		$ex_where .= " AND  d.student_code='".$student_code."'";
	}
	if ($duty_item)
	{
		$ex_where .= " AND  d.duty_item='".$duty_item."'";
	}
	
	if ($stime)
	{
		$ex_where .= " AND  d.date_>='".$stime."'";
	}
	
	if ($etime)
	{
		$ex_where .= " AND  d.date_<='".$etime."'";
	}
	if ($group)
	{
		$ex_where .= " group by ".$group;
	}
	if ($order_by)
	{
		$ex_where .= " order by ".$order_by;
	}

	$sql = "SELECT student_code, sum(score) as score, date_, desc_ ,created ".
	                " FROM " . $GLOBALS['ecs']->table("duty")  ." d 
					". $ex_where ;
	
// 	echo $sql ;echo '<br>';
	
	return $GLOBALS['db']->getAll($sql);
}



function set_params(){
	global $smarty, $_SESSION;
	//查询条件加载
	//班级所有学生
	$students = get_students($_SESSION["class_code"]);
	$smarty->assign("students", $students);
	
	//考试名称
	$prjs = get_exam_prjs($_SESSION["class_code"]);
	$smarty->assign("prjs", $prjs);
	
	//考试科目
	$subjects = get_exam_subjects();
	$smarty->assign("exam_subjects", $subjects);
}


function getRandStr($length) {
// 	$str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$randString = ''; $len = strlen($str)-1; for($i = 0;$i < $length;$i ++){
		$num = mt_rand(0, $len); $randString .= $str[$num];
	} return $randString ;
}


/**
 * 通过电话号码获取家长的信息
 */
function getGuardianByUsername($username){
	$sql = "show databases like '%_school' ";
	$rows = $GLOBALS['db']->getAll($sql);

	$res = false;
	if(count($rows)>0){
		foreach($rows as $row){
			foreach($row as $s){
				$sql = "select * from ".$s.".ht_student where guardian_phone='".$username."' or guardian_name='".$username."' limit 1";
				$res = $GLOBALS['db']->getRow($sql);
				if($res){
					$res["school_code"] = $s;
					return $res;
				}
			}
		}
	}
	return $res;
}

/**
 * 检验校验码
 * 1、是否正确
 * 2、是否有效
 */
function validateRegCode($regCode){
	$res = array("error"=>0,"msg"=>$regCode);
	$table = "hteacher.ht_license";
	$sql = "select * from ".$table." where license='".$regCode."'";
	$license = $GLOBALS["db"]->getRow($sql);
	if($license){
		if($license["removed"]){
			return array("error"=>1,"msg"=>"您的注册码已经被废弃！");
		}
		if($license["is_active"]){
			return array("error"=>1,"msg"=>"您的注册码已经被使用！");
		}

		$today = date("Y-m-d");
		if($license["sdate"]>$today){
			return array("error"=>1,"msg"=>"您的注册码要到".$license["sdate"]."才能生效！");
		}
		if($license["edate"]<$today){
			return array("error"=>1,"msg"=>"您的注册码在".$license["edate"]."已经失效！");
		}
	}else {
		return array("error"=>1,"msg"=>"您的注册码不正确！");
	}
	return $res;
}

//通过ID获取家长信息
function getGuardianById($id, $school){
	$table = $school.".ht_student";
	$sql = "select * from ".$table." where student_id='".$id."'";
	return $GLOBALS["db"]->getRow($sql);
}

//通过电话号码查找管理员
function getAdminByPhone($phone){
	$sql = "select * from hteacher.ht_admin_user where cellphone='".$phone."'";
	return $GLOBALS["db"]->getRow($sql);
}

?>