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
 * $Id: school.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 学校帐号列表
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
    /* 检查权限 */
    admin_priv('school_manage');
    
    $smarty->assign('ur_here',      $_LANG['03_school_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['04_school_add'], 'href'=>'school.php?act=add'));

    $school_list = school_list();

    $smarty->assign('school_list',    $school_list['school_list']);
    $smarty->assign('filter',       $school_list['filter']);
    $smarty->assign('record_count', $school_list['record_count']);
    $smarty->assign('page_count',   $school_list['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('sort_school_id', '<img src="images/sort_desc.gif">');

    assign_query_info();
    $smarty->display('school_list.htm');
}

/*------------------------------------------------------ */
//-- ajax返回学校列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $school_list = school_list();

    $smarty->assign('school_list',    $school_list['school_list']);
    $smarty->assign('filter',       $school_list['filter']);
    $smarty->assign('record_count', $school_list['record_count']);
    $smarty->assign('page_count',   $school_list['page_count']);

    $sort_flag  = sort_flag($school_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('school_list.htm'), '', array('filter' => $school_list['filter'], 'page_count' => $school_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加会员帐号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('school_manage');
	
    $school = array();
    
    $smarty->assign('ur_here',          $_LANG['04_school_add']);
    $smarty->assign('action_link',      array('text' => $_LANG['03_school_list'], 'href'=>'school.php?act=list'));
    $smarty->assign('form_action',      'insert');
    $smarty->assign('school',             $school);

    assign_query_info();
    $smarty->display('school_info.htm');
}

/*------------------------------------------------------ */
//-- 添加会员帐号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    /* 检查权限 */
    admin_priv('school_manage');
    
    
    $code = empty($_POST['code']) ? '' : trim($_POST['code']);
    //TODO 对code进行验证
    if($code==''){
    	sys_msg("学校编号不能为空！", 1);
    }
    
    $schoolname = empty($_POST['name']) ? '' : trim($_POST['name']);
    $type = empty($_POST['type']) ? '' : trim($_POST['type']);
    $address = empty($_POST['address']) ? '' : trim($_POST['address']);
    $header = empty($_POST['header']) ? '' : trim($_POST['header']);
    $mobtel = empty($_POST['mobtel']) ? '' : trim($_POST['mobtel']);
    
    $email = empty($_POST['email']) ? '' : trim($_POST['email']);
    $tel = empty($_POST['tel']) ? '' : trim($_POST['tel']);
    $fox = empty($_POST['fox']) ? '' : trim($_POST['fox']);
    $motto = empty($_POST['motto']) ? '' : trim($_POST['motto']);
    
    $create_date = empty($_POST['create_date']) ? '' : trim($_POST['create_date']);
    $dec_day = empty($_POST['dec_day']) ? '' : trim($_POST['dec_day']);
    $ceo = empty($_POST['ceo']) ? '' : trim($_POST['ceo']);
    $org_code = empty($_POST['org_code']) ? '' : trim($_POST['org_code']);
    
    $sql = "insert into ". $GLOBALS['ecs']->table('school') 
    	." (code,name,is_active,type,tel,mobtel,fox,motto,address,email,create_date,dec_day,org_code,header,ceo,createtime) value "
    	." ('$code','$schoolname',1,'$type','$tel','$mobtel','$fox','$motto','$address','$email','$create_date','$dec_day','$org_code','$header','$ceo',now())";
    if($db->query($sql)){
    	//创建学校数据库
	   	$flag = create_school_database($code);
    }

    /* 记录管理员操作 */
    admin_log($_POST['name'], 'add', 'school');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'school.php?act=list');
    sys_msg(sprintf("创建学校成功", htmlspecialchars(stripslashes($schoolname))), 0, $link);

}

/*------------------------------------------------------ */
//-- 编辑学校帐号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('school_manage');

    $school = get_school_by_id($_GET[id]);
    
    assign_query_info();
    $smarty->assign('ur_here',          $_LANG['school_edit']);
    $smarty->assign('action_link',      array('text' => $_LANG['03_school_list'], 'href'=>'school.php?act=list&' . list_link_postfix()));
    $smarty->assign('school',             $school);
    $smarty->assign('form_action',      'update');
    $smarty->display('school_info.htm');
}

/*------------------------------------------------------ */
//-- 更新学校帐号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('school_manage');
    
    $schoolname = empty($_POST['name']) ? '' : trim($_POST['name']);
    $type = empty($_POST['type']) ? '' : trim($_POST['type']);
    $address = empty($_POST['address']) ? '' : trim($_POST['address']);
    $header = empty($_POST['header']) ? '' : trim($_POST['header']);
    $mobtel = empty($_POST['mobtel']) ? '' : trim($_POST['mobtel']);
    
    $email = empty($_POST['email']) ? '' : trim($_POST['email']);
    $tel = empty($_POST['tel']) ? '' : trim($_POST['tel']);
    $fox = empty($_POST['fox']) ? '' : trim($_POST['fox']);
    $motto = empty($_POST['motto']) ? '' : trim($_POST['motto']);
    
    $create_date = empty($_POST['create_date']) ? '' : trim($_POST['create_date']);
    $dec_day = empty($_POST['dec_day']) ? '' : trim($_POST['dec_day']);
    $ceo = empty($_POST['ceo']) ? '' : trim($_POST['ceo']);
    $org_code = empty($_POST['org_code']) ? '' : trim($_POST['org_code']);
    
    $school_id = empty($_POST['id']) ? 0 : intval($_POST['id']);
    
    $sql = "UPDATE ". $GLOBALS['ecs']->table('school')." SET ".
    		"name='".$schoolname."',".
		    "type='".$type."',".
		    "tel='".$tel."',".
		    "mobtel='".$mobtel."',".
		    "fox='".$fox."',".
		    "motto='".$motto."',".
		    "address='".$address."',".
		    "email='".$email."',".
		    "create_date='".$create_date."',".
		    "dec_day='".$dec_day."',".
		    "org_code='".$org_code."',".
	    	"header='".$header."',".
	        "ceo='".$ceo."' WHERE school_id=".$school_id;
    

    if (!$db->query($sql))
    {
        sys_msg("修改学校信息失败", 1);
    }

    /* 记录管理员操作 */
    admin_log($schoolname, 'edit', 'school');

    /* 提示信息 */
    $links[0]['text']    = $_LANG['goto_list'];
    $links[0]['href']    = 'school.php?act=list&' . list_link_postfix();
    $links[1]['text']    = $_LANG['go_back'];
    $links[1]['href']    = 'javascript:history.back()';

    sys_msg("修改学校信息成功", 0, $links);

}


