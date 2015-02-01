<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: wangleisvn $
 * $Id: flashplay.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act']== 'list')
{
    /* 检查权限 */
    admin_priv('license_manage');
    
    $smarty->assign('ur_here',      "注册码列表");
    $smarty->assign('action_link',  array('text' => "生成注册码", 'href'=>'license.php?act=create'));
    
    $license_list = license_list();
    $schools = get_school_list();

    $smarty->assign('schools',       $schools);
    $smarty->assign('license_list',       $license_list['license_list']);
    $smarty->assign('filter',       $license_list['filter']);
    $smarty->assign('record_count', $license_list['record_count']);
    $smarty->assign('page_count',   $license_list['page_count']);
    $smarty->assign('full_page',    1);
    
    assign_query_info();
    $smarty->display('license_statistics_list.htm');
}



/*------------------------------------------------------ */
//-- ajax返回注册码列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
	 $license_list = license_list();

	$smarty->assign('license_list',       $license_list['license_list']);
    $smarty->assign('filter',       $license_list['filter']);
    $smarty->assign('record_count', $license_list['record_count']);
    $smarty->assign('page_count',   $license_list['page_count']);

	make_json_result($smarty->fetch('license_statistics_list.htm'), '', array('filter' => $license_list['filter'], 'page_count' => $license_list['page_count']));
}

/*------------------------------------------------------ */
//-- 导出注册码
/*------------------------------------------------------ */
elseif($_REQUEST['act'] == 'export')
{
	/* 检查权限 */
	admin_priv('license_manage');
	
	$list = license_list_export();
	license_export($list);
}

/*------------------------------------------------------ */
//-- 动态加载班级列表
/*------------------------------------------------------ */
elseif($_REQUEST['act'] == 'load_class')
{
	/* 检查权限 */
	admin_priv('license_manage');
	$sql = "select * from  ".$_REQUEST['school_code']."_school.ht_class ";
	$list = $db->getAll($sql);
	make_json_result($list);
}


/**
 *  返回学校管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function license_list()
{
	$result = get_filter();
	if ($result === false)
	{
		
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//注册码
		$filter['sdate'] = empty($_REQUEST['sdate']) ? '' : trim($_REQUEST['sdate']);
		$filter['edate'] = empty($_REQUEST['edate']) ? '' : trim($_REQUEST['edate']);
		$filter['has_pay'] = $_REQUEST['has_pay']==="" ? -10 : intval($_REQUEST['has_pay']);
		$filter['school_code'] = empty($_REQUEST['school_code']) ? '' : trim($_REQUEST['school_code']);
		$filter['class_code'] = empty($_REQUEST['class_code']) ? '' : trim($_REQUEST['class_code']);
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'license_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);

		$ex_where = ' WHERE state<>0 and removed=0 ';
		if ($filter['keywords'])
		{
			$ex_where .= " AND license LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['has_pay']>-10)
		{
			if($filter['has_pay']==0){
				$ex_where .= " AND pay_id =0 ";
			}else {
				$ex_where .= " AND pay_id >0 ";
			}
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND sdate = '" . $filter['sdate'] ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND edate = '" . $filter['edate'] ."'";
		}
		if ($filter['school_code'])
		{
			$ex_where .= " AND school_code = '" . $filter['school_code'] ."_school'";
		}
		if ($filter['class_code'])
		{
			$ex_where .= " AND class_code = '" . $filter['class_code'] ."'";
		}

		$filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('license') . $ex_where);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table('license') . $ex_where .
                " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql ; 
		
		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);
	foreach ($list as $k=>$val){
		if(!$val['school_code']){
			continue;
		}
		$list[$k]['schoo_name'] = get_school_name(str_replace("_school", "", $val['school_code']));
		$list[$k]['class_name'] = get_class_name_global($val['school_code'], $val['class_code']);
		$list[$k]['student_name'] = get_student_name_global($val['school_code'], $val['class_code'], $val['student_id']);
	}

	$arr = array('license_list' => $list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

	return $arr;
}




/**
 * @access  public
 * @param
 *
 * @return void
 */
function license_list_export()
{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//注册码
		$filter['sdate'] = empty($_REQUEST['sdate']) ? '' : trim($_REQUEST['sdate']);//注册码
		$filter['edate'] = empty($_REQUEST['edate']) ? '' : trim($_REQUEST['edate']);//注册码
		$filter['has_pay'] = $_REQUEST['has_pay']==="" ? -10 : intval($_REQUEST['has_pay']);
		$filter['school_code'] = empty($_REQUEST['school_code']) ? '' : trim($_REQUEST['school_code']);
		$filter['class_code'] = empty($_REQUEST['class_code']) ? '' : trim($_REQUEST['class_code']);
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$ex_where = ' WHERE state<>0 and removed=0 ';
		if ($filter['keywords'])
		{
			$ex_where .= " AND license LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND sdate = '" . $filter['sdate'] ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND edate = '" . $filter['edate'] ."'";
		}
		if ($filter['school_code'])
		{
			$ex_where .= " AND school_code = '" . $filter['school_code'] ."_school'";
		}
		if ($filter['class_code'])
		{
			$ex_where .= " AND class_code = '" . $filter['class_code'] ."'";
		}
		if ($filter['has_pay']>-10)
		{
			if($filter['has_pay']==0){
				$ex_where .= " AND pay_id =0 ";
			}else {
				$ex_where .= " AND pay_id >0 ";
			}
		}

		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table('license') . $ex_where .
                " ORDER by license_id ";

	$list = $GLOBALS['db']->getAll($sql);
	return $list;
}



function license_export($license){
	
	$content = "注册码,是否付款,学校,班级,学生,注册时间\n";
	foreach ($license as $k=>$v)
	{
		$content .= $v['license'].',';
		$content .= $v['pay_id']>0?"是,":"否,";
		
		$shool_name = '';
		$class_name = '';
		$student_name = '';
		if($v['school_code']){
			$shool_name = get_school_name(str_replace("_school", "", $v['school_code']));
			if($v['class_code']){
				$class_name = get_class_name_global($v['school_code'], $v['class_code']);
				if($v['student_id']){
					$student_name = get_student_name_global($v['school_code'], $v['class_code'], $v['student_id']);
				}
			}
		}
		$content .= $shool_name.',';
		$content .= $class_name.',';
		$content .= $student_name.',';
		$content .= $v['regtime'] . "\n";
	}
	
	$charset = empty($_POST['charset']) ? 'GBK' : trim($_POST['charset']);//UTF8
	
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=license_list.csv");
	header("Content-Type: application/unknown");
	die($file);
}

?>