<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ($_REQUEST['act'] == 'list')
{
	$students = get_students($class_code);
	$smarty->assign("tmp_students", $students);
	
	//将学生数据每十个一行进行处理
	$stds = array();
	$j = 0;
	for($i=0;$i<count($students);$i++){
		$stds[$j][] = $students[$i];
		if(($i+1)%10==0){
			$j++;
		}
	}
	
	$smarty->assign("students", $stds);
	
	$duty_items = get_duty_items($class_code);
	$smarty->assign("duty_items", $duty_items);
	
	$smarty->display('duty_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = duty_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_add')
{
	$students = empty($_REQUEST['students']) ? "":trim($_REQUEST['students']);	
	$items = empty($_REQUEST['duty_items']) ? "":trim($_REQUEST['duty_items']);
	
	$student_arry = explode(",", $students);
	$item_arry = explode("###SPLIT_V1###", $items);
	
	$total = 0;
	foreach($student_arry as $student){
		foreach($item_arry as $item){
			$item_attr = explode("###SPLIT_V2###", $item);
			if(!$item_attr[0] || count($item_attr)<4){
				continue;
			}
			
			$sql = "insert into ".$ecs->table("duty")
			." (student_code,duty_item,
								score,date_,desc_,class_code,created )
							values 
								('".$student."','".$item_attr[0]."',
								'".$item_attr[1]."','".$item_attr[2]."',
								'".$item_attr[3]."','".$_SESSION["class_code"]."',
								now())";
			$db->query($sql);
			admin_log(addslashes($student.':'.$item_attr[0]), 'add', "duty_item");
			$total++;
		}
	}
	make_json_result("添加成功！共添加数据".$total."条!");
}


elseif ($_REQUEST['act'] == 'ajax_save')
{
	$id    = !empty($_REQUEST['duty_id'])        ? intval($_REQUEST['duty_id'])      : 0;
	$item = empty($_REQUEST['duty_item']) ? "":trim($_REQUEST['duty_item']);
	
	if($id==0){//insert
		
		$sql = "insert into ".$ecs->table("duty")
		." (student_code,duty_item,
			score,date_,desc_,class_code,created )
		values 
			('".$_REQUEST["student_code"]."','".$item."',
			'".$_REQUEST["score"]."','".$_REQUEST["date_"]."',
			'".$_REQUEST["desc_"]."','".$_SESSION["class_code"]."',
			now())";
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["student_code"].$_REQUEST["duty_item"]), 'add', $sql);
		
		make_json_result("添加成功！");
		
	}
	
	else //update
	{
		$sql = "update ".$ecs->table("duty")
		." set student_code='".$_REQUEST["student_code"]."',
			duty_item='".$item."',
			score='".$_REQUEST["score"]."',
			date_='".$_REQUEST["date_"]."',
			desc_='".$_REQUEST["desc_"]."'
			where duty_id=".$id;
		
		$db->query($sql);
		
		admin_log(addslashes($_REQUEST["student_code"].$_REQUEST["duty_item"]), 'update', $sql);
		
		make_json_result("修改成功！");
		
	}
	
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['duty_id'])        ? trim($_REQUEST['duty_id'])      : "";
	$sql = "delete from ".$ecs->table("duty")." where duty_id in (".$id.")";
	
	$db->query($sql);
	
	admin_log($_REQUEST["duty_id"], 'delete', 'duty');
	
	make_json_result("删除成功！");
}

elseif ($_REQUEST['act'] == 'exportdutys')
{
	$content = "学号,姓名,量化项目,记录分数 ,事发日期 ,备注描述 ,创建时间 \r\n";
	
	$list = duty_list();
	
	foreach($list["rows"] as $k=>$v){
		$content .= $v["student_code"].",".$v["student_name"].",".$v["duty_item"].",".$v["score"].",".$v["date_"].",".$v["desc_"].",".$v["created"]."\r\n";
	}
	
	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);//UTF8
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=duty_list.csv");
	header("Content-Type: application/unknown");
	die($file);
}

elseif ($_REQUEST['act'] == 'exportRank')
{
	$content = "学号,姓名,总分,排名 \r\n";
	
	$sdate = empty($_REQUEST['search_sdate']) ? '' : trim($_REQUEST['search_sdate']);//起始日期
	$edate = empty($_REQUEST['search_edate']) ? '' : trim($_REQUEST['search_edate']);//截止日期
	
	$sql = "select d.student_code, s.name as student_name, sum(d.score) as total from ".$GLOBALS['ecs']->table("duty")." d 
			left join ".$ecs->table("student")." s on s.code=d.student_code and s.class_code='".$class_code."' 
			WHERE d.date_ between '".$sdate."' and '".$edate."'  and d.class_code='".$class_code."' 
 			group by d.student_code order by total desc";
	$rows = $db->getAll($sql);
	
	$i=1;
	foreach($rows as $k=>$v){
		$content .= $v["student_code"].",".$v["student_name"].",".$v["total"].",".($i++)."\r\n";
	}

	$charset = empty($_REQUEST['charset']) ? 'UTF8' : trim($_REQUEST['charset']);
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=".$sdate."To".$edate."Duty Rank.csv");
	header("Content-Type: application/unknown;charset=utf-8");
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
function duty_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['student_code'] = empty($_REQUEST['search_student_code']) ? '' : trim($_REQUEST['search_student_code']);//编号
		$filter['name'] = empty($_REQUEST['search_name']) ? '' : trim($_REQUEST['search_name']);//名称
		$filter['sdate'] = empty($_REQUEST['search_sdate']) ? '' : trim($_REQUEST['search_sdate']);//起始日期
		$filter['edate'] = empty($_REQUEST['search_edate']) ? '' : trim($_REQUEST['search_edate']);//截止日期
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['name'] = json_str_iconv($filter['name']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'duty_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE d.class_code='".$_SESSION["class_code"]."' ";
		if ($filter['student_code'])
		{
			$ex_where .= " AND d.student_code = '" . mysql_like_quote($filter['student_code']) ."'";
		}
		if ($filter['name'])
		{
			$ex_where .= " AND d.duty_item like '" . mysql_like_quote($filter['name']) ."%'";
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND d.date_ >='" . mysql_like_quote($filter['sdate']) ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND d.date_ <='" . mysql_like_quote($filter['edate']) ."'";
		}
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("duty") ." d ". $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT d.*, s.name as student_name ".
                " FROM " . $GLOBALS['ecs']->table("duty")  ." d left join ". $GLOBALS['ecs']->table("student")." s on d.student_code=s.code and d.class_code=s.class_code ". $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

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