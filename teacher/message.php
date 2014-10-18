<?php

/**
 *	系统内部消息：
 *	msg_type：0 表示问题， 1表示回复
 *	to_： 发送之某个人的id
 *	to_type： 消息创建人的类型： guardian家长， admin管理员
 *	from_: 消息创建人id
 *  from_type： 消息创建人的类型： guardian家长， admin管理员
 * 
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');


/*初始化数据交换对象 */
$table = $ecs->table("message");

/*------------------------------------------------------ */
//-- 列出所有$from的留言
/*------------------------------------------------------ */
if ($_REQUEST['act']=="list")
{
	$guardians = get_guardians($class_code);
	$smarty->assign("guardians",$guardians);
	$admin_id = $_SESSION[admin_id];
	$smarty->assign("admin_id",$admin_id);
	$smarty->display('message_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = msg_list($table);
	make_json($list);
}


elseif ($_REQUEST['act'] == 'ajax_add')
{
	$sql = "INSERT INTO " . $table . "(fid, message, msg_type, to_, to_type,
	                                                 from_, from_type, class_code, created)" .
	            " VALUES (".$_REQUEST['fid'].",'".$_REQUEST['message']."', '".$_REQUEST['msg_type']."',
	            '".$_REQUEST['to_']."','".$_REQUEST['to_type']."',
	            '".$_REQUEST['from_']."','".$_REQUEST['from_type']."',
	             '$class_code', now())";
	$db->query($sql);
	admin_log(addslashes($_REQUEST["message"]), 'add', $sql);
	make_json_result("添加消息成功！");
}

elseif ($_REQUEST['act'] == 'view')
{
	$id    = !empty($_REQUEST['message_id'])        ? intval($_REQUEST['message_id'])      : 0;
	$sql = "select * from ".$ecs->table("message")." where message_id=".$id;
	$row = $db->getRow($sql);
	if(!$row){
		die("您访问的消息不存在！");
	}
	if(($row['to_']!=$_SESSION['admin_id'] && $row['to_type']=="admin") && ($row['from_']!=$_SESSION['admin_id'] && $row['from_type']=="admin")){
		die("您访问的不属于您自己的消息！");
	}
	if($row['msg_type']==0){
		//这是问题
		$sql = "select * from ".$ecs->table("message")." where fid=".$id." order by message_id ";
		$replys = $db->getAll($sql);

	}else {//这是回答

		$sql = "select * from ".$ecs->table("message")." where message_id=".$row['fid'];
		$row = $db->getRow($sql);

		$sql = "select * from ".$ecs->table("message")." where fid=".$row['fid']." order by message_id ";
		$replys = $db->getAll($sql);
	}
	$row['from_user'] = get_user_name($row['from_'], $row['from_type']);
	$smarty->assign("question",$row);
	$smarty->assign("replys",$replys);

	//如果不是自己提的问题，支持回复
	$not_mine = true;
	if($row['from_type']=='admin' && $row['from_']==$_SESSION['admin_id']){
		$not_mine = false;
	}
	$smarty->assign("not_mine",$not_mine);
	$smarty->display('message_show.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'reply')
{
	$sql = "INSERT INTO " . $table . "(fid, message, msg_type, to_, to_type,
	                                                 from_, from_type, class_code, created)" .
	            " VALUES (".$_REQUEST['fid'].",'".$_REQUEST['message']."', '".$_REQUEST['msg_type']."',
	            '".$_REQUEST['to_']."','".$_REQUEST['to_type']."',
	            '".$_REQUEST['from_']."','".$_REQUEST['from_type']."',
	             '$class_code', now())";
	$db->query($sql);

	admin_log(addslashes($_REQUEST["message"]), 'add', $sql);
	header("Location: message.php?act=view&message_id=".$_REQUEST['fid']."\n");
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['message_id'])        ? intval($_REQUEST['message_id'])      : 0;
	$sql = "delete from ".$table." where fid=$id or message_id=".$id;
	$db->query($sql);
	admin_log($_REQUEST["message_id"], 'delete', $sql);
	make_json_result("删除成功！");
}



/**
 *
 *
 * @access  public
 * @param
 * *	msg_type：0 表示问题， 1表示回复
	 *	to_： 发送之某个人的id
	 *	to_type： 消息创建人的类型： guardian家长， admin管理员
	 *	from_: 消息创建人id
	 *  from_type： 消息创建人的类型： guardian家长， admin管理员
 * @return void
 */
function msg_list($table)
{
    /* 过滤条件 */
    $filter['keywords']   = empty($_REQUEST['search_keywords']) ? '' : trim($_REQUEST['search_keywords']);
    $filter['msg_type']   = isset($_REQUEST['search_msg_type']) ? intval($_REQUEST['search_msg_type']) : 0;//msg_type：0 表示问题， 1表示回复
    $filter['to_']   = isset($_REQUEST['search_to_']) ? intval($_REQUEST['search_to_']) : -1;
    $filter['to_type']   = empty($_REQUEST['search_to_type']) ? 'guardian' : trim($_REQUEST['search_to_type']);
    $filter['from_']   = isset($_REQUEST['search_from_']) ? intval($_REQUEST['search_from_']) : -1;
    $filter['from_type']   = empty($_REQUEST['search_from_type']) ? 'guardian' : trim($_REQUEST['search_from_type']);
    
    if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
    {
        $filter['keywords'] = json_str_iconv($filter['keywords']);
    }
    
    $filter['sort']    = empty($_REQUEST['sort']) ||  trim($_REQUEST['sort'])=='msg_reply'  ? 'msg_id' : trim($_REQUEST['sort']);
    $filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
    $filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
    $filter['page_size']	= empty($_REQUEST['rows']) ? '25'     : trim($_REQUEST['rows']);
    
    $where = " WHERE class_code='".$_SESSION["class_code"]."' and ((to_type='admin' and to_='".$_SESSION["admin_id"]."') or (from_type='admin' and from_='".$_SESSION["admin_id"]."')) ";
    
    if ($filter['keywords'])
    {
        $where .= " AND message LIKE '%" . mysql_like_quote($filter['keywords']) . "%' ";
    }
    if ($filter['msg_type'] != -1)
    {
        $where .= " AND msg_type = '$filter[msg_type]' ";
    }
    if ($filter['to_'] != -1)
    {
    	$where .= " AND (to_ = '$filter[to_]' and to_type='$filter[to_type]') ";
    }
    if ($filter['from_'] != -1)
    {
    	$where .= " AND (from_ = '$filter[from_]' and from_type='$filter[from_type]') ";
    }
    
    $sql = "SELECT count(*) FROM " .$table . $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);
    
    $sql = "SELECT * ".
                    " FROM " . $table . $where .
                    " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                    " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
    
//     echo $sql;
    
    $msg_list = $GLOBALS['db']->getAll($sql);
    foreach($msg_list as $k=>$msg){
    	$msg_list[$k]['replys'] = $GLOBALS['db']->getOne("select count(1) from ".$table." where fid=".$msg['message_id']);
    	$msg_list[$k]['to_user'] = get_user_name($msg['to_'],$msg['to_type']);
    	$msg_list[$k]['from_user'] = get_user_name($msg['from_'],$msg['from_type']);
    }
    
    
    $filter['keywords'] = stripslashes($filter['keywords']);
    $arr = array('rows' => $msg_list, 'filter' => $filter,
            'page' => $filter['page_count'], 'total' => $filter['record_count']);
    
    return $arr;
}

?>