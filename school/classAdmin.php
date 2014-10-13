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
 * $Id: classAdmin.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . '/includes/cls_sms.php');

/*------------------------------------------------------ */
//-- 班级管理员帐号列表
/*------------------------------------------------------ */
$table = "hteacher.ht_admin_user";

if ($_REQUEST['act'] == 'list')
{
    /* 检查权限 */
    admin_priv('classAdmin_manage');
    
    $smarty->assign('ur_here',      "班级管理员列表");
    $smarty->assign('action_link',  array('text' => "添加班级管理员", 'href'=>'classAdmin.php?act=add'));

    $classAdmin_list = classAdmin_list($table);
    $class_list = class_list();
    $grade_list = grade_list();

    $smarty->assign('classAdmin_list',    $classAdmin_list['classAdmin_list']);
    $smarty->assign('class_list',       $class_list);
    $smarty->assign('grade_list',       $grade_list);
    $smarty->assign('filter',       $classAdmin_list['filter']);
    $smarty->assign('record_count', $classAdmin_list['record_count']);
    $smarty->assign('page_count',   $classAdmin_list['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('sort_classAdmin_id', '<img src="images/sort_desc.gif">');

    assign_query_info();
    $smarty->display('classAdmin_list.htm');
}

/*------------------------------------------------------ */
//-- ajax返回班级管理员列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $classAdmin_list = classAdmin_list($table);

    $smarty->assign('classAdmin_list',    $classAdmin_list['classAdmin_list']);
    $smarty->assign('filter',       $classAdmin_list['filter']);
    $smarty->assign('record_count', $classAdmin_list['record_count']);
    $smarty->assign('page_count',   $classAdmin_list['page_count']);

    $sort_flag  = sort_flag($classAdmin_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('classAdmin_list.htm'), '', array('filter' => $classAdmin_list['filter'], 'page_count' => $classAdmin_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加帐号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('classAdmin_manage');
	
    $smarty->assign('ur_here',         "添加班级管理员");
    $smarty->assign('action_link',      array('text' => "班级管理员列表", 'href'=>'classAdmin.php?act=list'));
    $smarty->assign('form_action',      'insert');

    $class_list = class_list();
    $smarty->assign('class_list',       $class_list);
    
    assign_query_info();
    $smarty->display('classAdmin_info.htm');
}

/*------------------------------------------------------ */
//-- 添加帐号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    /* 检查权限 */
    admin_priv('classAdmin_manage');
    
    $code = empty($_POST['class_code']) ? '' : trim($_POST['class_code']);
    //TODO 对code进行验证
    if($code==''){
    	sys_msg("所属班级不能为空！", 1);
    }
    
    $user_name = empty($_POST['user_name']) ? '' : trim($_POST['user_name']);
    /* 判断管理员是否已经存在 */
    if (!empty($_POST['user_name']))
    {
    		$is_only = is_only($table, 'user_name', stripslashes($_POST['user_name']));
    
            if (!$is_only)
		    {
		    	sys_msg(sprintf($_POST['user_name']."账号已存在", stripslashes($_POST['user_name'])), 1);
		    }
    }
    
    /* Email地址是否有重复 */
    if (!empty($_POST['email']))
    {
     	$is_only = is_only($table, 'email', stripslashes($_POST['email']));
    
     	if (!$is_only)
     	{
     		sys_msg(sprintf($_POST['email']."此邮箱已经被使用", stripslashes($_POST['email'])), 1);
     	}
     }
    
     /* 获取添加日期及密码 */
     $add_time = gmtime();
    
     $password  = md5($_POST['password']);
    
     $sql = "INSERT INTO ".$table." (user_name, name, email, cellphone, password, add_time, nav_list, action_list, role_id, status_id, school_code, class_code, creator_id) ".
               "VALUES ('".trim($_POST['user_name'])."', '".trim($_POST['name'])."','".trim($_POST['email'])."', '".trim($_POST['cellphone'])."','$password', '$add_time', '', 'all', '0', '2', '".$_SESSION["school_code"]."', '".$code."','".$_SESSION["admin_id"]."')";
    
    $db->query($sql);
    
        /* 记录管理员操作 */
    admin_log($_POST['user_name'], 'add', 'admin');

    $msg = "创建班级管理员 &nbsp;" .$_POST['user_name'] . "&nbsp; 成功！";
    //发生短信逻辑
    $smskey = empty($_POST['sms']) ? '' : trim($_POST['sms']);
    if($smskey=='send'){
    	$content = trim($_POST['name'])."您好！您在我爱我班系统的账号已开通：".trim($_POST['user_name'])."/".$_POST['password'];
    	$sms = new sms();
    	$result = $sms->send(trim($_POST['cellphone']), $content, "", "", $_SESSION["admin_name"]);
    	if($result["error"]!=0){
    		$msg.= $result["msg"];
    	}else {
    		$msg.= "并且短信发生成功！";
    	}
    }
    
    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'classAdmin.php?act=list');
    sys_msg($msg,0, $link);
}

/*------------------------------------------------------ */
//-- 编辑班级管理员帐号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('classAdmin_manage');

    $user = get_admin_by_id($_GET[id]);
    
    assign_query_info();
    
    $class_list = class_list();
    $smarty->assign('class_list',       $class_list);
    
    $smarty->assign('ur_here',          "编辑班级管理员信息");
    $smarty->assign('action_link',      array('text' => "班级管理员列表", 'href'=>'classAdmin.php?act=list&' . list_link_postfix()));
    $smarty->assign('user',             $user);
    $smarty->assign('form_action',      'update');
    $smarty->display('classAdmin_info.htm');
}

