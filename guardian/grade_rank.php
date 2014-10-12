<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	set_params();
	$smarty->display('grade_rank_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = grade_rank_list();
	make_json($list);
}

/**
 * 批量添加成绩
 */
elseif ($_REQUEST['act'] == 'ajax_add')
{
	$exam_prj = empty($_REQUEST["add_prj_id"])?"":trim($_REQUEST["add_prj_id"]);
	if(!$exam_prj){
		make_json_error("参数有误！");
		exit;
	}
	$students = get_students($class_code);
	
	$grade_ranks = array();
	foreach ($students as $s){
		$rank = empty($_REQUEST["add_rank_".$s["code"]])?"":trim($_REQUEST["add_rank_".$s["code"]]);//获取年级排名
		$up_down = empty($_REQUEST["add_up_down_".$s["code"]])?"":trim($_REQUEST["add_up_down_".$s["code"]]);//获取年级进退
		
		if($rank || $up_down){
			$grade_ranks[$s["code"]]["rank"] = $rank;
			$grade_ranks[$s["code"]]["up_down"] = $up_down;
			//检查是否重复录入成绩
			$sql = "select * from ".$ecs->table("grade_rank")." where prj_id=$exam_prj and student_code='".$s["code"]."' and class_code='$class_code' " ;
			$oldScore = $db->getRow($sql);
			if($oldScore){
				make_json_error("学生：“".$s["code"]."-".$s["name"]."”，在《".get_exam_prj_name($exam_prj)."》中的年级排名和年级进退于".$oldScore["created"]."已经录入到了系统，录入的年级排名为“".$oldScore["grade_rank"]."”,年级进退为“".$oldScore["up_down"]."”，请勿重复录入！");
				exit;
			}
		}
	}
	
	if(count($grade_ranks)<1){
		make_json_error("请填写数据！");
		exit;
	}
	
	//插入数据
	$sql = "insert into ".$ecs->table("grade_rank")." (prj_id, student_code, class_code, grade_rank, up_down, created) values ";
	$i=count($grade_ranks);
	foreach($grade_ranks as $s=>$v){
		$sql.="($exam_prj, '$s', '$class_code',  '".$v["rank"]."', '".$v["up_down"]."', now())";
		$i--;
		if($i>0){
			$sql.=",";
		}
	}
	$db->query($sql);
	
	make_json_result("成功插入".count($grade_ranks)."条数据");
	exit;
}

