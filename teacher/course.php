<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('course_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = course_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['course_id'])        ? intval($_REQUEST['course_id'])      : 0;
// 	print_r($_REQUEST);
	if($id==0){//insert
		$sql = "insert into ".$ecs->table("course")
		." (semster,
		z1_time,z1_w1,z1_w2,z1_w3,z1_w4,z1_w5,z1_w6,z1_w7,
		z2_time,z2_w1,z2_w2,z2_w3,z2_w4,z2_w5,z2_w6,z2_w7,
		1_time,1_w1,1_w2,1_w3,1_w4,1_w5,1_w6,1_w7,
		2_time,2_w1,2_w2,2_w3,2_w4,2_w5,2_w6,2_w7,
		3_time,3_w1,3_w2,3_w3,3_w4,3_w5,3_w6,3_w7,
		4_time,4_w1,4_w2,4_w3,4_w4,4_w5,4_w6,4_w7,
		5_time,5_w1,5_w2,5_w3,5_w4,5_w5,5_w6,5_w7,
		6_time,6_w1,6_w2,6_w3,6_w4,6_w5,6_w6,6_w7,
		7_time,7_w1,7_w2,7_w3,7_w4,7_w5,7_w6,7_w7,
		8_time,8_w1,8_w2,8_w3,8_w4,8_w5,8_w6,8_w7,
		w1_time,w1_w1,w1_w2,w1_w3,w1_w4,w1_w5,w1_w6,w1_w7,
		w2_time,w2_w1,w2_w2,w2_w3,w2_w4,w2_w5,w2_w6,w2_w7,
		w3_time,w3_w1,w3_w2,w3_w3,w3_w4,w3_w5,w3_w6,w3_w7,
		class_code )
		values 
			('".$_REQUEST["search_semster"]."',
			'".$_REQUEST["z1_time"]."','".$_REQUEST["z1_w1"]."','".$_REQUEST["z1_w2"]."','".$_REQUEST["z1_w3"]."','".$_REQUEST["z1_w4"]."','".$_REQUEST["z1_w5"]."','".$_REQUEST["z1_w6"]."','".$_REQUEST["z1_w7"]."',
			'".$_REQUEST["z2_time"]."','".$_REQUEST["z2_w1"]."','".$_REQUEST["z2_w2"]."','".$_REQUEST["z2_w3"]."','".$_REQUEST["z2_w4"]."','".$_REQUEST["z2_w5"]."','".$_REQUEST["z2_w6"]."','".$_REQUEST["z2_w7"]."',
			'".$_REQUEST["1_time"]."','".$_REQUEST["1_w1"]."','".$_REQUEST["1_w2"]."','".$_REQUEST["1_w3"]."','".$_REQUEST["1_w4"]."','".$_REQUEST["1_w5"]."','".$_REQUEST["1_w6"]."','".$_REQUEST["1_w7"]."',
			'".$_REQUEST["2_time"]."','".$_REQUEST["2_w1"]."','".$_REQUEST["2_w2"]."','".$_REQUEST["2_w3"]."','".$_REQUEST["2_w4"]."','".$_REQUEST["2_w5"]."','".$_REQUEST["2_w6"]."','".$_REQUEST["2_w7"]."',
			'".$_REQUEST["3_time"]."','".$_REQUEST["3_w1"]."','".$_REQUEST["3_w2"]."','".$_REQUEST["3_w3"]."','".$_REQUEST["3_w4"]."','".$_REQUEST["3_w5"]."','".$_REQUEST["3_w6"]."','".$_REQUEST["3_w7"]."',
			'".$_REQUEST["4_time"]."','".$_REQUEST["4_w1"]."','".$_REQUEST["4_w2"]."','".$_REQUEST["4_w3"]."','".$_REQUEST["4_w4"]."','".$_REQUEST["4_w5"]."','".$_REQUEST["4_w6"]."','".$_REQUEST["4_w7"]."',
			'".$_REQUEST["5_time"]."','".$_REQUEST["5_w1"]."','".$_REQUEST["5_w2"]."','".$_REQUEST["5_w3"]."','".$_REQUEST["5_w4"]."','".$_REQUEST["5_w5"]."','".$_REQUEST["5_w6"]."','".$_REQUEST["5_w7"]."',
			'".$_REQUEST["6_time"]."','".$_REQUEST["6_w1"]."','".$_REQUEST["6_w2"]."','".$_REQUEST["6_w3"]."','".$_REQUEST["6_w4"]."','".$_REQUEST["6_w5"]."','".$_REQUEST["6_w6"]."','".$_REQUEST["6_w7"]."',
			'".$_REQUEST["7_time"]."','".$_REQUEST["7_w1"]."','".$_REQUEST["7_w2"]."','".$_REQUEST["7_w3"]."','".$_REQUEST["7_w4"]."','".$_REQUEST["7_w5"]."','".$_REQUEST["7_w6"]."','".$_REQUEST["7_w7"]."',
			'".$_REQUEST["8_time"]."','".$_REQUEST["8_w1"]."','".$_REQUEST["8_w2"]."','".$_REQUEST["8_w3"]."','".$_REQUEST["8_w4"]."','".$_REQUEST["8_w5"]."','".$_REQUEST["8_w6"]."','".$_REQUEST["8_w7"]."',
			'".$_REQUEST["w1_time"]."','".$_REQUEST["w1_w1"]."','".$_REQUEST["w1_w2"]."','".$_REQUEST["w1_w3"]."','".$_REQUEST["w1_w4"]."','".$_REQUEST["w1_w5"]."','".$_REQUEST["w1_w6"]."','".$_REQUEST["w1_w7"]."',
			'".$_REQUEST["w2_time"]."','".$_REQUEST["w2_w1"]."','".$_REQUEST["w2_w2"]."','".$_REQUEST["w2_w3"]."','".$_REQUEST["w2_w4"]."','".$_REQUEST["w2_w5"]."','".$_REQUEST["w2_w6"]."','".$_REQUEST["w2_w7"]."',
			'".$_REQUEST["w2_time"]."','".$_REQUEST["w3_w1"]."','".$_REQUEST["w3_w2"]."','".$_REQUEST["w3_w3"]."','".$_REQUEST["w3_w4"]."','".$_REQUEST["w3_w5"]."','".$_REQUEST["w3_w6"]."','".$_REQUEST["w3_w7"]."',
			'".$_SESSION["class_code"]."')";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["search_semster"]), 'add', 'course');
		
		make_json_result("《".$_REQUEST["search_semster"]."课程表》保存成功！");
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("course")
		." set 
			z1_time='".$_REQUEST["z1_time"]."',
			z1_w1='".$_REQUEST["z1_w1"]."',
			z1_w2='".$_REQUEST["z1_w2"]."',
			z1_w3='".$_REQUEST["z1_w3"]."',
			z1_w4='".$_REQUEST["z1_w4"]."',
			z1_w5='".$_REQUEST["z1_w5"]."',
			z1_w6='".$_REQUEST["z1_w6"]."',
			z1_w7='".$_REQUEST["z1_w7"]."',
			z2_time='".$_REQUEST["z2_time"]."',
			z2_w1='".$_REQUEST["z2_w1"]."',
			z2_w2='".$_REQUEST["z2_w2"]."',
			z2_w3='".$_REQUEST["z2_w3"]."',
			z2_w4='".$_REQUEST["z2_w4"]."',
			z2_w5='".$_REQUEST["z2_w5"]."',
			z2_w6='".$_REQUEST["z2_w6"]."',
			z2_w7='".$_REQUEST["z2_w7"]."',
			1_time='".$_REQUEST["1_time"]."',
			1_w1='".$_REQUEST["1_w1"]."',
			1_w2='".$_REQUEST["1_w2"]."',
			1_w3='".$_REQUEST["1_w3"]."',
			1_w4='".$_REQUEST["1_w4"]."',
			1_w5='".$_REQUEST["1_w5"]."',
			1_w6='".$_REQUEST["1_w6"]."',
			1_w7='".$_REQUEST["1_w7"]."',
			2_time='".$_REQUEST["2_time"]."',
			2_w1='".$_REQUEST["2_w1"]."',
			2_w2='".$_REQUEST["2_w2"]."',
			2_w3='".$_REQUEST["2_w3"]."',
			2_w4='".$_REQUEST["2_w4"]."',
			2_w5='".$_REQUEST["2_w5"]."',
			2_w6='".$_REQUEST["2_w6"]."',
			2_w7='".$_REQUEST["2_w7"]."',
			3_time='".$_REQUEST["3_time"]."',
			3_w1='".$_REQUEST["3_w1"]."',
			3_w2='".$_REQUEST["3_w2"]."',
			3_w3='".$_REQUEST["3_w3"]."',
			3_w4='".$_REQUEST["3_w4"]."',
			3_w5='".$_REQUEST["3_w5"]."',
			3_w6='".$_REQUEST["3_w6"]."',
			3_w7='".$_REQUEST["3_w7"]."',
			4_time='".$_REQUEST["4_time"]."',
			4_w1='".$_REQUEST["4_w1"]."',
			4_w2='".$_REQUEST["4_w2"]."',
			4_w3='".$_REQUEST["4_w3"]."',
			4_w4='".$_REQUEST["4_w4"]."',
			4_w5='".$_REQUEST["4_w5"]."',
			4_w6='".$_REQUEST["4_w6"]."',
			4_w7='".$_REQUEST["4_w7"]."',
			5_time='".$_REQUEST["5_time"]."',
			5_w1='".$_REQUEST["5_w1"]."',
			5_w2='".$_REQUEST["5_w2"]."',
			5_w3='".$_REQUEST["5_w3"]."',
			5_w4='".$_REQUEST["5_w4"]."',
			5_w5='".$_REQUEST["5_w5"]."',
			5_w6='".$_REQUEST["5_w6"]."',
			5_w7='".$_REQUEST["5_w7"]."',
			6_time='".$_REQUEST["6_time"]."',
			6_w1='".$_REQUEST["6_w1"]."',
			6_w2='".$_REQUEST["6_w2"]."',
			6_w3='".$_REQUEST["6_w3"]."',
			6_w4='".$_REQUEST["6_w4"]."',
			6_w5='".$_REQUEST["6_w5"]."',
			6_w6='".$_REQUEST["6_w6"]."',
			6_w7='".$_REQUEST["6_w7"]."',
			7_time='".$_REQUEST["7_time"]."',
			7_w1='".$_REQUEST["7_w1"]."',
			7_w2='".$_REQUEST["7_w2"]."',
			7_w3='".$_REQUEST["7_w3"]."',
			7_w4='".$_REQUEST["7_w4"]."',
			7_w5='".$_REQUEST["7_w5"]."',
			7_w6='".$_REQUEST["7_w6"]."',
			7_w7='".$_REQUEST["7_w7"]."',
			8_time='".$_REQUEST["8_time"]."',
			8_w1='".$_REQUEST["8_w1"]."',
			8_w2='".$_REQUEST["8_w2"]."',
			8_w3='".$_REQUEST["8_w3"]."',
			8_w4='".$_REQUEST["8_w4"]."',
			8_w5='".$_REQUEST["8_w5"]."',
			8_w6='".$_REQUEST["8_w6"]."',
			8_w7='".$_REQUEST["8_w7"]."',
			w1_time='".$_REQUEST["w1_time"]."',
			w1_w1='".$_REQUEST["w1_w1"]."',
			w1_w2='".$_REQUEST["w1_w2"]."',
			w1_w3='".$_REQUEST["w1_w3"]."',
			w1_w4='".$_REQUEST["w1_w4"]."',
			w1_w5='".$_REQUEST["w1_w5"]."',
			w1_w6='".$_REQUEST["w1_w6"]."',
			w1_w7='".$_REQUEST["w1_w7"]."',
			w2_time='".$_REQUEST["w2_time"]."',
			w2_w1='".$_REQUEST["w2_w1"]."',
			w2_w2='".$_REQUEST["w2_w2"]."',
			w2_w3='".$_REQUEST["w2_w3"]."',
			w2_w4='".$_REQUEST["w2_w4"]."',
			w2_w5='".$_REQUEST["w2_w5"]."',
			w2_w6='".$_REQUEST["w2_w6"]."',
			w2_w7='".$_REQUEST["w2_w7"]."',
			w3_time='".$_REQUEST["w3_time"]."',
			w3_w1='".$_REQUEST["w3_w1"]."',
			w3_w2='".$_REQUEST["w3_w2"]."',
			w3_w3='".$_REQUEST["w3_w3"]."',
			w3_w4='".$_REQUEST["w3_w4"]."',
			w3_w5='".$_REQUEST["w3_w5"]."',
			w3_w6='".$_REQUEST["w3_w6"]."',
			w3_w7='".$_REQUEST["w3_w7"]."'
			where course_id=".$id;
		
		$db->query($sql);
				
		admin_log(addslashes($_REQUEST["subject"]), 'update', 'course');
		make_json_result("《".$_REQUEST["search_semster"]."课程表》保存成功！", $id);
	}
	
}

