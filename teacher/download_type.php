<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('download_type_list.htm');
	exit;
}

if ($_REQUEST['act'] == 'ajax_list')
{
	$list = download_type_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['dtype_id'])        ? intval($_REQUEST['dtype_id'])      : 0;
	
	//判断重复
	$isExit = $db->getRow("select * from ".$ecs->table("download_type")." where name='".$_REQUEST["name"]."' and class_code='".$_SESSION["class_code"]."' and dtype_id!=".$id." limit 1 ");
	if($isExit){
		make_json_error("类型“".$_REQUEST["name"]."”已经存在！ID为：“".$isExit["dtype_id"]."”");
		exit;
	}
	
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("download_type")
		." (name,class_code,created )
		values 
			('".$_REQUEST["name"]."','".$_SESSION["class_code"]."',
			now())";
		
		$db->query($sql);
		
		$id = $db->insert_id();
		
		$dirname = '../resource/data/download/'.$id;
		//添加文件夹
		if (!file_exists($dirname))
		{
			make_dir($dirname);
		}
		
		admin_log(addslashes($_REQUEST["name"]), 'add', 'download_type');
		make_json_result("添加“".$_REQUEST["name"]."”成功！");
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("download_type")
		." set name='".$_REQUEST["name"]."'
			where dtype_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["name"]), 'update', 'download_type');
		
		make_json_result("修改“".$id.",".$_REQUEST["name"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['dtype_id'])        ? intval($_REQUEST['dtype_id'])      : 0;
	
	//如果做了考试安排，则不能删除
	$sql = "select count(1) from ".$ecs->table("download")." where type=".$id;
	if($db->getOne($sql)){
		make_json_error("“".get_download_type_name($id)."”在上传下载中已使用，不能直接删除！");
		exit;
	}
	
	//删除文件夹
	$dirname = '../resource/data/download/'.$id;
	if (file_exists($dirname))
	{
		delsvndir($dirname);
	}
	
	$sql = "delete from ".$ecs->table("download_type")." where dtype_id=".$id;
	$db->query($sql);
	
	admin_log($_REQUEST["dtype_id"], 'delete', 'download_type');
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
function download_type_list()
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

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'dtype_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['name'])
		{
			$ex_where .= " AND name like '" . mysql_like_quote($filter['name']) ."%'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("download_type") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("download_type")  . $ex_where .
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