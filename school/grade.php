<?php

/**
 * ECSHOP 管理中心供货商管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: wanglei $
 * $Id: grade.php 15013 2009-05-13 09:31:42Z wanglei $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 年级列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{

    /* 查询 */
    $result = grade_list();

    /* 模板赋值 */
    $smarty->assign('ur_here', "年级列表"); // 当前导航
    $smarty->assign('action_link', array('href' => 'grade.php?act=add', 'text' => "添加年级数据"));

    $smarty->assign('full_page',        1); // 翻页参数

    $smarty->assign('grade_list',    $result);
    $smarty->assign('sort_grade_id', '<img src="images/sort_desc.gif">');

    /* 显示模板 */
    assign_query_info();
    $smarty->display('grade_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
	$sort_by = isset($_REQUEST['sort_by'])?"":trim($_REQUEST['sort_by']);
	$sort_order = isset($_REQUEST['sort_order'])?"DESC":trim($_REQUEST['sort_order']);
	
    $result = grade_list($sort_by,$sort_order);

    $smarty->assign('grade_list',    $result);

    /* 排序标记 */
    $filter = array("sort_by"=>$sort_by, "sort_order"=>$sort_order);
    $sort_flag  = sort_flag($filter);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('grade_list.htm'), '', array());
}

/*------------------------------------------------------ */
//-- 删除供货商
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{

    $id = intval($_REQUEST['id']);
    
    $sql = "select * from ". $ecs->table('grade') ." WHERE grade_id = '$id'";
    $grade = $db->getRow($sql);
    
    if ($grade['grade_id'])
    {
        $sql = "DELETE FROM " . $ecs->table('grade') . "
            WHERE grade_id = '$id'";
        $db->query($sql);

        /* 记日志 */
        admin_log($grade['name'], 'remove', 'grade');

        /* 清除缓存 */
        clear_cache_files();
    }

    $url = 'grade.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");

    exit;
}


/*------------------------------------------------------ */
//-- 添加、编辑年级
/*------------------------------------------------------ */
elseif (in_array($_REQUEST['act'], array('add', 'edit')))
{

    if ($_REQUEST['act'] == 'add')
    {
        $smarty->assign('ur_here', "添加年级数据");
        $smarty->assign('action_link', array('href' => 'grade.php?act=list', 'text' => "年级列表"));

        $smarty->assign('form_action', 'insert');
        $smarty->assign('grade', $grade);

        assign_query_info();

        $smarty->display('grade_info.htm');

    }

}

/*------------------------------------------------------ */
//-- 提交添加、编辑供货商
/*------------------------------------------------------ */
elseif (in_array($_REQUEST['act'], array('insert', 'update')))
{

    if ($_REQUEST['act'] == 'insert')
    {
        /* 提交值 */
        $grade = array('name'   => trim($_POST['name']),
                           'code'   => trim($_POST['code'])
                           );

        /* 判断名称是否重复 */
        $sql = "SELECT grade_id
                FROM " . $ecs->table('grade') . "
                WHERE code = '" . $grade['code'] . "' ";
        if ($db->getOne($sql))
        {
            sys_msg("此年级编码“".$grade['code']."”已经存在");
        }

        $db->autoExecute($ecs->table('grade'), $grade, 'INSERT');
        $grade['grade_id'] = $db->insert_id();

        /* 记日志 */
        admin_log($grade['name'], 'add', 'grade');

        /* 清除缓存 */
        clear_cache_files();

        /* 提示信息 */
        $links = array(array('href' => 'grade.php?act=add',  'text' => "继续添加"),
                       array('href' => 'grade.php?act=list', 'text' => "返回年级列表")
                       );
        sys_msg("添加新年级“".$grade['name']."”成功", 0, $links);

    }


}


?>