elseif ($_REQUEST['act'] == 'export')
{
	$semster = empty($_REQUEST['search_semster']) ? '' : trim($_REQUEST['search_semster']);//名称
	
	$v = course_list();
	
	$content = "节次,时间,星期一,星期二,星期三,星期四,星期五,星期六,星期日\n";
	$content .= "第一节早自习,".$v["z1_time"]. ",".$v["z1_w1"]. ",".$v["z1_w2"]. ",".$v["z1_w3"]. ",".$v["z1_w4"]. ",".$v["z1_w5"]. ",".$v["z1_w6"]. ",".$v["z1_w7"] . "\n";
	$content .= "第二节早自习,".$v["z2_time"]. ",".$v["z2_w1"]. ",".$v["z2_w2"]. ",".$v["z2_w3"]. ",".$v["z2_w4"]. ",".$v["z2_w5"]. ",".$v["z2_w6"]. ",".$v["z2_w7"] . "\n";
	$content .= "第一节课,".$v["1_time"]. ",".$v["1_w1"]. ",".$v["1_w2"]. ",".$v["1_w3"]. ",".$v["1_w4"]. ",".$v["1_w5"]. ",".$v["1_w6"]. ",".$v["1_w7"] . "\n";
	$content .= "第二节课,".$v["2_time"]. ",".$v["2_w1"]. ",".$v["2_w2"]. ",".$v["2_w3"]. ",".$v["2_w4"]. ",".$v["2_w5"]. ",".$v["2_w6"]. ",".$v["2_w7"] . "\n";
	$content .= "第三节课,".$v["3_time"]. ",".$v["3_w1"]. ",".$v["3_w2"]. ",".$v["3_w3"]. ",".$v["3_w4"]. ",".$v["3_w5"]. ",".$v["3_w6"]. ",".$v["3_w7"] . "\n";
	$content .= "第四节课,".$v["4_time"]. ",".$v["4_w1"]. ",".$v["4_w2"]. ",".$v["4_w3"]. ",".$v["4_w4"]. ",".$v["4_w5"]. ",".$v["4_w6"]. ",".$v["4_w7"] . "\n";
	$content .= "第五节课,".$v["5_time"]. ",".$v["5_w1"]. ",".$v["5_w2"]. ",".$v["5_w3"]. ",".$v["5_w4"]. ",".$v["5_w5"]. ",".$v["5_w6"]. ",".$v["5_w7"] . "\n";
	$content .= "第六节课,".$v["6_time"]. ",".$v["6_w1"]. ",".$v["6_w2"]. ",".$v["6_w3"]. ",".$v["6_w4"]. ",".$v["6_w5"]. ",".$v["6_w6"]. ",".$v["6_w7"] . "\n";
	$content .= "第七节课,".$v["7_time"]. ",".$v["7_w1"]. ",".$v["7_w2"]. ",".$v["7_w3"]. ",".$v["7_w4"]. ",".$v["7_w5"]. ",".$v["7_w6"]. ",".$v["7_w7"] . "\n";
	$content .= "第八节课,".$v["8_time"]. ",".$v["8_w1"]. ",".$v["8_w2"]. ",".$v["8_w3"]. ",".$v["8_w4"]. ",".$v["8_w5"]. ",".$v["8_w6"]. ",".$v["8_w7"] . "\n";
	$content .= "第一节晚自习,".$v["w1_time"]. ",".$v["w1_w1"]. ",".$v["w1_w2"]. ",".$v["w1_w3"]. ",".$v["w1_w4"]. ",".$v["w1_w5"]. ",".$v["w1_w6"]. ",".$v["w1_w7"] . "\n";
	$content .= "第二节晚自习,".$v["w2_time"]. ",".$v["w2_w1"]. ",".$v["w2_w2"]. ",".$v["w2_w3"]. ",".$v["w2_w4"]. ",".$v["w2_w5"]. ",".$v["w2_w6"]. ",".$v["w2_w7"] . "\n";
	$content .= "第三节晚自习,".$v["w3_time"]. ",".$v["w3_w1"]. ",".$v["w3_w2"]. ",".$v["w3_w3"]. ",".$v["w3_w4"]. ",".$v["w3_w5"]. ",".$v["w3_w6"]. ",".$v["w3_w7"] . "\n";
	
	$charset = empty($_POST['charset']) ? 'GBK' : trim($_POST['charset']);
	
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=".$semster."课程表".".csv");
	header("Content-Type: application/unknown");
	die($file);
	
}
/**
 *  返回班级管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function course_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['semster'] = empty($_REQUEST['search_semster']) ? '' : trim($_REQUEST['search_semster']);//名称
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['semster'] = json_str_iconv($filter['semster']);
		}

		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['semster'])
		{
			$ex_where .= " AND semster = '" . mysql_like_quote($filter['semster']) ."'";
		}

		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table("course")  . $ex_where ;

// 		echo $sql; echo '<br>';

		$filter['semster'] = stripslashes($filter['semster']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getRow($sql);
	return $list;
}



?>