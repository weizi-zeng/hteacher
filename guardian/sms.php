<?php

/**
 * ECSHOP 短信模块 之 控制器
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: yehuaixiao $
 * $Id: sms.php 17155 2010-05-06 06:29:05Z yehuaixiao $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . '/includes/cls_sms.php');

$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'def';

switch ($action)
{
	case 'def':
		$sql = "select * from ".$ecs->table("student")." where is_active=1 ";//and license!=''
		$students = $db->getAll($sql);
		
		$smarty->assign("students", $students);
		$smarty->display('sms_def.htm');
		exit;
		
	case 'send':
		$phones = trim($_POST["phones"]);
		$content = trim($_POST["content"]);
		$copy = trim($_POST["copy"]);
		
		$result = array("error"=>1,"msg"=>"您选择的监护人的电话号码全部为空");
		if(str_len($phones)>4){
			if($copy){
				$admin = get_admin_by_id($_SESSION["admin_id"]);
				if(is_moblie($admin["cellphone"])){
					$phones .= ",".$admin["cellphone"];
				}
			}
			
			$sms = new sms();
			$result = $sms->send($phones, $content, $school_code, $class_code, $_SESSION["admin_name"]);
		}
		
		make_json($result);
		exit;
		;
	case 'record':
		$smarty->display('sms_list.htm');
		exit;
		
	case 'ajax_list':
		$list = sms_list($class_code, $_SESSION["phone"]);
		make_json($list);
		exit;
		
	default:
		die("您访问的页面不存在！");
	exit;
}



/**
 *  返回短信列表数据
 */
function sms_list($class_code, $phone)
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['search_keyword']) ? '' : trim($_REQUEST['search_keyword']);//关键字
		$filter['phones'] = empty($_REQUEST['search_phones']) ? '' : trim($_REQUEST['search_phones']);//学校code
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'sms_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '20'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$class_code."' and phones like '%$phone%'";
		if ($filter['keywords'])
		{
			$ex_where .= " AND content LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		
		$filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS["ecs"]->table("sms") . $ex_where);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS["ecs"]->table("sms") . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$sms_list = $GLOBALS['db']->getAll($sql);

	$arr = array('rows' => $sms_list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



?>