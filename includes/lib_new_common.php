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

function get_class_name_global($db, $class_code)
{
	$sql = "select name from ".$db.".ht_class where code='".$class_code."'";
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
function get_students($class_code, $orderby='')
{
	$sql = "SELECT * ".
                " FROM ".$GLOBALS['ecs']->table("student")." " .
                " WHERE class_code= '" . $class_code."'";
	if($orderby){
		$sql .= ' order by '.$orderby;
	}
	return $GLOBALS['db']->getAll($sql);
}

function get_student_name($class_code, $student_code)
{
	$sql = "select name from ".$GLOBALS['ecs']->table("student")." where class_code= '" . $class_code."' and code='".$student_code."'";
	return $GLOBALS['db']->getOne($sql);
}

function get_student_name_global($db, $class_code, $student_id)
{
	$sql = "select name from ".$db.".ht_student where class_code= '" . $class_code."' and student_id='".$student_id."'";
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

function get_guardians($class_code)
{
	$sql = "select * from ".$GLOBALS['ecs']->table("guardian")." where class_code= '" . $class_code."'";
	return $GLOBALS['db']->getAll($sql);
}

function get_class_admins($class_code){
	$sql = "select * from hteacher.ht_admin_user where class_code= '" . $class_code."'";
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
function get_subjects($class_code, $prj_id='')
{
	$sql = "SELECT distinct subject ".
                " FROM ".$GLOBALS['ecs']->table("exam")." " .
                " WHERE class_code= '" . $class_code."'";
	if($prj_id){
		$sql.=" AND prj_id='$prj_id'";
	}
// 	echo $sql;
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


function get_scores_by_exam($class_code, $prj_id="", $student_code="", $exam_subject="", $order_by=""){
	
	$ex_where = " WHERE s.class_code='".$class_code."' ";
	if ($prj_id)
	{
		$ex_where .= " AND  s.prj_id='".$prj_id."'";
	}
	if ($student_code)
	{
		$ex_where .= " AND  s.student_code='".$student_code."'";
	}
	if ($exam_subject)
	{
		$ex_where .= " AND  s.subject='".$exam_subject."'";
	}
	if ($order_by)
	{
		$ex_where .= " order by ".$order_by;
	}
	
	$sql = "SELECT s.*, st.name as student_name , prj.name as prj_name ".
	                " FROM " . $GLOBALS['ecs']->table("score")  ." s 
	                left join ".$GLOBALS['ecs']->table("exam_prj")." prj on s.prj_id=prj.prj_id 
					left join ".$GLOBALS['ecs']->table("student") ." st on s.student_code=st.code and st.class_code='".$class_code."'
 					". $ex_where ;
	
	return $GLOBALS['db']->getAll($sql);
}

/***
 * 获取年级排名数据
 */
function get_grade_ranks($class_code, $prj_id="", $student_code="", $order_by=""){
	$ex_where = " WHERE s.class_code='".$class_code."' ";
	if ($prj_id)
	{
		$ex_where .= " AND  s.prj_id='".$prj_id."'";
	}
	if ($student_code)
	{
		$ex_where .= " AND  s.student_code='".$student_code."'";
	}
	if ($order_by)
	{
		$ex_where .= " order by ".$order_by;
	}
	$sql = "SELECT s.*, st.name as student_name , prj.name as prj_name ".
		                " FROM " . $GLOBALS['ecs']->table("grade_rank")  ." s 
		                left join ".$GLOBALS['ecs']->table("exam_prj")." prj on s.prj_id=prj.prj_id 
						left join ".$GLOBALS['ecs']->table("student") ." st on s.student_code=st.code and st.class_code='".$class_code."'
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
* 根据班级代码获取所有考试名称
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
                " WHERE class_code= '" . $class_code."' ";
	return $GLOBALS['db']->getAll($sql);
}

/**
* 根据ID获取考试名称
*
* @access  public
* @param
*
* @return void
*/
function get_exam_prj_name($prj_id)
{
	$sql = "SELECT name ".
                " FROM ".$GLOBALS['ecs']->table("exam_prj")." " .
                " WHERE prj_id= '" . $prj_id."'";
	return $GLOBALS['db']->getOne($sql);
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
                " FROM ".$GLOBALS['ecs']->table("subject")." where class_code='".$_SESSION['class_code']."' order by subject_id ";
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
		if($license["state"]>0){
			return array("error"=>1,"msg"=>"您的注册码已经被使用！");
		}
		if($license["state"]<0){
			return array("error"=>1,"msg"=>"您的注册码已经失效！");
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


function get_user_name($id, $type='guardian'){
	if($type=='guardian'){
		$sql = "select * from ".$GLOBALS["ecs"]->table("guardian")." where class_code='".$_SESSION['class_code']."' and guardian_id=".$id;
		$g = $GLOBALS["db"]->getRow($sql);
		$name = $g['student_name'].'——'.$g['name']."(家长)";
		return $name;
	}else {
		$sql = "select user_name from hteacher.ht_admin_user where user_id=".$id;
		return $GLOBALS["db"]->getOne($sql);
	}
}

function get_album_types(){
	$sql = "select * from ".$GLOBALS["ecs"]->table("album_type")." where class_code='".$_SESSION['class_code']."' and removed=0";
	return $GLOBALS["db"]->getAll($sql);
}

function get_download_types(){
	$sql = "select * from ".$GLOBALS["ecs"]->table("download_type")." where class_code='".$_SESSION['class_code']."' and removed=0";
	return $GLOBALS["db"]->getAll($sql);
}

function get_resource_types(){
	$sql = "select * from ".$GLOBALS["ecs"]->table("resource_type")." where class_code='".$_SESSION['class_code']."' and removed=0";
	return $GLOBALS["db"]->getAll($sql);
}

function get_album_type_name($id){
	$sql = "select name from ".$GLOBALS["ecs"]->table("album_type")." where atype_id=".$id;
	return $GLOBALS["db"]->getOne($sql);
}

function get_download_type_name($id){
	$sql = "select name from ".$GLOBALS["ecs"]->table("download_type")." where dtype_id=".$id;
	return $GLOBALS["db"]->getOne($sql);
}

function get_resource_type_name($id){
	$sql = "select name from ".$GLOBALS["ecs"]->table("resource_type")." where rtype_id=".$id;
	return $GLOBALS["db"]->getOne($sql);
}
/**
*  生成指定目录不重名的文件名
*
* @access  public
* @param   string      $dir        要检查是否有同名文件的目录
*
* @return  string      文件名
*/
function unique_name($dir)
{
	$filename = '';
	while (empty($filename))
	{
		$filename = random_filename();
		if (file_exists($dir . $filename . '.jpg') || file_exists($dir . $filename . '.gif') || file_exists($dir . $filename . '.png'))
		{
			$filename = '';
		}
	}

	return $filename;
}

/**
* 生成随机的数字串
*
* @author: weber liu
* @return string
*/
function random_filename()
{
	$str = '';
	for($i = 0; $i < 9; $i++)
	{
		$str .= mt_rand(0, 9);
	}
	return gmtime() . $str;
}

function get_prefix($filename){
	$str = explode(".", $filename);
	if(count($str)>1){
		return $str[count($str)-1];
	}
	return "uk";
}

function delsvndir($svndir){
	//先删除目录下的文件：
	$dh=opendir($svndir);
	while($file=readdir($dh)){
		if($file!="."&&$file!=".."){
			$fullpath=$svndir."/".$file;
			if(is_dir($fullpath)){
				delsvndir($fullpath);
			}else{
				unlink($fullpath);
			}
		}
	}
	closedir($dh);
	//删除目录文件夹
	if(rmdir($svndir)){
		return  true;
	}else{
		return false;
	}
}

function get_article_syscat(){
	$sql = "select * from " .$GLOBALS['ecs']->table('article_cat'). "  where parent_id=0 and cat_id!=1 ";
	return $GLOBALS['db']->getAll($sql);
}

function get_article_by_cat($cat_id=0, $limit=0, $is_important=-1){
	$sql = "select * from " .$GLOBALS['ecs']->table('article'). "  where is_open=1  ";
	if($cat_id>0){
		$sql .= " and cat_id=".$cat_id." ";
	}
	if($is_important>-1){
		$sql .= " and is_important=".$is_important." ";
	}
	$sql .= " order by article_id desc ";
	if($limit>0){
		$sql .= " limit ".$limit;
	}
	
// 	echo $sql;
	
	$rows = $GLOBALS['db']->getAll($sql);
	$arr = array();
	foreach ($rows as $row)
	{
		$row['date'] = local_date('Y-m-d', $row['add_time']);
		$row['add_time'] = local_date('Y-m-d H:i:s', $row['add_time']);
		$row['alt'] = $row['title'];
		$row['short_title'] = sub_str($row['title'], 13);
		$row['middle_title'] = sub_str($row['title'], 16);
		
	    $arr[] = $row;
	}
	return $arr;
}



/* 获得文章列表 */
function get_articles_list($cat_id=0, $page_size=20, $is_important=-1, $keyword='', $sort_by='a.article_id', $sort_order='DESC')
{
		$filter = array();
		$filter['keyword']    = $keyword;
		$filter['cat_id'] = $cat_id;
		$filter['sort_by']    = $sort_by;
		$filter['sort_order'] = $sort_order;
		$filter['page_size']	= $page_size;

		$where = '';
		if (!empty($filter['keyword']))
		{
			$where = " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
		}
		if ($filter['cat_id'])
		{
			$where .= " AND a." . get_article_children($filter['cat_id']);
		}
		if($is_important>-1){
			$where .= " AND a.is_important=".$is_important." ";
		}
		
		/* 文章总数 */
		$sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('article'). ' AS a '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('article_cat'). ' AS ac ON ac.cat_id = a.cat_id '.
               'WHERE is_open=1  ' .$where;
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		$filter = page_and_size_new($filter);

		/* 获取文章数据 */
		$sql = 'SELECT a.* , ac.cat_name '.
               'FROM ' .$GLOBALS['ecs']->table('article'). ' AS a '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('article_cat'). ' AS ac ON ac.cat_id = a.cat_id '.
               'WHERE is_open=1  ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

		$filter['keyword'] = stripslashes($filter['keyword']);
		
	$arr = array();
	$res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

	while ($rows = $GLOBALS['db']->fetchRow($res))
	{
		$rows['date'] = local_date('Y-m-d', $rows['add_time']);
		$rows['add_time'] = local_date('Y-m-d H:i:s', $rows['add_time']);
		$rows['alt'] = $rows['title'];
		$rows['short_title'] = sub_str($rows['title'], 13);
		$rows['middle_title'] = sub_str($$rows['title'], 16);
		$arr[] = $rows;
	}
	return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}



/**
 * 分页的信息加入条件的数组
 *
 * @access  public
 * @return  array
 */
function page_and_size_new($filter)
{
	if(empty($filter['page_size'])){
		if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
		{
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
		{
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		}
		else
		{
			$filter['page_size'] = 25;
		}
	}

	/* 每页显示 */
	$filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

	/* page 总数 */
	$filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;

	/* 边界处理 */
	if ($filter['page'] > $filter['page_count'])
	{
		$filter['page'] = $filter['page_count'];
	}

	$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];

	return $filter;
}


?>