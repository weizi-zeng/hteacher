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

$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'setting';

switch ($action)
{
	case 'setting':
		$smarty->assign('ur_here', "服务器设置");
		$sql = "select * from ".$ecs->table("sms_server")." where is_active=1 limit 1";
		$row = $db->getRow($sql);
		
		$sql = "select cellphone from ".$ecs->table("admin_user")." where user_id=".$_SESSION["admin_id"];
		$admin_phone = $db->getOne($sql);
		
		$smarty->assign('sms_my_info', $row);
		$smarty->assign('phone', $admin_phone);
		
		assign_query_info();
		$smarty->display('sms_my_info.htm');
		exit;
		
	case 'register':
		//更新数据库数据
		$sms_server_id = empty($_POST["sms_server_id"])?0:intval($_POST["sms_server_id"]);
		$user = trim($_POST["user"]);
		$pass = trim($_POST["pass"]);
		$server = trim($_POST["server"]);
		$port = trim($_POST["port"]);
		$total = empty($_POST["total"])?0:intval($_POST["total"]);
		
		$is_active = empty($_POST["is_active"])?0:intval($_POST["is_active"]);
		
		$phone = trim($_POST["phone"]);
		
		$sql = "update ".$ecs->table("sms_server")." set user='$user',pass='$pass',server='$server',port='$port',is_active='$is_active',total='$total' where sms_server_id=".$sms_server_id;
		$db->query($sql);
		
		$msg = "服务器设置成功！";
		//短信测试
		if($is_active && str_len($phone)>3){
			$sms = new sms();
			$res = $sms->send($phone, "您好，您已开通您的短信服务，感谢您的使用！【磐盛科技】", "", "", $_SESSION["admin_name"]);
			if($res['error']==1){
				$msg .= $res['msg'];
			}else {
				$msg .="请查收短信";
			}
		}
		sys_msg($msg,0,array(),false);
		exit;
	case 'sense':
		$smarty->assign('ur_here', "敏感词汇");
		$sms = new sms();
		$smarty->assign('sense', implode("|", $sms->sense));
		assign_query_info();
		$smarty->display('sms_sense_info.htm');
		exit;
		
	case 'update_sense':
		$sms = new sms();
		$sms->getSmsKeys();
		$sense = implode("|", $sms->sense);
		$sql = "update ".$ecs->table("sms_server")." set sense='".addslashes($sense)."' where sms_server_id=".$sms->sms_server_id;//mysql_like_quote
		$db->query($sql);
		
		$smarty->assign('sense', $sense);
		$smarty->assign('info', "更新成功!");
		
		assign_query_info();
		$smarty->display('sms_sense_info.htm');
		exit;
		
		
	case 'statistics':
		$smarty->assign('ur_here', "短信统计");
		
		$statics = array();
		$sms = new sms();
		
		$statics = array('total'=>$sms->total,'used'=>$sms->used);
		
		$inf = $sms->getBalance();
		$statics["remainder"] = empty($inf[1])?0:intval($inf[1]);
		
		$schools = get_school_list();
		foreach($schools as $s){
			$statics['school'][$s['code']]['name'] = $s['name'];
			$num = $db->getOne("select sum(num) from ".$s['code']."_school.ht_sms ");
			$statics['school'][$s['code']]['num'] = $num?$num:0;
		}
		
		$smarty->assign('statics', $statics);
		assign_query_info();
		$smarty->display('sms_statics_info.htm');
		exit;
		
	case 'record':
		$smarty->assign('ur_here',      "短信发送记录");
		
		$schools = get_school_list();
		$smarty->assign('schools', $schools);
		
		$sms_list = sms_list();
		$smarty->assign('sms_list',    $sms_list['sms_list']);
		
		$smarty->assign('filter',       $sms_list['filter']);
		$smarty->assign('record_count', $sms_list['record_count']);
		$smarty->assign('page_count',   $sms_list['page_count']);
		$smarty->assign('full_page',    1);
		
		assign_query_info();
		$smarty->display('sms_list.htm');
		
		exit;
		
	case 'query':
		$sms_list = sms_list();
		$smarty->assign('sms_list',    $sms_list['sms_list']);
		
		$smarty->assign('filter',       $sms_list['filter']);
		$smarty->assign('record_count', $sms_list['record_count']);
		$smarty->assign('page_count',   $sms_list['page_count']);
		
		make_json_result($smarty->fetch('sms_list.htm'), '', array('filter' => $sms_list['filter'], 'page_count' => $sms_list['page_count']));
		exit;
		
	default:
		die("您访问的页面不存在！");
	exit;
}



/**
 *  返回短信列表数据
 */
function sms_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//关键字
		$filter['school'] = empty($_REQUEST['school']) ? '' : trim($_REQUEST['school']);//学校code
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'sms_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);

		$ex_where = ' WHERE 1 ';
		if ($filter['keywords'])
		{
			$ex_where .= " AND content LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		
		$database = $filter['school']?$filter['school']."_school":"hteacher";
		$table = $database.".ht_sms ";

		$filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $table . $ex_where);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $table . $ex_where .
                " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
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

	$arr = array('sms_list' => $sms_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

	return $arr;
}



?>