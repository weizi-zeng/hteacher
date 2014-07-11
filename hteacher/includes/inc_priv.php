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
    $purview['02_grade_list']        = 'grade_manage';
    $purview['03_grade_add']         = 'grade_manage';
    $purview['02_class_list']        = 'class_manage';
    $purview['03_class_add']         = 'class_manage';
    $purview['02_classAdmin_list']        = 'classAdmin_manage';
    $purview['03_classAdmin_add']         = 'classAdmin_manage';

?>