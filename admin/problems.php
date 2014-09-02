<?php

/**
 * ECSHOP 管理中心问题处理程序文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: problems.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . "includes/fckeditor/fckeditor.php");
require_once(ROOT_PATH . 'includes/cls_image.php');

/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("problems"), $db, 'problems_id', 'title');

/*------------------------------------------------------ */
//-- 问题列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('ur_here',      "问题列表");
    $smarty->assign('action_link',  array('text' => "添加问题", 'href' => 'problems.php?act=add'));
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $problems_list = get_problemsslist();

    $smarty->assign('problems_list',    $problems_list['arr']);
    $smarty->assign('filter',          $problems_list['filter']);
    $smarty->assign('record_count',    $problems_list['record_count']);
    $smarty->assign('page_count',      $problems_list['page_count']);

    $sort_flag  = sort_flag($problems_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('problems_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('problems_manage');

    $problems_list = get_problemsslist();

    $smarty->assign('problems_list',    $problems_list['arr']);
    $smarty->assign('filter',          $problems_list['filter']);
    $smarty->assign('record_count',    $problems_list['record_count']);
    $smarty->assign('page_count',      $problems_list['page_count']);

    $sort_flag  = sort_flag($problems_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('problems_list.htm'), '',
        array('filter' => $problems_list['filter'], 'page_count' => $problems_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加问题
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('problems_manage');

    /* 创建 html editor */
    create_html_editor('FCKeditor1');

    /*初始化*/
    $problems = array();
    $problems['is_open'] = 1;

    $smarty->assign('problems',     $problems);
    $smarty->assign('ur_here',     "添加问题");
    $smarty->assign('action_link', array('text' => "问题列表", 'href' => 'problems.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('problems_info.htm');
}

/*------------------------------------------------------ */
//-- 添加问题
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('problems_manage');

    /*检查是否重复*/
    $is_only = $exc->is_only('title', $_POST['title'],0 );

    if (!$is_only)
    {
        sys_msg(sprintf("此问题已经存在", stripslashes($_POST['title'])), 1);
    }

    /*插入数据*/
    $add_time = gmtime();
    $sql = "INSERT INTO ".$ecs->table('problems')." (`type`, title, content, author, created) ".
            " VALUES (1, '$_POST[title]', '$_POST[FCKeditor1]', '$_SESSION[admin_name]', now())";
    $db->query($sql);

    $link[0]['text'] = "继续添加";
    $link[0]['href'] = 'problems.php?act=add';

    $link[1]['text'] = "返回列表";
    $link[1]['href'] = 'problems.php?act=list';

    admin_log($_POST['title'],'add',$sql);

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg("添加系统相关成功",0, $link);
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('problems_manage');

    /* 取问题数据 */
    $sql = "SELECT * FROM " .$ecs->table('problems'). " WHERE problems_id='$_REQUEST[id]'";
    $problems = $db->GetRow($sql);

    /* 创建 html editor */
    create_html_editor('FCKeditor1',$problems['content']);

    $smarty->assign('problems',     $problems);
    $smarty->assign('ur_here',     "编辑问题");
    $smarty->assign('action_link', array('text' => "问题列表", 'href' => 'problems.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');

    assign_query_info();
    $smarty->display('problems_info.htm');
}

if ($_REQUEST['act'] =='update')
{
    /* 权限判断 */
    admin_priv('problems_manage');

    /*检查问题名是否相同*/
    $is_only = $exc->is_only('title', $_POST['title'], $_POST['id']);

    if (!$is_only)
    {
        sys_msg(sprintf("此问题已经存在", stripslashes($_POST['title'])), 1);
    }

    if ($exc->edit("title='$_POST[title]', content='$_POST[FCKeditor1]'", $_POST['id']))
    {
        $link[0]['text'] = "返回列表";
        $link[0]['href'] = 'problems.php?act=list&' . list_link_postfix();

        $note = sprintf("编辑问题成功", stripslashes($_POST['title']));
        admin_log($_POST['title'], 'edit', 'problems');

        clear_cache_files();

        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑问题主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_title')
{
    check_authz_json('problems_manage');

    $id    = intval($_POST['id']);
    $title = json_str_iconv(trim($_POST['val']));

    /* 检查问题标题是否重复 */
    if ($exc->num("title", $title, $id) != 0)
    {
        make_json_error(sprintf("此问题已经存在", $title));
    }
    else
    {
        if ($exc->edit("title = '$title'", $id))
        {
            clear_cache_files();
            admin_log($title, 'edit', 'problems');
            make_json_result(stripslashes($title));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_show')
{
    check_authz_json('problems_manage');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_active = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}


/*------------------------------------------------------ */
//-- 删除问题主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('problems_manage');

    $id = intval($_GET['id']);

    if ($exc->drop($id))
    {
        admin_log($id,'remove','problems');
        clear_cache_files();
    }

    $url = 'problems.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/* 获得问题列表 */
function get_problemsslist()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'a.problems_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = '';
        if (!empty($filter['keyword']))
        {
            $where = " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }

        /* 问题总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('problems'). ' AS a '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取问题数据 */
        $sql = 'SELECT a.*  '.
               'FROM ' .$GLOBALS['ecs']->table('problems'). ' AS a '.
               'WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $arr = array();
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['date'] = local_date($GLOBALS['_CFG']['time_format'], $rows['add_time']);
        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>