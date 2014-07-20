<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$smarty->display('exam_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'import')
{
	/* 将文件按行读入数组，逐行进行解析 */
	$line_number = 0;
	$students_list = array();
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
			foreach($line_list as $k=>$v){
				//学生姓名
				if(strpos($v, "考试名称")>-1){
					$bigan_flag = true;
					$line_number++;
					break;
				}
			}
			if(!$bigan_flag){
				//定位起始行
				$line_number++;
			}
			continue;
		}

		$i=1;
		$arr['code'] = replace_dbquote($line_list[$i++]);//学号
		$arr['name'] = trim($line_list[$i++]);//学生信息
		$arr['subject'] = trim($line_list[$i++]);
		$arr['teacher'] = trim($line_list[$i++]);
		$arr['examdate'] = replace_dbquote($line_list[$i++]);
		$arr['stime'] = trim($line_list[$i++]);
		$arr['etime'] = trim($line_list[$i++]);
		$arr['classroom'] = trim($line_list[$i++]);
		$arr['class_code'] = $class_code;

		$exams_list[] = $arr;
	}
	insert_datas($exams_list);

	$smarty->display('exam_list.htm');
	exit;
}


if ($_REQUEST['act'] == 'ajax_list')
{
	$list = exam_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['exam_id'])        ? intval($_REQUEST['exam_id'])      : 0;
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("exam")
		." (code,name,class_code,subject,
			teacher,examdate,stime,etime,classroom,closed,created )
		values 
			('".$_REQUEST["code"]."','".$_REQUEST["name"]."','".$_SESSION["class_code"]."',
			'".$_REQUEST["subject"]."','".$_REQUEST["teacher"]."',
			'".$_REQUEST["examdate"]."','".$_REQUEST["stime"]."','".$_REQUEST["etime"]."',
			'".$_REQUEST["closed"]."','".$_REQUEST["classroom"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["code"]), 'add', 'exam');
		
		make_json_result("添加“".$_REQUEST["code"]."”成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("exam")
		." set name='".$_REQUEST["name"]."',
			code='".$_REQUEST["code"]."',
			subject='".$_REQUEST["subject"]."',
			teacher='".$_REQUEST["teacher"]."',
			examdate='".$_REQUEST["examdate"]."',
			stime='".$_REQUEST["stime"]."',
			etime='".$_REQUEST["etime"]."',
			closed='".$_REQUEST["closed"]."',
			classroom='".$_REQUEST["classroom"]."'
			where exam_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["code"]), 'update', 'exam');
		
		make_json_result("修改“".$id.",".$_REQUEST["code"]."”成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['exam_id'])        ? intval($_REQUEST['exam_id'])      : 0;
	$sql = "delete from ".$ecs->table("exam")." where exam_id=".$id;
	
	$db->query($sql);
	
	admin_log($_REQUEST["exam_id"], 'delete', 'exam');
	
	make_json_result("删除成功！");
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
		$filter['code'] = empty($_REQUEST['search_code']) ? '' : trim($_REQUEST['search_code']);//编号
		$filter['name'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		$filter['subject'] = empty($_REQUEST['search_subject']) ? '' : trim($_REQUEST['search_subject']);//科目
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'exam_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '15'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['code'])
		{
			$ex_where .= " AND code like '" . mysql_like_quote($filter['code']) ."%'";
		}
		if ($filter['name'])
		{
			$ex_where .= " AND name like '" . mysql_like_quote($filter['name']) ."%'";
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
	}

	$arr = array('rows' => $list, 'filter' => $filter,
        'page' => $filter['page_count'], 'total' => $filter['record_count']);

	return $arr;
}



function insert_datas($exams_list){

	$sql = "insert into ".$GLOBALS['ecs']->table("exam")
	." (code,name,class_code,subject,
				teacher,examdate,stime,etime,classroom,created )
			values ";
		
	foreach ($exams_list as $k=>$v){
		$sql .= "('".$v["code"]."','".$v["name"]."','".$v["class_code"]."',
		'".$v["subject"]."','".$v["teacher"]."',
		'".$v["examdate"]."','".$v["stime"]."','".$v["etime"]."',
		'".$v["classroom"]."',
		now())";
		
		if($k<(sizeof($exams_list)-1)){
			$sql .= ",";
		}
	}
	$GLOBALS['db']->query($sql);
}


?>