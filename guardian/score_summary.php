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
		$content .="总分,年级排名,年级进退\r\n";
		
		$res = scoreStatistics($class_code, $prj_id);
		foreach($res as $s=>$v){
			$content .= $s.','.$v['student_name'].',';//学号,姓名
			
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
	$sql = "select * FROM " . $GLOBALS['ecs']->table("score")  ." WHERE class_code='".$class_code."' AND prj_id='".$prj_id."' AND student_code='".$_SESSION['student_code']."'  " ;
	$scores = $GLOBALS['db']->getAll($sql);
	//获取年级排名
	$sql = "select * FROM " . $GLOBALS['ecs']->table("grade_rank")  ." WHERE class_code='".$class_code."' AND prj_id='".$prj_id."' AND student_code='".$_SESSION['student_code']."' " ;
	$grade_rank = $GLOBALS['db']->getRow($sql);
	//获取学生
	$s = get_student($class_code, $_SESSION['student_code']);
	
	$res = array();//学生学号，科目。。。，总分，年级排名，年级进退
	
	$res[$s['code']]['student_code'] = $s['code'];
	$res[$s['code']]['student_name'] = $s['name'];
	$total = 0;
	
	foreach ($subjects as $subject){
		$hasScore = false;
		foreach ($scores as $score){
			if($score['subject']==$subject['subject']){
				$res[$s['code']][$score['subject']]=$score['score'];//科目=》成绩
				$total += $score['score'];
				$hasScore = true;
				break;
			}
		}
		if(!$hasScore){
			$res[$s['code']][$subject["subject"]]='';//科目=》成绩
		}
	}
	
	$res[$s['code']]['total'] = $total;//总分
	$res[$s['code']]['grade_rank'] = $grade_rank['grade_rank'];//年级排名
	$res[$s['code']]['up_down'] = $grade_rank['up_down'];//年级进退
	
// 	print_r($res);
	return $res;
}


?>