/* 编辑学校名 */
elseif ($_REQUEST['act'] == 'edit_schoolname')
{
    /* 检查权限 */
    check_authz_json('school_manage');

    $schoolname = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

    if ($id == 0)
    {
        make_json_error('NO school ID');
        return;
    }

    if ($schoolname == '')
    {
        make_json_error($GLOBALS['_LANG']['schoolname_empty']);
        return;
    }

    $school =& init_school();

    if ($school->edit_school($id, $schoolname))
    {
        if ($_CFG['integrate_code'] != 'ecshop')
        {
            /* 更新商城会员表 */
            $db->query('UPDATE ' .$ecs->table('school'). " SET school_name = '$schoolname' WHERE school_id = '$id'");
        }

        admin_log(addslashes($schoolname), 'edit', 'school');
        make_json_result(stripcslashes($schoolname));
    }
    else
    {
        $msg = ($school->error == ERR_schoolNAME_EXISTS) ? $GLOBALS['_LANG']['schoolname_exists'] : $GLOBALS['_LANG']['edit_school_failed'];
        make_json_error($msg);
    }
}

/*------------------------------------------------------ */
//-- 编辑email
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_email')
{
    /* 检查权限 */
    check_authz_json('school_manage');

    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $email = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

    $sql = "SELECT name FROM " . $ecs->table('school') . " WHERE school_id = '$id'";
    $schoolname = $db->getOne($sql);


    if (is_email($email))
    {
    	$sql = "update ".$ecs->table('school')." set email='".$email."' WHERE school_id = '$id'";
    	$db->query($sql);
    	
        admin_log(addslashes($schoolname), 'edit', 'school');
		
        make_json_result(stripcslashes($email));
    }
    else
    {
        make_json_error($GLOBALS['_LANG']['invalid_email']);
    }
}

