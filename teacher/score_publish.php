<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	//考试项目
	$prjs = get_exam_prjs($_SESSION["class_code"]);
	$smarty->assign("prjs", $prjs);
	
	$smarty->display('score_publish_list.htm');
	exit;
}


/// 根据考试项目导出成绩
elseif ($_REQUEST['act'] == 'publish')
{
	$prj_code = empty($_REQUEST['prj_code']) ? '' : trim($_REQUEST['prj_code']);//考试项目
	//获取这个项目所有的考试科目
	$subjects = get_subjects_by_exam($class_code, $prj_code);
	
	$title = "《".$prj_code."》考试成绩单";
	
	$notice = '<table cellspacing="0" cellpadding="0" style="width:100%"><tbody>';
	$notice .= '<tr style="font-weight:bold;">';
	$notice .= '<td style="text-align:center;width:20%;border:1px solid rgb(27, 240, 180)">学号</td>';
	$notice .= '<td style="text-align:center;width:15%;border:1px solid rgb(27, 240, 180)">姓名</td>';
	foreach($subjects as $k=>$v){
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v["code"].'</td>';
	}
	$notice .= '<td style="text-align:center;width:20%;border:1px solid rgb(27, 240, 180)">总分</td>';
	$notice .= '</tr>';
	
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
	
	foreach($students as $k=>$v){
		$notice .= '<tr>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v["student_code"].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$v["student_name"].'</td>';
		$total = 0;
		foreach($subjects as $sk=>$sv){
			$score = intval($v[$sv["code"]]);
			$total += $score;
			$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$score.'</td>';
		}
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$total.'</td>';
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
	$prj_code = empty($_REQUEST['prj_code']) ? '' : trim($_REQUEST['prj_code']);//考试项目
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
				break;
			}
		}
		if(!$isExsit){
			$students[$student["student_code"]] = $student;
		}
	}
	
	$msg = "";
	//获取这个项目所有的考试科目
	$subjects = get_subjects_by_exam($class_code, $prj_code);
	if(!$subjects){
		die('<p style="color:red;">您选择的考试项目《'.$prj_code.'》下面没有成绩数据，清选择有效的考试项目！</p>');
	}
	foreach($students as $k=>$v){
		$content = '【'.$v["student_name"].'《'.$prj_code.'》考试成绩】';
		$total = 0;
		foreach($subjects as $sk=>$sv){
			$score = intval($v[$sv["code"]]);
			$total += $score;
			$content.=$sv["subject"].':'.$score.',';
		}
		$content.= '总分:'.$total;
		
		///给注册了的用户发送短信
		$sql = "select * from ".$GLOBALS["ecs"]->table("student")." where code='".$v["student_code"]."' and is_active=1 and has_left=0 and length(guardian_phone)>6 limit 1";
		$row = $GLOBALS["db"]->getRow($sql);
		
		if($row){
			require_once(ROOT_PATH . '/includes/cls_sms.php');
			$sms = new sms();
			$result = $sms->send($row['guardian_phone'], $content, $school_code, $class_code, $_SESSION["admin_name"]);
			
			if($result["error"]==0){
				$msg.='<p style="color:green;">向'.$v["student_code"]."-".$v["student_name"]."家长发送短信成功！短信内容：<br>".$content."<p>";
					
			}else {
				$msg.='<p style="color:red;">向'.$v["student_code"]."-".$v["student_name"]."家长发送短信失败！失败原因：".$result["msg"]."。短信内容：<br>".$content."<p>"
				;
			}
		}else {
			$msg.='<p style="color:red;">'.$v["student_code"]."-".$v["student_name"]."未注册，或者未填写有效的手机号码，不能收到以下短信：<br>".$content."<p>";
		}
	}
	
	die($msg);
}

?>