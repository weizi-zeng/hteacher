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
 * status_id=4 : 监护人
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
	$list[4]["status_name"] = "监护人";

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
            'FROM hteacher.ht_school ORDER BY school_id DESC';
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

	$sql = "select * from ".$GLOBALS['ecs']->table('class') ." order by ".$sort_by." ".$sort_order;
	$row = $GLOBALS['db']->getAll($sql);
	return $row;
}


function get_class_name($class_code)
{
	$sql = "select name from ".$GLOBALS['ecs']->table('class') ." where code='".$class_code."'";
	return $GLOBALS['db']->getOne($sql);
}


?>