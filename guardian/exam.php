<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	set_params();
	$smarty->display('exam_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'import')
{
	/* 将文件按行读入数组，逐行进行解析 */
	$line_number = 0;
	$exams_list = array();
	$data = file($_FILES["importFile"]["tmp_name"]);
	
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
				//考试编号
				if(strpos($v, "科目")>-1){
					$begin_flag = true;
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
		$arr['prj_id'] = trim($line_list[$i++]);
		$arr['subject'] = trim($line_list[$i++]);
		$arr['teacher'] = trim($line_list[$i++]);
		$arr['examdate'] = trim($line_list[$i++]);
		$arr['stime'] = trim($line_list[$i++]);
		$arr['etime'] = trim($line_list[$i++]);
		$arr['classroom'] = trim($line_list[$i++]);
		$arr['class_code'] = $class_code;

		$exams_list[] = $arr;
	}
// 	print_r($exams_list);
	insert_datas($exams_list);

	set_params();
	$smarty->display('exam_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = exam_list();
	make_json($list);
}


elseif ($_REQUEST['act'] == 'ajax_add')
{
	$exam_prj = empty($_REQUEST['exam_prj']) ? "":trim($_REQUEST['exam_prj']);
	$exam_subjects = empty($_REQUEST['exam_subjects']) ? "":trim($_REQUEST['exam_subjects']);
	$exam_subjects = explode("###SPLIT_V1###", $exam_subjects);
	
	$total = 0;
	foreach($exam_subjects as $subject){
		
			$item_attr = explode("###SPLIT_V2###", $subject);
			if(!$item_attr[0] || count($item_attr)<6){
				continue;
			}
				
			$sql = "insert into ".$ecs->table("exam")
		." (prj_id,class_code,subject,
			teacher,examdate,stime,etime,classroom,created )
		values 
			('".$exam_prj."','".$_SESSION["class_code"]."',
			'".$item_attr[0]."','".$item_attr[1]."',
			'".$item_attr[2]."','".$item_attr[3]."','".$item_attr[4]."',
			'".$item_attr[5]."',
			now())";

			$db->query($sql);
			admin_log(addslashes($_REQUEST["prj_id"].$_REQUEST["subject"]), 'add', 'exam');
			$total++;
	}
	make_json_result("添加成功！共添加数据".$total."条!");
}


elseif ($_REQUEST['act'] == 'ajax_save')
{
		$id    = !empty($_REQUEST['exam_id'])        ? intval($_REQUEST['exam_id'])      : 0;
		$sql = "update ".$ecs->table("exam")
		." set prj_id='".$_REQUEST["prj_id"]."',
			subject='".$_REQUEST["subject"]."',
			teacher='".$_REQUEST["teacher"]."',
			examdate='".$_REQUEST["examdate"]."',
			stime='".$_REQUEST["stime"]."',
			etime='".$_REQUEST["etime"]."',
			classroom='".$_REQUEST["classroom"]."'
			where exam_id=".$id;
		
		$db->query($sql);
		admin_log(addslashes($_REQUEST["prj_id"].$_REQUEST["subject"]), 'update', 'exam');
		make_json_result("修改成功！");
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['exam_id'])        ? trim($_REQUEST['exam_id'])      : "";
	$sql = "delete from ".$ecs->table("exam")." where exam_id in (".$id.")";
	$db->query($sql);
	
	admin_log($_REQUEST["exam_id"], 'delete', 'exam');
	
	make_json_result("删除成功！");
}

elseif ($_REQUEST['act'] == 'getSmsContent')
{
	$prj_id    = !empty($_REQUEST['prj_id'])        ? trim($_REQUEST['prj_id'])      : "";
	
	$sql = "select * from ".$ecs->table("exam")." where prj_id='".$prj_id."'";
	$rows = $db->getAll($sql);
	
	$content = "【《".get_exam_prj_name($prj_id)."》考试安排】";
	foreach ($rows as $row){
		$content.=$row["examdate"].','.substr($row["stime"],0,5).'-'.substr($row["etime"],0,5).'在'.$row["classroom"].'考试'.$row["subject"].'；';
	}
	make_json(array("error"=>0,"msg"=>$content));
}


elseif ($_REQUEST['act'] == 'publish')
{
	$prj_id    = !empty($_REQUEST['prj_id'])        ? trim($_REQUEST['prj_id'])      : "";
	
	$sql = "select * from ".$ecs->table("exam")." where prj_id='".$prj_id."'";
	$rows = $db->getAll($sql);
	
	$title = "《".get_exam_prj_name($prj_id)."》考试安排";
	$notice = '<table cellspacing="0" cellpadding="0" style="width:100%"><tbody>';
	$notice .= '<tr style="font-weight:bold;">';
	$notice .= '<td style="text-align:center;width:15%;border:1px solid rgb(27, 240, 180)">考试科目</td>';
	$notice .= '<td style="text-align:center;width:15%;border:1px solid rgb(27, 240, 180)">监考老师</td>';
	$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">考试日期</td>';
	$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">考试起止时间</td>';
	$notice .= '<td style="text-align:center;width:20%;border:1px solid rgb(27, 240, 180)">所在教室</td>';
	$notice .= '</tr>';
	
	foreach ($rows as $row){
		$notice .= '<tr>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$row["subject"].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$row["teacher"].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$row["examdate"].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$row["stime"].'-'.$row["etime"].'</td>';
		$notice .= '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'.$row["classroom"].'</td>';
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





/**
 *  返回班级管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function exam_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['prj_id'] = empty($_REQUEST['search_prj']) ? '' : trim($_REQUEST['search_prj']);//名称
		$filter['subject'] = empty($_REQUEST['search_subject']) ? '' : trim($_REQUEST['search_subject']);//科目
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'exam_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['prj_id'])
		{
			$ex_where .= " AND prj_id like '" . mysql_like_quote($filter['prj_id']) ."%'";
		}
		if ($filter['subject'])
		{
			$ex_where .= " AND subject like '" . mysql_like_quote($filter['subject']) ."%'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("exam") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("exam")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

		$filter['semster'] = stripslashes($filter['semster']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);
	foreach($list as $key=>$val){
		$list[$key]['setime']     = $val['stime']."-".$val['etime'];
		$list[$key]['prj_name']     = get_exam_prj_name($val["prj_id"]);
	}

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



function insert_datas($exams_list){

	$sql = "insert into ".$GLOBALS['ecs']->table("exam")
	." (prj_id,class_code,subject,
				teacher,examdate,stime,etime,classroom,created )
			values ";
	;
	foreach ($exams_list as $k=>$v){
		$sql .= "('".$v["prj_id"]."','".$v["class_code"]."',
		'".$v["subject"]."','".$v["teacher"]."',
		'".$v["examdate"]."','".$v["stime"]."','".$v["etime"]."',
		'".$v["classroom"]."',
		now())";
		
		if($k<(sizeof($exams_list)-1)){
			$sql .= ",";
		}
	}
// 	print_r($sql);
	
	$GLOBALS['db']->query($sql);
}


?>