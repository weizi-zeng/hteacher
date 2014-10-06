<?php 
/**
 * 定义所有的短信模板
 */

if (!defined('IN_ECS'))
{
	die('Hacking attempt');
}

/**
 * 用户注册成功时短信提醒 
 */
function sms_tmp_reg_success($guardian, $password, $regCode){
	$content = "尊敬的".$guardian["guardian_name"]."家长，您已成功注册我爱我班家校通系统！您的账号密码为：".$guardian["guardian_phone"]."/".$password."，注册码".$regCode."，网址：http://www.wawb.com.cn，谢谢您的使用！";
	return $content;
}

/**
* 班级管理员为家长直接重置密码的短信提醒
*/
function sms_tmp_change_pwd_by_classAdmin($guardian, $password, $admin_name){
	$content = "尊敬的".$guardian["guardian_name"]."家长，您的密码已经被重置！您当前的账号密码为：".$guardian["guardian_phone"]."/".$password."，重置人：".$admin_name."，谢谢您的使用！";
	return $content;
}

/**
 * 用户忘记密码，通过手机重置密码的短信提醒
 */
function sms_tmp_change_pwd_by_phone_guardian($guardian, $password){
	$content = "尊敬的".$guardian["guardian_name"]."家长，您的密码已经被重置！您当前的账号密码为：".$guardian["guardian_phone"]."/".$password."，谢谢您的使用！";
	return $content;
}

/**
* 管理员忘记密码，通过手机重置密码的短信提醒
*/
function sms_tmp_change_pwd_by_phone_admin($admin, $password){
	$content = "尊敬的".$admin["name"]."管理员，您的密码已经被重置！您当前的账号密码为：".$admin["user_name"]."/".$password."，谢谢您的使用！";
	return $content;
}


?>