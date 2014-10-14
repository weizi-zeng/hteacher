<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	//考试名称
	$prjs = get_exam_prjs($_SESSION["class_code"]);
	$smarty->assign("prjs", $prjs);
// 	$smarty->assign("select_prj", 2);
	$smarty->display('score_summary_list.htm');
	exit;
}

///加载考试汇总信息
elseif ($_REQUEST['act'] == 'ajax_load')
{
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试名称
	$prj_name = get_exam_prj_name($prj_id);
	$subjects = get_subjects($class_code, $prj_id);
	$res = scoreStatistics($class_code, $prj_id);
	$result = array('subjects'=>$subjects,'students'=>$res);
	make_json($result);
	exit;
}

///导入考试汇总信息
elseif ($_REQUEST['act'] == 'import')
{
	/* 将文件按行读入数组，逐行进行解析 */
	$line_number = 0;
	$scores_list = array();
	$data = file($_FILES["importFile"]["tmp_name"]);
	
	$titles = array();
	$prj_id = '';
	$begin_flag = false;
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
	
		if($begin_flag===false){
			foreach($line_list as $k=>$v){
				//学号
				if(strpos($v, "学号")>-1){
					$begin_flag = true;
					$titles = $line_list;//获取标题
					$line_number++;
					break;
				}
			}
			if(!$begin_flag){
				//定位起始行
				$line_number++;
			}
			continue;
		}
	
		$i=1;
		$prj_id = $arr['prj_id'] = replace_quote($line_list[$i++]);
		$arr['student_code'] = replace_quote($line_list[$i++]);//学生学号
		$arr['student_name'] = replace_quote($line_list[$i++]);//学生姓名
		
		$subjects_score = array();
		for(;$i<count($titles);){
			$title = $titles[$i];
			if(strpos($title, "年级排名")===false){
				$subjects_score[$title] = intval($line_list[$i++]);//科目=》成绩
			}else {
				break;
			}
		}
		$arr['subjects'] = $subjects_score;
	
		$arr['grade_rank'] = intval($line_list[$i++]);
		$arr['up_down'] = intval($line_list[$i++]);
		
		$arr['class_code'] = $class_code;
		$scores_list[] = $arr;
	}
	
// 	print_r($scores_list);

	delete_datas($class_code, $prj_id);
	insert_datas($scores_list);
	
	//考试名称
	$prjs = get_exam_prjs($_SESSION["class_code"]);
	$smarty->assign("prjs", $prjs);
	$smarty->assign("select_prj", $prj_id);
	$smarty->display('score_summary_list.htm');
	exit;
	
}

///导出考试汇总信息
elseif ($_REQUEST['act'] == 'export')
{
	//考试名称
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试名称
	$prj_name = get_exam_prj_name($prj_id);
	$subjects = get_subjects($class_code, $prj_id);
	$content = '您选择的《'.$prj_name.'》还没有进行考试安排！';
	if(count($subjects)>0){
		
		$content = "学号,姓名,";//学生学号，科目。。。，总分，年级排名，年级进退
		foreach ($subjects as $v)
		{
			$content .= $v["subject"].',';
		}
		$content .="总分,班级排名,年级排名,年级进退\r\n";
		
		$res = scoreStatistics($class_code, $prj_id);
		foreach($res as $s=>$v){
			$content .= $v['student_code'].','.$v['student_name'].',';//学号,姓名
			
			foreach ($subjects as $subject){
				$hasScore = false;
				foreach ($v as $sub=>$score){
					if($sub==$subject['subject']){
						$content .= $score.',';
						$hasScore = true;
						break;
					}
				}
				if(!$hasScore){
					$content .= ',';//如果没有对应科目的成绩，返回空数据
				}
			}
			
			$content .= $v['total'].',';
			$content .= $v['class_rank'].',';
			$content .= $v['grade_rank'].',';
			$content .= $v['up_down']."\r\n";
		}
	}
	
	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=《".$prj_name."》考试成绩汇总.csv");
	header("Content-Type: application/unknown");
	die($file);
}

///导出考试汇总模板
elseif ($_REQUEST['act'] == 'template')
{
	//考试名称
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试名称
	$prj_name = get_exam_prj_name($prj_id);
	$subjects = get_subjects($class_code, $prj_id);
	$content = '您选择的《'.$prj_name.'》还没有进行考试安排！';
	if(count($subjects)>0){

		$content = "考试名称,考试名称ID,学号,姓名,";//学生学号，科目。。。，年级排名，年级进退
		foreach ($subjects as $v)
		{
			$content .= $v["subject"].',';
		}
		$content .="年级排名,年级进退\r\n";

		$students = get_students($class_code, 'code');
		foreach($students as $s){
			$content .= $prj_name.','.$prj_id.',';//考试名称,考试名称
			$content .= $s['code'].','.$s['name'].',';//学号,姓名
// 			foreach ($subjects as $subject){
// 				$content .= ',';//如果没有对应科目的成绩，返回空数据
// 			}
// 			$content .= ',';
			$content .= "\r\n";
		}
	}

	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);
	$file = ecs_iconv(EC_CHARSET, $charset, $content);

	header("Content-Disposition: attachment; filename=《".$prj_name."》考试成绩导入模板.csv");
	header("Content-Type: application/unknown");
	die($file);
}