/*------------------------------------------------------ */
//-- 编辑mobtel
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_mobtel')
{
	/* 检查权限 */
	check_authz_json('school_manage');

	$id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	$mobtel = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

	$sql = "SELECT name FROM " . $ecs->table('school') . " WHERE school_id = '$id'";
	$schoolname = $db->getOne($sql);

	$sql = "update ".$ecs->table('school')." set mobtel='".$mobtel."' WHERE school_id = '$id'";
	$db->query($sql);
	 
	admin_log(addslashes($schoolname), 'edit', 'school');

	make_json_result(stripcslashes($mobtel));
}

/*------------------------------------------------------ */
//-- 切换是active: 激活或者关闭此学校
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_active')
{
	check_authz_json('school_manage');

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);

// 	$exc->edit("is_open = '$val'", $id);
	$sql = "update ".$ecs->table('school')." set is_active=".$val." WHERE school_id = '$id'";
	$db->query($sql);
	
	clear_cache_files();

	make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除学校数据
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    /* 检查权限 */
    admin_priv('school_drop');

    $sql = "SELECT * FROM " . $ecs->table('school') . " WHERE school_id = '" . $_GET['id'] . "'";
    $school = $db->getRow($sql);
    
//     $db->query("update " . $ecs->table('school') . " set removed=1 where school_id = '" . $_GET['id'] . "'");

    $db->query("delete from " . $ecs->table('school') . "  where school_id = '" . $_GET['id'] . "'");
    $db->query("drop database if EXISTS " . $school['code'] . "_school ");
    
    /* 记录管理员操作 */
    admin_log(addslashes($school['name']), 'remove', 'school');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'school.php?act=list');
    sys_msg("成功删除学校“".$school['name']."”", 0, $link);
}

/**
 *  返回学校列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function school_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//学校名称
        $filter['code'] = empty($_REQUEST['code']) ? '' : trim($_REQUEST['code']);//学校code
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keywords'] = json_str_iconv($filter['keywords']);
        }

        $filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'school_id' : trim($_REQUEST['sort_by']);
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

        $filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('school') . $ex_where);

        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table('school') . $ex_where .
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

    $school_list = $GLOBALS['db']->getAll($sql);

//     $count = count($school_list);
//     for ($i=0; $i<$count; $i++)
//     {
//         $school_list[$i]['createtime'] = local_date($GLOBALS['_CFG']['date_format'], $school_list[$i]['createtime']);
//     }

    $arr = array('school_list' => $school_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}



/**
 * 创建学校数据库以及学校中需要的相关表
 * @param string $code
 */
function create_school_database($code){
	global $err;
	
	$GLOBALS['db']->open_transaction();
	
	//创建数据库
	$flag = $GLOBALS['db']->query("create database ".$code."_school");
	if($flag){
		try{
			
			$sql_files = array(ROOT_PATH . 'admin/install/school.sql');
			
			include_once(ROOT_PATH . 'admin/install/lib_installer.php');
					
			$flag = install_data($sql_files, $code."_school");
			
			if ($flag === false)
			{
				echo implode(',', $err->get_all());
			}
			
		}catch (Exception  $e){
			$GLOBALS['db']->query("drop database ".$code."_school");
			
		}
		
	}
	
	$GLOBALS['db']->close_transaction($flag);
	return $flag;
}

?>