/*------------------------------------------------------ */
//-- 更新班级管理员帐号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('classAdmin_manage');
    
    
    /* 变量初始化 */
    $admin_id    = !empty($_REQUEST['id'])        ? intval($_REQUEST['id'])      : 0;
    $admin_name  = !empty($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : '';
    $name  = !empty($_REQUEST['name']) ? trim($_REQUEST['name']) : '';
    $admin_email = !empty($_REQUEST['email'])     ? trim($_REQUEST['email'])     : '';
    $admin_cellphone = !empty($_REQUEST['cellphone'])     ? trim($_REQUEST['cellphone'])     : '';
    $admin_class_code = !empty($_REQUEST['class_code'])     ? trim($_REQUEST['class_code'])     : '';
    
    $ec_salt=rand(1,9999);
    $password = !empty($_POST['new_password']) ? ", password = '".md5(md5($_POST['new_password']).$ec_salt)."'"    : '';
    
	/* 判断管理员是否已经存在 */
    if (!empty($_REQUEST['user_name']))
    {
    		$is_only = is_only($table, 'user_name', stripslashes($_REQUEST['user_name']), " user_id<>".$admin_id);
    
            if (!$is_only)
		    {
		    	sys_msg(sprintf($_REQUEST['user_name']."账号已存在", stripslashes($_REQUEST['user_name'])), 1);
		    }
    }
    
    /* Email地址是否有重复 */
    if (!empty($_REQUEST['email']))
    {
     	$is_only = is_only($table, 'email', stripslashes($_REQUEST['email']) , " user_id<>".$admin_id);
    
     	if (!$is_only)
     	{
     		sys_msg(sprintf($_REQUEST['email']."此邮箱已经被使用", stripslashes($_REQUEST['email'])), 1);
     	}
     }
    
    //如果要修改密码
    $pwd_modified = false;
    
    if (!empty($_POST['new_password']))
    {
    	/* 比较新密码和确认密码是否相同 */
    	if ($_POST['new_password'] <> $_POST['pwd_confirm'])
    	{
    		$link[] = array('text' => "返回", 'href'=>'javascript:history.back(-1)');
    		sys_msg("你输入的重置密码与确认重置密码不一致！", 0, $link);
    	}
    	else
    	{
    		$pwd_modified = true;
    	}
    }
    
    //更新管理员信息
    if($pwd_modified)
    {
    	$sql = "UPDATE " .$table. " SET ".
                   "user_name = '$admin_name', ".
    				"name = '$name', ".
                   "email = '$admin_email', ".
    	        "cellphone = '$admin_cellphone', ".
    	        "class_code = '$admin_class_code', ".
                   "ec_salt = '$ec_salt' ".
		    	$password.
                   "WHERE user_id = '$admin_id'";
    }
    else
    {
    	$sql = "UPDATE " .$table. " SET ".
                   "user_name = '$admin_name', ".
    				"name = '$name', ".
                   "email = '$admin_email', ".
    	        "cellphone = '$admin_cellphone', ".
    	        "class_code = '$admin_class_code' ".
                   "WHERE user_id = '$admin_id'";
    }
    
    $db->query($sql);
    
    /* 记录管理员操作 */
    admin_log($_POST['user_name'], 'edit', 'classAdmin');
    
    /* 如果修改了密码，则需要将session中该管理员的数据清空 */
    $msg = "修改“".$_POST['user_name']."”管理员信息成功！";
    
    //发生短信逻辑
    $smskey = empty($_POST['sms']) ? '' : trim($_POST['sms']);
    if($smskey=='send'){
    	$content = $name."您好！您的账号已被重置：".trim($_REQUEST['user_name'])."/".$_POST['new_password'];
    	$sms = new sms();
    	$result = $sms->send($admin_cellphone, $content, "", "", $_SESSION["admin_name"]);
    	if($result["error"]!=0){
    		$msg.= $result["msg"];
    	}else {
    		$msg.= "并且短信发生成功！";
    	}
    }
    
    /* 提示信息 */
    $links[0]['text']    = $_LANG['goto_list'];
    $links[0]['href']    = 'classAdmin.php?act=list&' . list_link_postfix();
    $links[1]['text']    = $_LANG['go_back'];
    $links[1]['href']    = 'javascript:history.back()';

    sys_msg($msg, 0, $links);

}