/**
 * 按考试名称，对成绩进行汇总
 * 学生学号，科目。。。，总分，年级排名，年级进退
 */
function scoreStatistics($class_code, $prj_id){
	//获取考试科目
	$subjects = get_subjects($class_code, $prj_id);
	if(count($subjects)<1){
		return array();
	}
	//获取考试成绩
	$sql = "select * FROM " . $GLOBALS['ecs']->table("score")  ." WHERE class_code='".$class_code."' AND prj_id='".$prj_id."' " ;
	$scores = $GLOBALS['db']->getAll($sql);
	//获取年级排名
	$sql = "select * FROM " . $GLOBALS['ecs']->table("grade_rank")  ." WHERE class_code='".$class_code."' AND prj_id='".$prj_id."' " ;
	$grade_rank = $GLOBALS['db']->getAll($sql);
	//获取班级学生
	$students = get_students($class_code);
	
	foreach($students as $k=>$s){
		foreach ($scores as $score){
			if($score["student_code"]==$s['code']){
				$students[$k][$score["subject"]]=$score["score"];//科目=》成绩
			}
		}
		foreach ($grade_rank as $rank){
			if($rank["student_code"]==$s['code']){
				$students[$k]['grade_rank']=$rank["grade_rank"];//年级排名，年级进退
				$students[$k]['up_down']=$rank["up_down"];//年级排名，年级进退
				break;
			}
		}
	}
	
	$res = array();//学生学号，科目。。。，总分，年级排名，年级进退
	foreach($students as $s){
		$res[$s['code']]['student_code'] = $s['code'];
		$res[$s['code']]['student_name'] = $s['name'];
	
		$total = 0;
		foreach ($subjects as $subject){
			$hasScore = false;
			foreach ($s as $sub=>$score){
				if($sub==$subject['subject']){
					$res[$s['code']][$sub]=$score;//科目=》成绩
					$total += $score;
					$hasScore = true;
					break;
				}
			}
			if(!$hasScore){
				$res[$s['code']][$subject["subject"]]='';//科目=》成绩
			}
		}
		$res[$s['code']]['total'] = $total;//总分
		$res[$s['code']]['grade_rank'] = $s['grade_rank'];//年级排名，年级进退
		$res[$s['code']]['up_down'] = $s['up_down'];//年级排名，年级进退
	}
	$res = set_rank($res);
// 	print_r($res);
	return $res;
}


//根据总分获取班级排名
function set_rank($scores){
	//根据总分获取班级排名
	$ranks = array();
	foreach($scores as $sc1=>$sv1){
		$class_rank = 1;
		foreach($scores as $sc2=>$sv2){
			if($sv1['total']<$sv2['total']){
				$class_rank++;
			}
		}
		$scores[$sc1]['class_rank'] = $class_rank;
		$ranks[$sc1] = $class_rank;
	}

	//按班级排名进行降序排序
	array_multisort($ranks, SORT_NUMERIC, SORT_ASC, $scores);

	return $scores;
}


/**
 * 删除指定考试名称的所有成绩和排名
 */
function delete_datas($class_code, $prj_id){
	$sql = "delete from ".$GLOBALS['ecs']->table("score")." where class_code='$class_code' and prj_id=".$prj_id;
	$GLOBALS['db']->query($sql);
	
	$sql = "delete from ".$GLOBALS['ecs']->table("grade_rank")." where class_code='$class_code' and prj_id=".$prj_id;
	$GLOBALS['db']->query($sql);
}

/**
 * 批量导入考试汇总
 */
function insert_datas($scores_list){
	//各科成绩
	$sql_score = "insert into ".$GLOBALS['ecs']->table("score")
	." (prj_id,subject,student_code,class_code,score,created )
					values "; 
	
	//年级排名
	$sql_rank = "insert into ".$GLOBALS['ecs']->table("grade_rank")
	." (prj_id,student_code,class_code,grade_rank,up_down,created )
						values "; 
	
	foreach ($scores_list as $k=>$v){
		$subjects = $v['subjects'];
		foreach($subjects as $subject=>$score){
			$sql_score .= "('".$v["prj_id"]."','".$subject."','".$v["student_code"]."','".$v["class_code"]."','".$score."',now()),";
		}
		
		$sql_rank .= "('".$v["prj_id"]."','".$v["student_code"]."','".$v["class_code"]."','".$v["grade_rank"]."','".$v["up_down"]."',now()),";
	}

	if(substr($sql_score, -1)==','){
		$sql_score = substr($sql_score, 0, strlen($sql_score)-1);
		$GLOBALS['db']->query($sql_score);
	}
	
	if(substr($sql_rank, -1)==','){
		$sql_rank = substr($sql_rank, 0, strlen($sql_rank)-1);
		$GLOBALS['db']->query($sql_rank);
	}

}

?>