<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	//考试名称
	$prjs = get_exam_prjs($_SESSION["class_code"]);
	$smarty->assign("prjs", $prjs);
	
	$smarty->display('score_publish_list.htm');
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

/// 根据考试名称导出成绩
elseif ($_REQUEST['act'] == 'publish')
{
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试名称
	$prj_name = get_exam_prj_name($prj_id);
	$subjects = get_subjects($class_code, $prj_id);//获取这个项目所有的考试科目
	
	$title = "《".$prj_name."》考试成绩单";
	$notice = '<table cellspacing="0" cellpadding="0" style="width:100%"><tbody>';
	$notice .= '<tr style="font-weight:bold;">';
	$notice .= '<td style="text-align:center;width:5%;border:1px solid rgb(27, 240, 180)">学号</td>';
	$notice .= '<td style="text-align:center;width:10%;border:1px solid rgb(27, 240, 180)">姓名</td>';
	foreach($subjects as $k=>$v){
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v["subject"].'</td>';
	}
	$notice .= '<td style="text-align:center;width:8%;border:1px solid rgb(27, 240, 180)">总分</td>';
	$notice .= '<td style="text-align:center;width:8%;border:1px solid rgb(27, 240, 180)">年级排名</td>';
	$notice .= '<td style="text-align:center;width:8%;border:1px solid rgb(27, 240, 180)">年级进退</td>';
	$notice .= '</tr>';
	
	$res = scoreStatistics($class_code, $prj_id);
	
	foreach($res as $k=>$v){
		$notice .= '<tr>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$k.'</td>';//学号,姓名
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v["student_name"].'</td>';
		foreach($subjects as $sv){
			$hasScore = false;
			foreach ($v as $sub=>$score){
				if($sub==$subject['subject']){
					$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$score.'</td>';
					$hasScore = true;
					break;
				}
			}
			if(!$hasScore){
				//如果没有对应科目的成绩，返回空数据
				$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)"></td>';
			}
		}
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v['total'].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v['grade_rank'].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v['up_down'].'</td>';
		$notice .= '</tr>';
	}
	$notice .= "</tbody></table>";
	
	$sql = "INSERT INTO ".$ecs->table('notice')."(title, urgency, class_code, content, author, ".
				                "is_active, created) ".
				            "VALUES ('$title', 1, '$_SESSION[class_code]', '$notice', ".
				                "'$_SESSION[admin_name]', 1, now())";
	$db->query($sql);
	$notice_id = $db->insert_id();
	
	admin_log($title, 'add', 'notice:'.$title);
	
	ecs_header("Location:notice.php?act=view&show_back=1&notice_id=$notice_id\n");
	exit();
}


/// 根据学生学号导出成绩
elseif ($_REQUEST['act'] == 'sendSMS')
{
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试名称
	$prj_name = get_exam_prj_name($prj_id);
	$subjects = get_subjects($class_code, $prj_id);//获取这个项目所有的考试科目
	if(!$subjects){
		die('<p style="color:red;">您选择的考试名称《'.$prj_name.'》下面没有成绩数据，清选择有效的考试名称！</p>');
	}
	
	$res = scoreStatistics($class_code, $prj_id);
	
	$msg = "";
	foreach($res as $k=>$v){
		
		///给注册了的用户发送短信
		$sql = "select * from ".$GLOBALS["ecs"]->table("student")." where code='".$k."' limit 1";
		$row = $GLOBALS["db"]->getRow($sql);
		
		$content = '亲爱的'.$row["guardian_name"].'家长,您的孩子'.$v["student_name"].'，在本次《'.$prj_name.'》中的成绩如下：';
		foreach($subjects as $sk=>$sv){
			foreach ($v as $sub=>$score){
				if($sub==$sv['subject']){
					$content.=$sub.($score?$score:0).',';
					break;
				}
			}
		}
		$content.= '总分:'.$v['total'];
		if($v['grade_rank']){
			$content .= ',年级排名:'.$v['grade_rank'];
		}
		if($v['up_down']){
			$content .= ',年级进退:'.$v['up_down'];
		}
		
		if($row['is_active'] && !$row["has_left"]){
			require_once(ROOT_PATH . '/includes/cls_sms.php');
			$sms = new sms();
// 			$result = $sms->send($row['guardian_phone'], $content, $school_code, $class_code, $_SESSION["admin_name"]);
			
			if($result["error"]==0){
				$msg.='<p style="color:green;">向'.$k."-".$v["student_name"]."同学的家长发送短信成功！短信内容：<br>".$content."<p>";
			}else {
				$msg.='<p style="color:red;">向'.$k."-".$v["student_name"]."同学的家长发送短信失败！失败原因：".$result["msg"]."。短信内容：<br>".$content."<p>";
			}
		}else {
			$msg.='<p style="color:red;">'.$k."-".$v["student_name"]."同学未注册，或者未填写有效的手机号码，不能收到以下短信：<br>".$content."<p>";
		}
	}
	
	die($msg);
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
	
// 	print_r($res);
	return $res;
}

?>