/*------------------------------------------------------ */
//-- 编辑name
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_name')
{
	/* 检查权限 */
	check_authz_json('classAdmin_manage');

	$id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	$name = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

	$sql = "SELECT user_name FROM " . $table . " WHERE user_id = '$id'";
	$user_name = $db->getOne($sql);

	$sql = "update ".$table." set name='".$name."' WHERE user_id = '$id'";
	$db->query($sql);

	admin_log(addslashes($user_name.','.$name), 'edit', 'classAdmin');

	make_json_result(stripcslashes($name));
}

/*------------------------------------------------------ */
//-- 编辑email
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_email')
{
    /* 检查权限 */
    check_authz_json('classAdmin_manage');

    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $email = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

    $sql = "SELECT user_name FROM " . $table . " WHERE user_id = '$id'";
    $user_name = $db->getOne($sql);


    if (is_email($email))
    {
    	$sql = "update ".$table." set email='".$email."' WHERE user_id = '$id'";
    	$db->query($sql);
    	
        admin_log(addslashes($user_name), 'edit', 'classAdmin');
		
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
elseif ($_REQUEST['act'] == 'edit_cellphone')
{
	/* 检查权限 */
	check_authz_json('classAdmin_manage');

	$id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	$mobtel = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

	$sql = "SELECT user_name FROM " . $table . " WHERE user_id = '$id'";
    $user_name = $db->getOne($sql);
    
	$sql = "update ".$table." set cellphone='".$mobtel."' WHERE user_id = '$id'";
	$db->query($sql);
	 
	admin_log(addslashes($user_name), 'edit', 'classAdmin');

	make_json_result(stripcslashes($mobtel));
}

/*------------------------------------------------------ */
//-- 切换是active: 激活或者关闭此班级管理员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_active')
{
	check_authz_json('classAdmin_manage');

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);

	$sql = "update ".$table." set is_active=".$val." WHERE user_id = '$id'";
	$db->query($sql);
	
	clear_cache_files();

	make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除班级管理员数据
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    /* 检查权限 */
    check_authz_json('classAdmin_manage');

    $sql = "SELECT * FROM " . $table . " WHERE user_id = '" . $_GET['id'] . "'";
    $classAdmin = $db->getRow($sql);
    
    $db->query("delete from " . $table . "  where user_id = '" . $_GET['id'] . "'");
    
    $sess->delete_spec_admin_session($_GET['id']); // 删除session中该管理员的记录
    
    /* 记录管理员操作 */
    admin_log(addslashes($classAdmin['user_name']), 'remove', 'classAdmin');
    clear_cache_files();
    
    $url = 'classAdmin.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    
    ecs_header("Location: $url\n");
    exit;
}

/**
 *  返回班级管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function classAdmin_list($table)
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//班级管理员名称
        $filter['grade_id'] = empty($_REQUEST['grade_id']) ? '' : trim($_REQUEST['grade_id']);//班级管理员code
        $filter['class_code'] = empty($_REQUEST['class_code']) ? '' : trim($_REQUEST['class_code']);//班级管理员code
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keywords'] = json_str_iconv($filter['keywords']);
        }

        $filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'user_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);

        $ex_where = " WHERE status_id=2 and school_code='".$_SESSION["school_code"]."' ";//班级管理员 status_id=2
        if ($filter['keywords'])
        {
            $ex_where .= " AND user_name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
        }
        if ($filter['class_code'])
        {
        	$ex_where .= " AND class_code = '" . mysql_like_quote($filter['class_code']) ."'";
        }
        if ($filter['grade_id'])
        {
        	$ex_where .= "AND class_code in (select class_code from ".$GLOBALS['ecs']->table("class")." where grade=".$filter['grade_id'].") ";
        }
        $filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $table . $ex_where);

        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT * ".
                " FROM " . $table . $ex_where .
                " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

//         echo $sql; echo '<br>';
        
        $filter['keywords'] = stripslashes($filter['keywords']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $classAdmin_list = $GLOBALS['db']->getAll($sql);
    foreach ($classAdmin_list AS $key=>$val)
    {
    	$classAdmin_list[$key]['add_time']     = local_date($GLOBALS['_CFG']['time_format'], $val['add_time']);
    	$classAdmin_list[$key]['last_login']   = local_date($GLOBALS['_CFG']['time_format'], $val['last_login']);
    	$classAdmin_list[$key]['status']   = get_status($val['status_id']);//status表示身份
    	$classAdmin_list[$key]['class']   = get_class_name($val['class_code']);
    }
    

    $arr = array('classAdmin_list' => $classAdmin_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}


?>