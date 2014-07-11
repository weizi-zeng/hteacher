<?php

/**
 * ECSHOP 会员管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: class.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 班级列表
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
    /* 检查权限 */
    admin_priv('class_manage');
    
    $smarty->assign('ur_here',      "班级列表");
    $smarty->assign('action_link',  array('text' => "添加班级", 'href'=>'class.php?act=add'));

    $class_list = get_class_list();

    $smarty->assign('class_list',    $class_list['class_list']);
    $smarty->assign('filter',       $class_list['filter']);
    $smarty->assign('record_count', $class_list['record_count']);
    $smarty->assign('page_count',   $class_list['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('sort_class_id', '<img src="images/sort_desc.gif">');

    assign_query_info();
    $smarty->display('class_list.htm');
}

/*------------------------------------------------------ */
//-- ajax返回班级列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $class_list = get_class_list();

    $smarty->assign('class_list',    $class_list['class_list']);
    $smarty->assign('filter',       $class_list['filter']);
    $smarty->assign('record_count', $class_list['record_count']);
    $smarty->assign('page_count',   $class_list['page_count']);

    $sort_flag  = sort_flag($class_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('class_list.htm'), '', array('filter' => $class_list['filter'], 'page_count' => $class_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加会员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('class_manage');
	
    $class = array();
    
    $grade_list = grade_list();
    $smarty->assign('grade_list',             $grade_list);
    
    $smarty->assign('ur_here',          "添加班级");
    $smarty->assign('action_link',      array('text' => "班级列表", 'href'=>'class.php?act=list'));
    $smarty->assign('form_action',      'insert');
    $smarty->assign('class',             $class);

    assign_query_info();
    $smarty->display('class_info.htm');
}

/*------------------------------------------------------ */
//-- 添加会员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    /* 检查权限 */
    admin_priv('class_manage');
    
    $code = empty($_POST['code']) ? '' : trim($_POST['code']);
    //TODO 对code进行验证
    if($code==''){
    	sys_msg("班级编号不能为空！", 1);
    }
    
    $classname = empty($_POST['name']) ? '' : trim($_POST['name']);
    $grade = empty($_POST['grade']) ? 0 : intval($_POST['grade']);
    $hteacher = empty($_POST['hteacher']) ? '' : trim($_POST['hteacher']);
    $classroom = empty($_POST['classroom']) ? '' : trim($_POST['classroom']);
    
    $sql = "insert into ". $GLOBALS['ecs']->table('class') 
    	." (code,name,is_active,classroom,hteacher,has_left,grade,created) value "
    	." ('$code','$classname',1,'$classroom','$hteacher','0','$grade',now())";
    
    $db->query($sql);

    /* 记录管理员操作 */
    admin_log($_POST['name'], 'add', 'class');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'class.php?act=list');
    sys_msg(sprintf("创建班级成功", htmlspecialchars(stripslashes($classname))), 0, $link);

}

/*------------------------------------------------------ */
//-- 编辑班级
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('class_manage');

    $class = get_class_by_id($_GET[id]);
    
    $grade_list = grade_list();
    $smarty->assign('grade_list',             $grade_list);
    
    assign_query_info();
    $smarty->assign('ur_here',          "编辑班级信息");
    $smarty->assign('action_link',      array('text' => "班级列表", 'href'=>'class.php?act=list&' . list_link_postfix()));
    $smarty->assign('class',             $class);
    $smarty->assign('form_action',      'update');
    $smarty->display('class_info.htm');
}

/*------------------------------------------------------ */
//-- 更新班级
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('class_manage');
    
    $classname = empty($_POST['name']) ? '' : trim($_POST['name']);
    $grade = empty($_POST['grade']) ? '' : trim($_POST['grade']);
    $hteacher = empty($_POST['hteacher']) ? '' : trim($_POST['hteacher']);
    $classroom = empty($_POST['classroom']) ? '' : trim($_POST['classroom']);
    
    $class_id = empty($_POST['id']) ? 0 : intval($_POST['id']);
    
    $sql = "UPDATE ". $GLOBALS['ecs']->table('class')." SET ".
    		"name='".$classname."',".
		    "grade='".$grade."',".
		    "hteacher='".$hteacher."',".
		    "classroom='".$classroom."'".
	        " WHERE class_id=".$class_id;
    

    if (!$db->query($sql))
    {
        sys_msg("修改班级信息失败", 1);
    }

    /* 记录管理员操作 */
    admin_log($classname, 'edit', 'class');

    /* 提示信息 */
    $links[0]['text']    = $_LANG['goto_list'];
    $links[0]['href']    = 'class.php?act=list&' . list_link_postfix();
    $links[1]['text']    = $_LANG['go_back'];
    $links[1]['href']    = 'javascript:history.back()';

    sys_msg("修改班级信息成功", 0, $links);

}


