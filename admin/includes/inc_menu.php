<?php

/**
 * ECSHOP 管理中心菜单数组
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: inc_menu.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/*
教师：  
学籍管理，成绩管理，量化，
班级公告（班级相册，班级动态，通知通告）
短信平台，讨论区（类论坛，限制权限），
基础信息维护（花名册，支持csv导入）。

系统管理：
token注册码，
系统管理员（可以生成学校，发布广告，生成学校管理员）
学校管理员（可以生成班级，班主任）
班主任


学校管理
	学校列表
	添加学校
	学校管理员列表
	添加学校管理员

所有系统管理员管理
	超级系统管理员列表
	添加超级系统管理员

系统建设性意见
	班主任意见
	监护人意见
	外围区意见箱
	在线调查
	
日志系统
	管理员日志
	
短信管理
	短信服务器配置
	短信统计
	发送记录	
	
系统支持
	说明文档
	
*/

//系统管理
$modules['08_schools']['03_school_list']             = 'school.php?act=list';
$modules['08_schools']['04_school_add']              = 'school.php?act=add';
// $modules['08_schools']['05_class_list']         = 'user_rank.php?act=list';
// $modules['08_schools']['06_class_add']         = 'integrate.php?act=list';
$modules['08_schools']['07_schoolAdmin_list']            = 'schoolAdmin.php?act=list';
$modules['08_schools']['08_schoolAdmin_add']           = 'schoolAdmin.php?act=add';
// $modules['08_schools']['09_classAdmin_list']    = 'user_account_manage.php?act=list';
// $modules['08_schools']['10_classAdmin_add']    = 'user_account_manage.php?act=list';

$modules['09_advice']['02_schoolAdmin_msg']           = 'user_msg.php?act=list_school';
$modules['09_advice']['03_classAdmin_msg']           = 'user_msg.php?act=list_class';
$modules['09_advice']['04_customer_msg']    = 'user_msg.php?act=list_customer';
$modules['09_advice']['05_other_msg']    = 'user_msg.php?act=list_outer';

//系统相关
$modules['09_problems']['01_problems_list']    = 'problems.php?act=list';
$modules['09_problems']['02_problems_add']    = 'problems.php?act=add';

$modules['10_priv_admin']['02_admin_list']             = 'admin.php?act=list';
$modules['10_priv_admin']['03_admin_add']             = 'admin.php?act=add';

//注册码
$modules['10_license_manage']['02_license_create']             = 'license.php?act=create';
$modules['10_license_manage']['03_license_list']             = 'license.php?act=list';


$modules['10_sys_log']['04_admin_logs']             = 'admin_logs.php?act=list';

// $modules['10_support']['02_article']    = 'article.php?act=list';

$modules['11_system']['04_mail_settings']           = 'shop_config.php?act=mail_settings';
$modules['11_system']['05_area_list']               = 'area_manage.php?act=list';
$modules['11_system']['check_file_priv']            = 'check_file_priv.php?act=check';
$modules['11_system']['captcha_manage']             = 'captcha_manage.php?act=main';
// $modules['11_system']['ucenter_setup']              = 'integrate.php?act=setup&code=ucenter';
// $modules['11_system']['navigator']                  = 'navigator.php?act=list';
// $modules['11_system']['file_check']                 = 'filecheck.php';


$modules['13_backup']['02_db_manage']               = 'database.php?act=backup';
$modules['13_backup']['03_db_optimize']             = 'database.php?act=optimize';
$modules['13_backup']['04_sql_query']               = 'sql.php?act=main';
//$modules['13_backup']['05_synchronous']             = 'integrate.php?act=sync';
// $modules['13_backup']['convert']                    = 'convert.php?act=main';


// $modules['14_sms']['02_sms_my_info']                = 'sms.php?act=display_my_info';
// $modules['14_sms']['03_sms_send']                   = 'sms.php?act=display_send_ui';
// $modules['14_sms']['04_sms_charge']                 = 'sms.php?act=display_charge_ui';
// $modules['14_sms']['05_sms_send_history']           = 'sms.php?act=display_send_history_ui';
// $modules['14_sms']['06_sms_charge_history']         = 'sms.php?act=display_charge_history_ui';

$modules['14_sms']['01_sms_setting']                = 'sms.php?act=setting';
$modules['14_sms']['02_sms_sensitive']             = 'sms.php?act=sense';
$modules['14_sms']['03_sms_statistics']             = 'sms.php?act=statistics';
$modules['14_sms']['04_sms_record']                 = 'sms.php?act=record';

$modules['16_email_manage']['email_list']           = 'email_list.php?act=list';
// $modules['16_email_manage']['attention_list']       = 'attention_list.php?act=list';
$modules['16_email_manage']['view_sendlist']        = 'view_sendlist.php?act=list';

$modules['17_stats']['flow_stats']                  = 'flow_stats.php?act=view';
// $modules['17_stats']['report_guest']                = 'guest_stats.php?act=list';

?>
