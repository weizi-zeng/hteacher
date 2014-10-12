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

/**
* 根据考试名称ajax加载考试科目
*/
elseif ($_REQUEST['act'] == 'ajax_get_subject')
{
	$exam_prj = empty($_REQUEST["prj_id"])?"":trim($_REQUEST["prj_id"]);
	$subjects = get_subjects($class_code, $exam_prj);
	make_json($subjects);
}
/**
 * 批量添加成绩
 */
elseif ($_REQUEST['act'] == 'ajax_add')
{
	$exam_prj = empty($_REQUEST["add_prj_id"])?"":trim($_REQUEST["add_prj_id"]);
	$student = empty($_REQUEST["add_student_code"])?"":trim($_REQUEST["add_student_code"]);
	if(!$exam_prj || !$student){
		make_json_error("参数有误！");
		exit;
	}
	
	$subjects = get_subjects($class_code);
	$scores = array(); //[subject ==> score]
	foreach ($subjects as $subject){
		$s = $subject["subject"];
		$score = empty($_REQUEST["add_score_".$s])?"":trim($_REQUEST["add_score_".$s]);
		if($score){
			$scores[$s] = $score;
			//检查是否重复录入成绩
			$sql = "select * from ".$ecs->table("score")." where prj_id=$exam_prj and subject='$s' and student_code='$student' and class_code='$class_code' " ;
			$oldScore = $db->getRow($sql);
			if($oldScore){
				make_json_error("学号：“".$student."”，在《".get_exam_prj_name($exam_prj)."》中的".$s."成绩于".$oldScore["created"]."已经录入到了系统，录入分数为“".$oldScore["score"]."”，请勿重复录入成绩！");
				exit;
			}
		}
	}
	
	if(count($scores)<1){
		make_json_error("请填写成绩！");
		exit;
	}
	
	//插入数据
	$sql = "insert into ".$ecs->table("score")." (prj_id, subject, class_code, student_code, score, created) values ";
	$i=count($scores);
	foreach($scores as $s=>$v){
		$sql.="($exam_prj, '$s', '$class_code', '$student', '$v', now())";
		$i--;
		if($i>0){
			$sql.=",";
		}
	}
	$db->query($sql);
	
	make_json_result("成功插入".count($scores)."条数据");
	exit;
}


elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id = !empty($_REQUEST['score_id'])? intval($_REQUEST['score_id']): 0;
	$exam_prj = !empty($_REQUEST['exam_prj'])? intval($_REQUEST['exam_prj']): 0;
	$exam_subject = !empty($_REQUEST['exam_subject'])? trim($_REQUEST['exam_subject']): "";
	$student = !empty($_REQUEST['student_code'])? trim($_REQUEST['student_code']): "";
	
	//检查是否重复录入成绩
	$sql = "select * from ".$ecs->table("score")." where prj_id=$exam_prj and subject='$exam_subject' and student_code='$student' and class_code='$class_code' and score_id!=".$id ;
	$oldScore = $db->getRow($sql);
	if($oldScore){
		make_json_error("学号：“".$student."”，在《".get_exam_prj_name($exam_prj)."》中的".$s."成绩于".$oldScore["created"]."已经录入到了系统，录入分数为“".$oldScore["score"]."”，请勿重复录入成绩！");
		exit;
	}
	
	if($id==0){//insert

		$sql = "insert into ".$ecs->table("score")
		." (prj_id, subject, class_code, student_code, score, created )
		values 
			($exam_prj,'".$exam_subject."','".$_SESSION["class_code"]."',
			'".$student."','".$_REQUEST["score"]."', now())";
		
		$db->query($sql);
		
		admin_log(addslashes($student), 'add', $sql);
		make_json_result("添加成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("score")
		." set subject='".$_REQUEST["exam_subject"]."',
			student_code='".$_REQUEST["student_code"]."',
			prj_id='".$exam_prj."',
			score='".$_REQUEST["score"]."'
			where score_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["student_code"]), 'update', $sql);
		
		make_json_result("修改成功！");
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['score_id'])        ? trim($_REQUEST['score_id'])      : "";
	$sql = "delete from ".$ecs->table("score")." where score_id in (".$id.")";
	$db->query($sql);
	
	admin_log($_REQUEST["score_id"], 'delete', 'score');
	make_json_result("删除成功！");
}