/* 编辑班级名 */
elseif ($_REQUEST['act'] == 'edit_hteacher')
{
    /* 检查权限 */
    check_authz_json('class_manage');

    $hteacher = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

    if ($id == 0)
    {
        make_json_error('NO class ID');
        return;
    }

    if ($hteacher == '')
    {
        make_json_error("班主任名称不能为空");
        return;
    }
    
    $db->query('UPDATE ' .$ecs->table('class'). " SET hteacher = '$hteacher' WHERE class_id = '$id'");
    
    $sql = "SELECT name FROM " . $ecs->table('class') . " WHERE class_id = '$id'";
    $classname = $db->getOne($sql);
    
    admin_log(addslashes($classname), 'edit', 'class');
    make_json_result(stripcslashes($hteacher));
}

/*------------------------------------------------------ */
//-- 编辑email
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_classroom')
{
    /* 检查权限 */
    check_authz_json('class_manage');

    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $classroom = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

    $sql = "SELECT name FROM " . $ecs->table('class') . " WHERE class_id = '$id'";
    $classname = $db->getOne($sql);


    $sql = "update ".$ecs->table('class')." set classroom='".$classroom."' WHERE class_id = '$id'";
    	$db->query($sql);
    	
        admin_log(addslashes($classname), 'edit', 'class');
		
        make_json_result(stripcslashes($classroom));
}


/*------------------------------------------------------ */
//-- 切换是active: 激活或者关闭此班级
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_left')
{
	check_authz_json('class_manage');

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);

	$sql = "update ".$ecs->table('class')." set has_left=".$val." WHERE class_id = '$id'";
	$db->query($sql);
	
	clear_cache_files();

	make_json_result($val);
}


/*------------------------------------------------------ */
//-- 切换是active: 激活或者关闭此班级
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_active')
{
	check_authz_json('class_manage');

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);

	$sql = "update ".$ecs->table('class')." set is_active=".$val." WHERE class_id = '$id'";
	$db->query($sql);

	clear_cache_files();

	make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除班级数据
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    /* 检查权限 */
    admin_priv('class_drop');

    $sql = "SELECT * FROM " . $ecs->table('class') . " WHERE class_id = '" . $_GET['id'] . "'";
    $class = $db->getRow($sql);
    
    if ($class['class_id'])
    {
        $sql = "DELETE FROM " . $ecs->table('class') . " WHERE class_id = '$id'";
        $db->query($sql);

        /* 记日志 */
        admin_log($class['name'], 'remove', 'class');

        /* 清除缓存 */
        clear_cache_files();
    }

    $url = 'class.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");

    exit;
}

/**
 *  返回班级列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function get_class_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//班级名称
        $filter['code'] = empty($_REQUEST['code']) ? '' : trim($_REQUEST['code']);//班级code
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keywords'] = json_str_iconv($filter['keywords']);
        }

        $filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'class_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);

        $ex_where = ' WHERE removed=0  ';
        if ($filter['keywords'])
        {
            $ex_where .= " AND name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
        }
        if ($filter['code'])
        {
        	$ex_where .= " AND code LIKE '%" . mysql_like_quote($filter['code']) ."%'";
        }

        $filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('class') . $ex_where);

        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table('class') . $ex_where .
                " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

        $filter['keywords'] = stripslashes($filter['keywords']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $class_list = $GLOBALS['db']->getAll($sql);

//     $count = count($class_list);
//     for ($i=0; $i<$count; $i++)
//     {
//         $class_list[$i]['created'] = local_date($GLOBALS['_CFG']['date_format'], $class_list[$i]['created']);
//     }

    $arr = array('class_list' => $class_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

function get_class_by_id($id)
{
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('class')  ." where class_id=".$id;
	return $GLOBALS['db']->getRow($sql);
}

?>