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


$sql  = 'SELECT * FROM hteacher.ht_school ORDER BY school_id DESC';
$schools = $GLOBALS['db']->getAll($sql);

// print_r($schools);die();

foreach ($schools as $school){
	update_license($school['code']);
}



function update_license($school_code){
	$sql = "select * from ".$school_code."_school.ht_student where license<>'' ";
	$students = $GLOBALS['db']->getAll($sql);
	
	foreach ($students as $s){
		$sql = "update hteacher.ht_license set state=1, student_id='".$s['student_id']."',class_code='".$s['class_code']."',school_code='".$school_code."_school' where license='".$s['license']."'";
		$GLOBALS['db']->query($sql);
	}
	
	
	
}

?>