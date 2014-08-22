<?php

/**
 * ECSHOP 客户留言
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user_msg.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');


/*初始化数据交换对象 */
$table = "hteacher.ht_feedback";
$exc = new exchange($table, $db, 'msg_id', 'msg_title');

/*------------------------------------------------------ */
//-- 列出所有$from的留言
/*------------------------------------------------------ */
if ($_REQUEST['act']=="list")
{
	$smarty->display('msg_list.htm');
	exit;
}

elseif ($_REQUEST['act'] == 'ajax_list')
{
	$list = msg_list("class", $table);
	make_json($list);
}

elseif ($_REQUEST['act'] == 'ajax_save')
{
	$sql = "INSERT INTO " . $table . "(parent_id, user_id, user_name, user_email, msg_title, 
	                                                 msg_type, msg_content, msg_time, msg_status, msg_from)" .
	            " VALUES (0, '$_SESSION[admin_id]', '$_SESSION[admin_name]', ' ', ".
	            " '$_POST[msg_title]', 5, '$_POST[msg_content]', '" . gmtime() . "', '0', 'class')";
	
	$db->query($sql);

	admin_log(addslashes($_REQUEST["msg_title"]), 'add', $sql);

	make_json_result("添加“".$_REQUEST["msg_title"]."”成功！");
}


elseif ($_REQUEST['act'] == 'ajax_delete')
{
	$id    = !empty($_REQUEST['msg_id'])        ? intval($_REQUEST['msg_id'])      : 0;
	$sql = "delete from ".$table." where parent_id=$id or msg_id=".$id;

	$db->query($sql);

	admin_log($_REQUEST["msg_id"], 'delete', $sql);

	make_json_result("删除成功！");
}



/**
 *
 *
 * @access  public
 * @param
 *
 * @return void
 */
function msg_list($from, $table)
{
    /* 过滤条件 */
    $filter['keywords']   = empty($_REQUEST['search_keywords']) ? '' : trim($_REQUEST['search_keywords']);
    $filter['msg_type']   = isset($_REQUEST['msg_type']) ? intval($_REQUEST['msg_type']) : -1;
    
    if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
    {
        $filter['keywords'] = json_str_iconv($filter['keywords']);
    }
    
    $filter['sort']    = empty($_REQUEST['sort']) ||  trim($_REQUEST['sort'])=='msg_reply'  ? 'msg_id' : trim($_REQUEST['sort']);
    $filter['order'] = empty($_REQUEST['order']) ? 'DESC'     : trim($_REQUEST['order']);
    $filter['page'] = empty($_REQUEST['page']) ? '1'     : trim($_REQUEST['page']);
    $filter['page_size']	= empty($_REQUEST['rows']) ? '15'     : trim($_REQUEST['rows']);
    
    $where = " WHERE parent_id = '0' AND msg_from='".$from."' and user_id='".$_SESSION["admin_id"]."' ";
    $filter['from'] = $from;
    
    if ($filter['keywords'])
    {
        $where .= " AND (msg_title LIKE '%" . mysql_like_quote($filter['keywords']) . "%' ";
        $where .= " OR msg_content LIKE '%" . mysql_like_quote($filter['keywords']) . "%') ";
    }
    if ($filter['msg_type'] != -1)
    {
        $where .= " AND msg_type = '$filter[msg_type]' ";
    }

    $sql = "SELECT count(*) FROM " .$table. " AS f " . $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);
    
    $sql = "SELECT * ".
                    " FROM " . $table . $where .
                    " ORDER by " . $filter['sort'] . ' ' . $filter['order'] .
                    " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
    

    $msg_list = $GLOBALS['db']->getAll($sql);
    foreach ($msg_list AS $key => $value)
    {   
    	$reply = $GLOBALS['db']->getOne("select msg_content  from ".$table. " where parent_id=".$value["msg_id"]." limit 1");
    	$msg_list[$key]['msg_status'] = $reply?1:0;
    	$msg_list[$key]['msg_reply'] = $reply;
        $msg_list[$key]['msg_time'] = local_date($GLOBALS['_CFG']['time_format'], $value['msg_time']);
        $msg_list[$key]['msg_type'] = $GLOBALS['_LANG']['type'][$value['msg_type']];
    }
    $filter['keywords'] = stripslashes($filter['keywords']);
    $arr = array('rows' => $msg_list, 'filter' => $filter,
            'page' => $filter['page_count'], 'total' => $filter['record_count']);
    
    return $arr;
}

?>