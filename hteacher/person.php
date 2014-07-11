<?php 

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');


if ($_REQUEST['act'] == 'list')
{
// 	$smarty->assign('ur_here',      "班级管理员列表");
	$smarty->display('person_list.htm');
}


elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = person_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['person_id'])        ? intval($_REQUEST['person_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("person")
		." (name,is_active,class_code,iden,id_card,sex,
			bthday,nation,tel,shorttel,email,
			address,unit,has_left,created ) values 
			('".$_REQUEST["name"]."',1,'".$_SESSION["class_code"]."','".$_REQUEST["iden"]."','".$_REQUEST["id_card"]."',
			'".$_REQUEST["sex"]."',
			'".$_REQUEST["bthday"]."','".$_REQUEST["nation"]."',
			'".$_REQUEST["tel"]."','".$_REQUEST["shorttel"]."','".$_REQUEST["email"]."',
			'".$_REQUEST["address"]."','".$_REQUEST["unit"]."','".$_REQUEST["has_left"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'add', 'person');
		
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("person")
		." set name='".$_REQUEST["name"]."',
			iden='".$_REQUEST["iden"]."',
			id_card='".$_REQUEST["id_card"]."',
			sex='".$_REQUEST["sex"]."',
					bthday='".$_REQUEST["bthday"]."',
					nation='".$_REQUEST["nation"]."',
					tel='".$_REQUEST["tel"]."',
					shorttel='".$_REQUEST["shorttel"]."',
					email='".$_REQUEST["email"]."',
					address='".$_REQUEST["address"]."',
					unit='".$_REQUEST["unit"]."',
					has_left='".$_REQUEST["has_left"]."' 
					where person_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'update', 'person');
		
		make_json_result("修改“".$_REQUEST["name"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['person_id'])        ? intval($_REQUEST['person_id'])      : 0;
	$sql = "delete from ".$ecs->table("person")." where person_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["person_id"], 'delete', 'person');
	
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
function person_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//名称
		$filter['tel'] = empty($_REQUEST['tel']) ? '' : trim($_REQUEST['tel']);//电话
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'person_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '15'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['keywords'])
		{
			$ex_where .= " AND name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['tel'])
		{
			$ex_where .= " AND tel = '" . mysql_like_quote($filter['tel']) ."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("person") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("person")  . $ex_where .
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

?>