<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');


if ($_REQUEST['act'] == 'list')
{
	$download_types = get_download_types();
	$smarty->assign("download_types", $download_types);

	$smarty->display('download_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = download_list();
	make_json($list);
}


elseif ($_REQUEST['act'] == 'download')
{
	$file = !empty($_REQUEST['path'])        ? trim($_REQUEST['path'])      : "";

	if(!strpos($file, "resource")){
		$file = ROOT_PATH.$file;
	}
	
	if(is_file($file)) {
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".basename($file));
		readfile($file);
	}else{
		die("文件不存在！");
	}
	exit;
}



/**
 *  返回班级管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function download_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['download_type'] = empty($_REQUEST['search_download_type']) ? '' : trim($_REQUEST['search_download_type']);//编号
		$filter['name'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		$filter['sdate'] = empty($_REQUEST['search_sdate']) ? '' : trim($_REQUEST['search_sdate']);//起始日期
		$filter['edate'] = empty($_REQUEST['search_edate']) ? '' : trim($_REQUEST['search_edate']);//截止日期

		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'download_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);

		$ex_where = " WHERE d.class_code='".$_SESSION["class_code"]."' ";
		if ($filter['download_type'])
		{
			$ex_where .= " AND d.type = '" . mysql_like_quote($filter['download_type']) ."'";
		}
		if ($filter['name'])
		{
			$ex_where .= " AND d.name like '" . mysql_like_quote($filter['name']) ."%'";
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND d.created >='" . mysql_like_quote($filter['sdate']) ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND d.created <='" . mysql_like_quote($filter['edate']) ."'";
		}
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("download") ." d ". $ex_where;

		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT d.*, s.name as download_type ".
                " FROM " . $GLOBALS['ecs']->table("download")  ." d left join ". $GLOBALS['ecs']->table("download_type")." s on d.type=s.dtype_id ". $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

		// 		echo $sql; echo '<br>';

		$filter['student_code'] = stripslashes($filter['student_code']);
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
