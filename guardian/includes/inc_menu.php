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
学校管理员:
	年级列表
	添加年级
	
	班级列表
	添加班级
	
	班级管理员列表
	添加班级管理员
*/

//系统管理
$modules['02_grade']['02_grade_list']             = 'grade.php?act=list';
$modules['02_grade']['03_grade_add']              = 'grade.php?act=add';

$modules['03_class']['02_class_list']             = 'class.php?act=list';
$modules['03_class']['03_class_add']              = 'class.php?act=add';

$modules['04_classAdmin']['02_classAdmin_list']             = 'classAdmin.php?act=list';
$modules['04_classAdmin']['03_classAdmin_add']              = 'classAdmin.php?act=add';

?>
