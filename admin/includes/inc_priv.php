<?php

/**
 * ECSHOP 权限对照表
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: sunxiaodong $
 * $Id: inc_priv.php 15503 2008-12-24 09:22:45Z sunxiaodong $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

//会员管理权限
    $purview['03_school_list']        = 'school_manage';
    $purview['04_school_add']         = 'school_manage';
//     $purview['05_class_list']        = 'class_manage';
//     $purview['06_class_add']         = 'class_manage';
    $purview['07_schoolAdmin_list']        = 'schoolAdmin_manage';
    $purview['08_schoolAdmin_add']         = 'schoolAdmin_manage';
//     $purview['09_classAdmin_list']        = 'classAdmin_manage';
//     $purview['10_classAdmin_add']         = 'classAdmin_manage';
    
    $purview['01_problems_list']        = 'problems_manage';
    $purview['02_problems_add']         = 'problems_manage';
    
//权限管理
    $purview['02_admin_list']           = array('admin_manage', 'admin_drop', 'allot_priv');
    $purview['03_admin_add']           = array('admin_manage');
    
    $purview['02_license_create']        = 'license_manage';
    $purview['03_license_list']         = 'license_manage';
    
    $purview['04_admin_logs']           = array('logs_manage', 'logs_drop');
//     $purview['agency_list']          = 'agency_manage';
//     $purview['suppliers_list']          = 'suppliers_manage'; // 供货商
//     $purview['admin_role']             = 'role_manage';

    $purview['02_schoolAdmin_msg']           = 'msg_manage';
    $purview['03_classAdmin_msg']           = 'msg_manage';
    $purview['04_customer_msg']           = 'msg_manage';
    $purview['05_other_msg']           = 'msg_manage';
    
//商店设置权限
    $purview['04_mail_settings']     = 'shop_config';
    $purview['05_area_list']         = 'area_manage';
    $purview['check_file_priv']      = 'file_priv';
    $purview['captcha_manage']       = 'shop_config';
    $purview['file_check']           = 'file_check';
    $purview['navigator']            = 'navigator';
    $purview['ucenter_setup']        = 'integrate_users';

//报表统计权限
    $purview['flow_stats']           = 'client_flow_stats';
    $purview['report_guest']         = 'client_flow_stats';

//数据库管理
    $purview['02_db_manage']         = array('db_backup', 'db_renew');
    $purview['03_db_optimize']       = 'db_optimize';
    $purview['04_sql_query']         = 'sql_query';
    $purview['convert']              = 'convert';

//短信管理
    $purview['01_sms_setting']       = 'sms_manage';
    $purview['02_sms_sensitive']       = 'sms_manage';
    $purview['03_sms_statistics']    = 'sms_manage';
    $purview['04_sms_record']        = 'sms_manage';

//邮件群发管理
    $purview['attention_list']       = 'attention_list';
    $purview['email_list']           = 'email_list';
    $purview['magazine_list']        = 'magazine_list';
    $purview['view_sendlist']        = 'view_sendlist';

?>