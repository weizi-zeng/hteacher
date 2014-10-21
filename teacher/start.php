<?php
/**
 * ECSHOP 控制台首页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: index.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 框架
/*------------------------------------------------------ */
	/*
	 * 通知通告
		讨论信息
		短信信息
		班级相册
	 */
	//通知通告
	$sql = "select * from ".$ecs->table("notice")." where class_code='".$_SESSION["class_code"]."' order by notice_id desc limit 20";
	$notices = $db->getAll($sql);
	$smarty->assign('notices', $notices);
	
	//意见箱回复
	$sql = "select * from ".$ecs->table("message")." where to_type='admin' and to_='".$_SESSION["admin_id"]."' order by message_id desc limit 20";
	$msg_list = $db->getAll($sql);
	foreach($msg_list as $k=>$msg){
		$msg_list[$k]['from_user'] = get_user_name($msg['from_'], $msg['from_type']);
	}
	$smarty->assign('msg_list', $msg_list);
	
	//短信
	$sql = "select * from ".$ecs->table("sms")." where class_code='".$_SESSION["class_code"]."' order by sms_id desc limit 20";
	$sms = $db->getAll($sql);
	$smarty->assign('sms', $sms);
	
	//讨论信息
	$sql = "select * from ".$ecs->table("forum")." where parent_id=0 and is_active=1 order by forum_id desc limit 20";
	$forums = $db->getAll($sql);
	$smarty->assign('forums', $forums);
	
	//最新图片
	$sql = "select * from ".$ecs->table("album")." where class_code='".$_SESSION["class_code"]."'  order by album_id desc limit 40";
	$albums = $db->getAll($sql);
	$smarty->assign('albums', $albums);
	
    $smarty->display('start.htm');

?>