elseif ($_REQUEST['act'] == 'import')
{
	/* 将文件按行读入数组，逐行进行解析 */
	$line_number = 0;
	$grade_ranks_list = array();
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
			foreach($line_list as $k=>$v){//学生姓名
				if(strpos($v, "分数")>-1){
					$begin_flag = true;
					$line_number++;
					break;
				}
			}
			if(!$begin_flag){	//定位起始行
				$line_number++;
			}
			continue;
		}
		
		$i=1;
		$arr['exam_subject'] = replace_quote($line_list[$i++]);//考试编号
		$arr['student_code'] = replace_quote($line_list[$i++]);
		
		$grade_rank = replace_quote($line_list[$i++]);
		$arr['grade_rank'] = $grade_rank?intval($grade_rank):0;
		
		$add_grade_rank = replace_quote($line_list[$i++]);
		$arr['add_grade_rank'] = $add_grade_rank?intval($add_grade_rank):0;
		
		$arr['class_code'] = $class_code;
		
		$grade_ranks_list[] = $arr;
	}
	
	insert_datas($grade_ranks_list);
	
	set_params();
	$smarty->display('grade_rank_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id = !empty($_REQUEST['grank_id'])? intval($_REQUEST['grank_id']): 0;
	$exam_prj = !empty($_REQUEST['exam_prj'])? intval($_REQUEST['exam_prj']): 0;
	$student = !empty($_REQUEST['student_code'])? trim($_REQUEST['student_code']): "";
	
	//检查是否重复录入成绩
	$sql = "select * from ".$ecs->table("grade_rank")." where prj_id=$exam_prj and student_code='$student' and class_code='$class_code' and grank_id!=".$id ;
	$oldScore = $db->getRow($sql);
	if($oldScore){
		make_json_error("学号：“".$student."”，在《".get_exam_prj_name($exam_prj)."》中的年级排名和年级进退于".$oldScore["created"]."已经录入到了系统，录入的年级排名为“".$oldScore["grade_rank"]."”,年级进退为“".$oldScore["up_down"]."”，请勿重复录入！");
		exit;
	}
	
	if($id==0){//insert

		$sql = "insert into ".$ecs->table("grade_rank")
		." (prj_id, class_code, student_code, grade_rank, up_down, created )
		values 
			($exam_prj,'".$_SESSION["class_code"]."',
			'".$student."','".$_REQUEST["grade_rank"]."', '".$_REQUEST["up_down"]."',now())";
		
		$db->query($sql);
		admin_log(addslashes($student), 'add', $sql);
		make_json_result("添加成功！");
	}
	else //update
	{
		$sql = "update ".$ecs->table("grade_rank")
		." set student_code='".$_REQUEST["student_code"]."',
			prj_id='".$exam_prj."',
			grade_rank='".$_REQUEST["grade_rank"]."',
			up_down='".$_REQUEST["up_down"]."'
			where grank_id=".$id;
		
		$db->query($sql);
		admin_log(addslashes($_REQUEST["student_code"]), 'update', $sql);
		make_json_result("修改成功！");
	}
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['grank_id'])        ? trim($_REQUEST['grank_id'])      : "";
	$sql = "delete from ".$ecs->table("grade_rank")." where grank_id in (".$id.")";
	$db->query($sql);
	
	admin_log($_REQUEST["grank_id"], 'delete', 'grade_rank');
	make_json_result("删除成功！");
}

/// 根据查询条件导出成绩
elseif ($_REQUEST['act'] == 'export')
{
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//学生学号
	
	$list = get_grade_ranks($class_code, $prj_id, $_SESSION["student_code"], " s.prj_id");
	
	$content = "考试名称,学号,姓名,年级排名,年级进退\r\n";
	foreach ($list as $k=>$v)
	{
		$content .= $v["prj_name"].",".$v["student_code"].",".$v["student_name"].",".$v["grade_rank"].",".$v["up_down"]."\r\n";
	}
	
	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);
	$file = ecs_iconv(EC_CHARSET, $charset, $content);

	header("Content-Disposition: attachment; filename=grade_rank.csv");
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
function grade_rank_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['prj_id'] = empty($_REQUEST['search_prj_id']) ? '' : trim($_REQUEST['search_prj_id']);//考试名称
		
		$filter['sort']    = empty($_REQUEST['sort'])    ? 'grank_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE s.class_code='".$_SESSION["class_code"]."' and s.student_code='".$_SESSION["student_code"]."' ";
		if ($filter['prj_id'])
		{
			$ex_where .= " AND  s.prj_id='".$filter['prj_id']."'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("grade_rank") ." s ". $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT s.*, p.name as prj_name,st.name as student_name ".
                " FROM " . $GLOBALS['ecs']->table("grade_rank")  ." s 
				left join ".$GLOBALS['ecs']->table("student") ." st on s.student_code=st.code and st.class_code='".$_SESSION["class_code"]."' 
				left join ".$GLOBALS['ecs']->table("exam_prj") ." p on s.prj_id=p.prj_id  
				". $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
// 		echo $sql;echo '<br>';
		
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



function insert_datas($grade_ranks_list){
	$sql = "insert into ".$GLOBALS['ecs']->table("grade_rank")
	." (class_code,exam_subject,student_code,grade_rank,add_grade_rank,created )
					values "; 
					
	foreach ($grade_ranks_list as $k=>$v){
		
		$sql .= "('".$v["class_code"]."','".$v["exam_subject"]."',
		'".$v["student_code"]."','".$v["grade_rank"]."',
		'".$v["add_grade_rank"]."',now())";
		if($k<(sizeof($grade_ranks_list)-1)){
			$sql .= ",";
		}
		
	}
	
	$GLOBALS['db']->query($sql);
	
}


?>