/// 根据考试名称导出成绩
elseif ($_REQUEST['act'] == 'exportbyprj')
{
	$prj_id = empty($_REQUEST['search_prj_id']) ? '' : trim($_REQUEST['search_prj_id']);//考试名称
	//获取这个项目所有的考试科目
	$subjects = get_subjects($class_code, $prj_id);
	
	$content = "学号,姓名,";
	foreach($subjects as $k=>$v){
		$content .= $v["subject"].",";//"-".$v["subject"].
	}
	$content .= "总分\n";
	
	$list = get_scores_by_exam($class_code, $prj_id, "", "", " s.student_code");
	$students = array();
	foreach ($list as $k=>$v)
	{
		$student = array();
		
		$student["student_code"] = $v["student_code"];
		$student["student_name"] = $v["student_name"];
		$student[$v["subject"]]= $v["score"]; //科目对于成绩
		
		$isExsit = false;
		$students_tmp = $students;
		foreach($students_tmp as $studsk=>$studsv){
			if($studsv["student_code"]==$student["student_code"]){
				$isExsit = true;
				$students[$student["student_code"]][$v["subject"]] = $student[$v["subject"]];
				break;
			}
		}
		
		if(!$isExsit){
			$students[$student["student_code"]] = $student;
		}
	}
	
// 	print_r($students);echo '<br>';
	
	foreach($students as $k=>$v){
		$content .= $v["student_code"].",".$v["student_name"].",";
		
		$total = 0;
		foreach($subjects as $sk=>$sv){
			$score = intval($v[$sv["subject"]]);
			$total += $score;
			$content .= $score.",";
		}
		
		$content .= $total;
		$content .= "\r\n";
	}
	
	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=".get_exam_prj_name($prj_id).".csv");
	header("Content-Type: application/unknown");
	die($file);
	
}

/// 根据查询条件导出成绩
elseif ($_REQUEST['act'] == 'exportbyquery')
{
	$student_code = empty($_REQUEST['search_student_code']) ? '' : trim($_REQUEST['search_student_code']);//学生学号
	$prj_id = empty($_REQUEST['search_prj_id']) ? '' : trim($_REQUEST['search_prj_id']);//学生学号
	$exam_subject = empty($_REQUEST['search_exam_subject']) ? '' : trim($_REQUEST['search_exam_subject']);//学生学号
	
	$list = get_scores_by_exam($class_code, $prj_id, $student_code, $exam_subject, " s.student_code");
	
	$content = "考试名称,考试科目,学号,姓名,考试分数\r\n";
	foreach ($list as $k=>$v)
	{
		$content .= $v["prj_name"].",".$v["subject"].",".$v["student_code"].",".$v["student_name"].",".$v["score"]."\r\n";
	}
	
	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);
	$file = ecs_iconv(EC_CHARSET, $charset, $content);

	header("Content-Disposition: attachment; filename=score.csv");
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
		$filter['prj_id'] = empty($_REQUEST['search_prj_id']) ? '' : trim($_REQUEST['search_prj_id']);//考试名称
		$filter['exam_subject'] = empty($_REQUEST['search_exam_subject']) ? '' : trim($_REQUEST['search_exam_subject']);//考试科目
		$filter['student_code'] = empty($_REQUEST['search_student_code']) ? '' : trim($_REQUEST['search_student_code']);//学生学号
		
		$filter['sort']    = empty($_REQUEST['sort'])    ? 'score_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE s.class_code='".$_SESSION["class_code"]."' ";
		if ($filter['prj_id'])
		{
			$ex_where .= " AND  s.prj_id='".$filter['prj_id']."'";
		}
		if ($filter['exam_subject'])
		{
			$ex_where .= " AND s.subject = '" . mysql_like_quote($filter['exam_subject']) ."'";
		}
		if ($filter['student_code'])
		{
			$ex_where .= " AND s.student_code = '" . mysql_like_quote($filter['student_code']) ."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("score") ." s ". $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT s.*, p.name as prj_name,st.name as student_name ".
                " FROM " . $GLOBALS['ecs']->table("score")  ." s 
				left join ".$GLOBALS['ecs']->table("student") ." st on s.student_code=st.code and st.class_code='".$_SESSION["class_code"]."' 
				left join ".$GLOBALS['ecs']->table("exam_prj") ." p on s.prj_id=p.prj_id  
				". $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
		
		$filter['prj_id'] = stripslashes($filter['prj_id']);
		$filter['exam_subject'] = stripslashes($filter['exam_subject']);
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

?>