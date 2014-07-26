<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . "includes/fckeditor/fckeditor.php");
require_once(ROOT_PATH . 'includes/cls_image.php');

if ($_REQUEST['act'] == 'list')
{
	/* 创建 html editor */
	create_html_editor('FCKeditor1');
	$smarty->display('notice_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'view')
{
	$id    = !empty($_REQUEST['notice_id'])        ? intval($_REQUEST['notice_id'])      : 0;
	$sql = "select * from ".$ecs->table("notice")." where notice_id=".$id;
	$row = $db->getRow($sql);
	
	if(empty($row) || $row["is_active"]!=1){
		die('您所有查看的通知不存在或者是被屏蔽');
	}
	
	$smarty->assign("notice",$row);
	$smarty->assign("show_back",$_REQUEST['show_back']);
	
	$sql = "select * from ".$ecs->table("notice_attach")." where notice_id=".$id;
	$rows = $db->getAll($sql);
	if(count($rows)>0){
		$smarty->assign("attachs",$rows);
	}
	
	/* 上一篇下一篇文章 */
	$next_notice = $db->getRow("SELECT notice_id, title FROM " .$ecs->table('notice'). " WHERE notice_id > $id AND is_active=1 LIMIT 1");
	if (!empty($next_notice))
	{
		$smarty->assign('next_notice', $next_notice);
	}
	
	$prev_aid = $db->getOne("SELECT max(notice_id) FROM " . $ecs->table('notice') . " WHERE notice_id < $id AND is_active=1");
	if (!empty($prev_aid))
	{
		$prev_notice = $db->getRow("SELECT notice_id, title FROM " .$ecs->table('notice'). " WHERE notice_id = $prev_aid");
		$smarty->assign('prev_notice', $prev_notice);
	}
	
	$smarty->display('notice.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = notice_list();
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
		
		$sql = "INSERT INTO ".$ecs->table('notice')."(title, urgency, class_code, content, author, ".
		                "is_active, created) ".
		            "VALUES ('$_POST[title]', '$_POST[urgency]', '$_SESSION[class_code]', '$_POST[FCKeditor1]', ".
		                "'$_SESSION[admin_name]', '$_POST[is_active]', now())";
		$db->query($sql);
		$notice_id = $db->insert_id();
		
		admin_log(addslashes($_REQUEST["title"]), 'add', 'notice');
		
		/* 取得文件地址 */
		$res = save_notice_attach($notice_id, $_FILES["file1"], $_SESSION[admin_name]);
		if($res["error"]==0){
			$res = save_notice_attach($notice_id, $_FILES["file2"], $_SESSION[admin_name]);
			if($res["error"]==0){
				$res = save_notice_attach($notice_id, $_FILES["file3"], $_SESSION[admin_name]);
			}else {
				$smarty->assign("error","附件上传失败！".$res["msg"]);
			}
		}else {
			$smarty->assign("error","附件上传失败！".$res["msg"]);
		}
		
		create_html_editor('FCKeditor1');
		$smarty->display('notice_list.htm');
		exit;
		
}

elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['notice_id'])        ? intval($_REQUEST['notice_id'])      : 0;
	if($id!=0){
		//删除附件
		$sql = "select * from ".$ecs->table("notice_attach")." where notice_id=".$id;
		$rows = $db->getAll($sql);
		foreach($rows as $row){
			@unlink(ROOT_PATH.$row["path"]);
		}
		//删除附件
		$db->query("DELETE FROM ".$ecs->table("notice_attach")." where notice_id=".$id);
		
		//删除记录
		$sql = "delete from ".$ecs->table("notice")." where notice_id=".$id;
		$db->query($sql);
		
		admin_log($_REQUEST["notice_id"], 'delete', $sql);
	}
	
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
function notice_list()
{
	$result = get_filter();
	if ($result === false)
	{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['search_keywords']) ? '' : trim($_REQUEST['search_keywords']);//关键字
		$filter['created'] = empty($_REQUEST['search_created']) ? '' : trim($_REQUEST['search_created']);//名称
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort']    = empty($_REQUEST['sort'])    ? 'notice_id' : trim($_REQUEST['sort']);
		$filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
		$filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
		$filter['page_size']	= empty($_REQUEST['rows']) ? '15'     : trim($_REQUEST['rows']);
		
		$ex_where = " WHERE class_code='".$_SESSION["class_code"]."' ";
		if ($filter['keywords'])
		{
			$ex_where .= " AND (title like '%" . mysql_like_quote($filter['keywords']) ."%'";
			$ex_where .= " OR content like '%" . mysql_like_quote($filter['keywords']) ."%') ";
		}
		if ($filter['created'])
		{
			$ex_where .= " AND created like '" . mysql_like_quote($filter['created']) ."%'";
		}
		
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table("notice") . $ex_where;
		
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table("notice")  . $ex_where .
                " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql; echo '<br>';

		$filter['keywords'] = stripslashes($filter['keywords']);
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

/**
 * 保存附件
 * Enter description here ...
 * @param unknown_type $attach
 */
function save_notice_attach($notice_id, $attach, $uploader){
	global $allow_file_types;
	
	$result = array("error"=>0,"msg"=>"");
	if ((isset($attach['error']) && $attach['error'] == 0) || (!isset($attach['error']) && isset($attach['tmp_name']) && $attach['tmp_name'] != 'none'))
	{
		// 检查文件格式
		if (!check_file_type($attach['tmp_name'], $attach['name'], $allow_file_types))
		{
			return array("error"=>1,"msg"=>"您上传的文件格式不被允许,只能是以下格式的文件才能上传:".$allow_file_types);
		}
	
		// 复制文件
		$res = upload_article_file($attach);
		if ($res != false)
		{
			$file_url = $res;
		}
	}else {
		if(isset($attach['error']) && $attach['error'] == 4){
			return $result;
		}else {
			return array("error"=>1,"msg"=>"您上传的文件存在异常，文件必须在2M之内，且只能是以下格式的文件:".$allow_file_types);
		}
	}
	
	$sql = "insert into ".$GLOBALS['ecs']->table("notice_attach")." (notice_id, name, path, size, type, uploader, created) 
		values ('$notice_id', '$attach[name]', '$file_url', '$attach[size]', '$attach[type]', '$uploader', now()) ";
	 
	$GLOBALS['db']->query($sql);
	return $result;
}


/* 上传文件 */
function upload_article_file($upload)
{
	if (!make_dir("../" . DATA_DIR . "/notice"))
	{
		/* 创建目录失败 */
		return false;
	}

	$filename = cls_image::random_filename() . substr($upload['name'], strpos($upload['name'], '.'));
	$path     = ROOT_PATH. DATA_DIR . "/notice/" . $filename;

	if (move_upload_file($upload['tmp_name'], $path))
	{
		return DATA_DIR . "/notice/" . $filename;
	}
	else
	{
		return false;
	}
}
?>