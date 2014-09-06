<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	set_params();
	$smarty->display('score_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = score_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'import')
{
	/* 将文件按行读入数组，逐行进行解析 */
	$line_number = 0;
	$scores_list = array();
	$data = file($_FILES["importFile"]["tmp_name"]);
	
	$bigan_flag = false;
	foreach ($data AS $line)
	{
		// 转换编码
// 		if (($_POST['charset'] != 'UTF8') && (strpos(strtolower(EC_CHARSET), 'utf') === 0))
// 		{
// 			$line = ecs_iconv($_POST['charset'], 'UTF8', $line);
// 		}
		$line = mb_convert_encoding($line, "UTF-8", "GBK");
		
		// 初始化
		$arr    = array();
		$line_list = explode(",",$line);
		
		if($bigan_flag===false){
			foreach($line_list as $k=>$v){//学生姓名
				if(strpos($v, "分数")>-1){
					$bigan_flag = true;
					$line_number++;
					break;
				}
			}
			if(!$bigan_flag){	//定位起始行
				$line_number++;
			}
			continue;
		}
		
		$i=1;
		$arr['exam_code'] = replace_quote($line_list[$i++]);//考试编号
		$arr['student_code'] = replace_quote($line_list[$i++]);
		
		$score = replace_quote($line_list[$i++]);
		$arr['score'] = $score?intval($score):0;
		
		$add_score = replace_quote($line_list[$i++]);
		$arr['add_score'] = $add_score?intval($add_score):0;
		
		$arr['class_code'] = $class_code;
		
		$scores_list[] = $arr;
	}
	
	insert_datas($scores_list);
	
	set_params();
	$smarty->display('score_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['score_id'])        ? intval($_REQUEST['score_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("score")
		." (class_code,exam_code,student_code,score,add_score,created )
		values 
			('".$_SESSION["class_code"]."','".$_REQUEST["exam_code"]."',
			'".$_REQUEST["student_code"]."','".$_REQUEST["score"]."',
			'".$_REQUEST["add_score"]."',now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["student_code"]), 'add', $sql);
		make_json_result("添加成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("score")
		." set exam_code='".$_REQUEST["exam_code"]."',
			student_code='".$_REQUEST["student_code"]."',
			score='".$_REQUEST["score"]."',
			add_score='".$_REQUEST["add_score"]."'
			where score_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["student_code"]), 'update', $sql);
		
		make_json_result("修改成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['score_id'])        ? intval($_REQUEST['score_id'])      : 0;
	$sql = "delete from ".$ecs->table("score")." where score_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["score_id"], 'delete', 'score');
	
	make_json_result("删除成功！");
}

/// 根据考试名称导出成绩
elseif ($_REQUEST['act'] == 'exportbyexamname')
{
	$prj_code = empty($_REQUEST['search_prj_code']) ? '' : trim($_REQUEST['search_prj_code']);//考试名称
	//获取这个项目所有的考试科目
	$subjects = get_subjects_by_exam($class_code, $prj_code);
	
	$content = "学号,姓名,";
	foreach($subjects as $k=>$v){
		$content .= $v["code"].",";//"-".$v["subject"].
	}
	$content .= "总分\n";
	
	$list = get_scores_by_exam($class_code, $prj_code, "", "", " s.student_code");
	$students = array();
	foreach ($list as $k=>$v)
	{
		$student = array();
		
		$student["student_code"] = $v["student_code"];
		$student["student_name"] = $v["student_name"];
		$student[$v["exam_code"]]= $v["score"]+$v["add_score"]; //科目对于成绩
		
		
		$isExsit = false;
		$students_tmp = $students;
		foreach($students_tmp as $studsk=>$studsv){
			if($studsv["student_code"]==$student["student_code"]){
				$isExsit = true;
				
				$students[$student["student_code"]][$v["exam_code"]] = $student[$v["exam_code"]];
// 				echo $student["student_name"] .":". $v["exam_code"] .":". $student[$v["exam_code"]];echo '<br>';
				break;
			}
		}
		
		if(!$isExsit){
			$students[$student["student_code"]] = $student;
		}
		
	}
	
// 	print_r($students);echo '<br>';
	
	foreach($students as $k=>$v){
		$content .= "'".$v["student_code"]."',".$v["student_name"].",";
		
		$total = 0;
		foreach($subjects as $sk=>$sv){
			$score = intval($v[$sv["code"]]);
			$total += $score;
			$content .= $score.",";
		}
		
		$content .= $total;
		$content .= "\r\n";
	}
	
	
	$charset = empty($_POST['charset']) ? 'UTF8' : trim($_POST['charset']);
	
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=".$prj_code.".csv");
	header("Content-Type: application/unknown");
	die($file);
	
}


/// 根据考试编号导出成绩
elseif ($_REQUEST['act'] == 'exportbyexamcode')
{
	$exam_code = empty($_REQUEST['search_exam_code']) ? '' : trim($_REQUEST['search_exam_code']);//考试名称

	$content = "学号,姓名,";
	
	$list = get_scores_by_exam($class_code, "", $exam_code, "", " s.student_code");
	
	$subject = "";
	$$prj_code = "";
	$students = array();
	foreach ($list as $k=>$v)
	{
		$student = array();

		$student["student_code"] = $v["student_code"];
		$student["student_name"] = $v["student_name"];
		$student[$v["exam_code"]]= $v["score"]+$v["add_score"]; //科目对于成绩
		if($subject==""){
			$subject = $v["exam_subject"];
			$prj_code = $v["prj_code"];
		}
		
		$students[$student["student_code"]] = $student;
	}
	$content .= $subject."\r\n";

	// 	print_r($students);echo '<br>';

	foreach($students as $k=>$v){
		$content .= "'".$v["student_code"]."',".$v["student_name"].",".$v[$exam_code]."\r\n";
	}


	$charset = empty($_POST['charset']) ? 'UTF8' : trim($_POST['charset']);

	$file = ecs_iconv(EC_CHARSET, $charset, $content);

	header("Content-Disposition: attachment; filename=".$prj_code."_".$subject.".csv");
	header("Content-Type: application/unknown");
	die($file);

}


/// 根据学生学号导出成绩
elseif ($_REQUEST['act'] == 'exportbystudentcode')
{
	$student_code = empty($_REQUEST['search_student_code']) ? '' : trim($_REQUEST['search_student_code']);//学生学号
	$prj_code = empty($_REQUEST['search_prj_code']) ? '' : trim($_REQUEST['search_prj_code']);//学生学号

	$content = "学号,姓名,考试名称,考试编号,考试科目,分数,附加分数\r\n";

	$list = get_scores_by_exam($class_code, $prj_code, "", $student_code, " s.exam_code");

	foreach ($list as $k=>$v)
	{
		$content .= "'".$v["student_code"]."',".$v["student_name"].",".$v["prj_code"].",".$v["exam_code"].",".$v["exam_subject"].",".$v["score"].",".$v["add_score"]."\r\n";
	}
	
	$charset = empty($_POST['charset']) ? 'UTF8' : trim($_POST['charset']);

	$file = ecs_iconv(EC_CHARSET, $charset, $content);

	header("Content-Disposition: attachment; filename=学号".$student_code."成绩汇总表.csv");
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
function score_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['prj_code'] = empty($_REQUEST['search_prj_code']) ? '' : trim($_REQUEST['search_prj_code']);//考试名称
		$filter['exam_code'] = empty($_REQUEST['search_exam_code']) ? '' : trim($_REQUEST['search_exam_code']);//考试编码
		$filter['student_code'] = empty($_REQUEST['search_student_code']) ? '' : trim($_REQUEST['search_student_code']);//学生学号
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['prj_code'] = json_str_iconv($filter['prj_code']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'score_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE s.class_code='".$_SESSION["class_code"]."' ";
		if ($filter['prj_code'])
		{
			$ex_where .= " AND  e.prj_code='".$filter['prj_code']."'";
		}
		if ($filter['exam_code'])
		{
			$ex_where .= " AND s.exam_code = '" . mysql_like_quote($filter['exam_code']) ."'";
		}
		if ($filter['student_code'])
		{
			$ex_where .= " AND s.student_code = '" . mysql_like_quote($filter['student_code']) ."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("score") ." s 
				left join ".$GLOBALS['ecs']->table("exam") ." e  on s.exam_code=e.code ". $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT s.score_id, s.exam_code, e.prj_code as prj_code,
		         	e.subject as exam_subject, s.student_code, s.score,
		         	st.name as student_name, s.add_score, s.created ".
                " FROM " . $GLOBALS['ecs']->table("score")  ." s 
				left join ".$GLOBALS['ecs']->table("exam") ." e on s.exam_code=e.code 
				left join ".$GLOBALS['ecs']->table("student") ." st on s.student_code=st.code
				". $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
// 		echo $sql;echo '<br>';
		
		$filter['prj_code'] = stripslashes($filter['prj_code']);
		$filter['exam_code'] = stripslashes($filter['exam_code']);
		$filter['student_code'] = stripslashes($filter['student_code']);
		
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



function insert_datas($scores_list){
	$sql = "insert into ".$GLOBALS['ecs']->table("score")
	." (class_code,exam_code,student_code,score,add_score,created )
					values "; 
					
	foreach ($scores_list as $k=>$v){
		
		$sql .= "('".$v["class_code"]."','".$v["exam_code"]."',
		'".$v["student_code"]."','".$v["score"]."',
		'".$v["add_score"]."',now())";
		if($k<(sizeof($scores_list)-1)){
			$sql .= ",";
		}
		
	}
	
	$GLOBALS['db']->query($sql);
	
}


?>