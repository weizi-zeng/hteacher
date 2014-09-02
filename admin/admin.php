<?php

/**
 * ECSHOP 管理员信息以及权限管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: admin.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

/* 初始化 $exc 对象 */
$exc = new exchange($ecs->table("admin_user"), $db, 'user_id', 'user_name');


/*------------------------------------------------------ */
//-- 管理员列表页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 模板赋值 */
    $smarty->assign('ur_here',     "超级管理员列表");
    $smarty->assign('action_link', array('href'=>'admin.php?act=add', 'text' => "添加超级管理员列表"));
    $smarty->assign('full_page',   1);
    $smarty->assign('admin_list',  get_admin_list());
    
    /* 显示页面 */
    assign_query_info();
    
    $smarty->display('admin_list.htm');
}

/*------------------------------------------------------ */
//-- 查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $smarty->assign('admin_list',  get_admin_list());

    make_json_result($smarty->fetch('admin_list.htm'));
}

/*------------------------------------------------------ */
//-- 添加管理员页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('admin_manage');
    

     /* 模板赋值 */
    $smarty->assign('ur_here',     "添加超级管理员");
    $smarty->assign('action_link', array('href'=>'admin.php?act=list', 'text' => "超级管理员列表"));
    $smarty->assign('form_act',    'insert');
    $smarty->assign('action',      'add');
    
    /* 显示页面 */
    assign_query_info();
    $smarty->display('admin_info.htm');
}

/*------------------------------------------------------ */
//-- 添加管理员的处理
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    admin_priv('admin_manage');
    
    /* 判断管理员是否已经存在 */
    if (!empty($_POST['user_name']))
    {
        $is_only = $exc->is_only('user_name', stripslashes($_POST['user_name']));

        if (!$is_only)
        {
            sys_msg(sprintf("账号已存在", stripslashes($_POST['user_name'])), 1);
        }
    }

    /* Email地址是否有重复 */
    if (!empty($_POST['email']))
    {
        $is_only = $exc->is_only('email', stripslashes($_POST['email']));

        if (!$is_only)
        {
            sys_msg(sprintf("此邮箱已经被使用", stripslashes($_POST['email'])), 1);
        }
    }

    /* 获取添加日期及密码 */
    $add_time = gmtime();
    
    $password  = md5($_POST['password']);
    
    $sql = "INSERT INTO ".$ecs->table('admin_user')." (user_name, email, cellphone, password, add_time, nav_list, action_list, role_id, status_id) ".
           "VALUES ('".trim($_POST['user_name'])."', '".trim($_POST['email'])."', '".trim($_POST['cellphone'])."','$password', '$add_time', '', 'all', '0', '0')";

    $db->query($sql);
    
    /* 记录管理员操作 */
    admin_log($_POST['user_name'], 'add', 'admin');
    
    /*添加链接*/
    $link[0]['text'] = "继续添加";
    $link[0]['href'] = 'admin.php?act=add';
    
    $link[1]['text'] = "返回列表";
    $link[1]['href'] = 'admin.php?act=list';
    
    sys_msg("添加用户 &nbsp;" .$_POST['user_name'] . "&nbsp; 成功！",0, $link);
 }

/*------------------------------------------------------ */
//-- 编辑管理员信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
   
    $_REQUEST['id'] = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

    /* 不能编辑super这个管理员 */
    if ($_REQUEST['id'] == 1)
    {
    	$link[] = array('text' => "返回列表", 'href'=>'admin.php?act=list');
    	sys_msg("超级管理员的信息不能被修改", 0, $link);
    }
    
    /* 查看是否有权限编辑其他管理员的信息 */
    if ($_SESSION['admin_id'] != $_REQUEST['id'])
    {
        admin_priv('admin_manage');
    }

    /* 获取管理员信息 */
    $user_info = get_admin_by_id($_REQUEST['id']);

    /* 模板赋值 */
    $smarty->assign('ur_here',     "编辑超级管理员信息");
    $smarty->assign('action_link', array('text' => "超级管理员列表", 'href'=>'admin.php?act=list'));
    $smarty->assign('user',        $user_info);

    /* 获得该管理员的权限 */
    
    $smarty->assign('form_act',    'update');
    $smarty->assign('action',      'edit');

    assign_query_info();
    $smarty->display('admin_info.htm');
}

/*------------------------------------------------------ */
//-- 更新管理员信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update')
{

    /* 变量初始化 */
    $admin_id    = !empty($_REQUEST['id'])        ? intval($_REQUEST['id'])      : 0;
    $admin_name  = !empty($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : '';
    $admin_email = !empty($_REQUEST['email'])     ? trim($_REQUEST['email'])     : '';
    $admin_cellphone = !empty($_REQUEST['cellphone'])     ? trim($_REQUEST['cellphone'])     : '';
    $admin_school_code = !empty($_REQUEST['school_code'])     ? trim($_REQUEST['school_code'])     : '';
    
    $ec_salt=rand(1,9999);
    $password = !empty($_POST['new_password']) ? ", password = '".md5(md5($_POST['new_password']).$ec_salt)."'"    : '';

    /* 判断管理员是否已经存在 */
    if (!empty($admin_name))
    {
        $is_only = $exc->num('user_name', $admin_name, $admin_id);
        if ($is_only == 1)
        {
            sys_msg(sprintf("账号已经存在", stripslashes($admin_name)), 1);
        }
    }

    /* Email地址是否有重复 */
    if (!empty($admin_email))
    {
        $is_only = $exc->num('email', $admin_email, $admin_id);

        if ($is_only == 1)
        {
            sys_msg(sprintf("邮箱已经被使用", stripslashes($admin_email)), 1);
        }
    }

    //如果要修改密码
    $pwd_modified = false;

    if (!empty($_POST['new_password']))
    {
        /* 查询旧密码并与输入的旧密码比较是否相同 */
        $sql = "SELECT password FROM ".$ecs->table('admin_user')." WHERE user_id = '$admin_id'";
        $old_password = $db->getOne($sql);
		$sql ="SELECT ec_salt FROM ".$ecs->table('admin_user')." WHERE user_id = '$admin_id'";
        $old_ec_salt= $db->getOne($sql);
		if(empty($old_ec_salt))
	    {
			$old_ec_password=md5($_POST['old_password']);
		}
		else
	    {
			$old_ec_password=md5(md5($_POST['old_password']).$old_ec_salt);
		}
        if ($old_password <> $old_ec_password)
        {
           $link[] = array('text' => $_LANG['go_back'], 'href'=>'javascript:history.back(-1)');
           sys_msg("原始密码输入不正确！", 0, $link);
        }

        /* 比较新密码和确认密码是否相同 */
        if ($_POST['new_password'] <> $_POST['pwd_confirm'])
        {
           $link[] = array('text' => "返回", 'href'=>'javascript:history.back(-1)');
           sys_msg("你输入的新密码与确认密码不一致！", 0, $link);
        }
        else
        {
            $pwd_modified = true;
        }
    }

    //更新管理员信息
    if($pwd_modified)
    {
        $sql = "UPDATE " .$ecs->table('admin_user'). " SET ".
               "user_name = '$admin_name', ".
               "email = '$admin_email', ".
	           "cellphone = '$admin_cellphone', ".
               "ec_salt = '$ec_salt' ".
               $password.
               "WHERE user_id = '$admin_id'";
    }
    else
    {
        $sql = "UPDATE " .$ecs->table('admin_user'). " SET ".
               "user_name = '$admin_name', ".
               "email = '$admin_email', ".
	           "cellphone = '$admin_cellphone' ".
               "WHERE user_id = '$admin_id'";
    }

   $db->query($sql);
   /* 记录管理员操作 */
   admin_log($_POST['user_name'], 'edit', 'admin');

   /* 如果修改了密码，则需要将session中该管理员的数据清空 */
   if ($pwd_modified)
   {
       $sess->delete_spec_admin_session($admin_id);
   }
   $msg = "修改“".$_POST['user_name']."”管理员信息成功";

   $self = $_SESSION["admin_id"]==$admin_id;
   /* 提示信息 */
   $link[] = array('text' => "返回超级管理员列表", 'href'=>"admin.php?act=list");
   sys_msg("$msg<script>parent.document.getElementById('header-frame').contentWindow.document.location.reload();</script>", 0, $link);
   
}

/*------------------------------------------------------ */
//-- 编辑个人资料
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'modif')
{
    /* 不能编辑demo这个管理员 */
    if ($_SESSION['admin_name'] == 'demo')
    {
       $link[] = array('text' => $_LANG['back_admin_list'], 'href'=>'admin.php?act=list');
       sys_msg($_LANG['edit_admininfo_cannot'], 0, $link);
    }

    include_once('includes/inc_menu.php');
    include_once('includes/inc_priv.php');

    /* 包含插件菜单语言项 */
    $sql = "SELECT code FROM ".$ecs->table('plugins');
    $rs = $db->query($sql);
    while ($row = $db->FetchRow($rs))
    {
        /* 取得语言项 */
        if (file_exists(ROOT_PATH.'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php'))
        {
            include_once(ROOT_PATH.'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php');
        }

        /* 插件的菜单项 */
        if (file_exists(ROOT_PATH.'plugins/'.$row['code'].'/languages/inc_menu.php'))
        {
            include_once(ROOT_PATH.'plugins/'.$row['code'].'/languages/inc_menu.php');
        }
    }

    foreach ($modules AS $key => $value)
    {
        ksort($modules[$key]);
    }
    ksort($modules);

    foreach ($modules AS $key => $val)
    {
        if (is_array($val))
        {
            foreach ($val AS $k => $v)
            {
                if (is_array($purview[$k]))
                {
                    $boole = false;
                    foreach ($purview[$k] as $action)
                    {
                         $boole = $boole || admin_priv($action, '', false);
                    }
                    if (!$boole)
                    {
                        unset($modules[$key][$k]);
                    }
                }
                elseif (! admin_priv($purview[$k], '', false))
                {
                    unset($modules[$key][$k]);
                }
            }
        }
    }

    /* 获得当前管理员数据信息 */
    $sql = "SELECT user_id, user_name, email, nav_list ".
           "FROM " .$ecs->table('admin_user'). " WHERE user_id = '".$_SESSION['admin_id']."'";
    $user_info = $db->getRow($sql);

    /* 获取导航条 */
    $nav_arr = (trim($user_info['nav_list']) == '') ? array() : explode(",", $user_info['nav_list']);
    $nav_lst = array();
    foreach ($nav_arr AS $val)
    {
        $arr              = explode('|', $val);
        $nav_lst[$arr[1]] = $arr[0];
    }

    /* 模板赋值 */
    $smarty->assign('lang',        $_LANG);
    $smarty->assign('ur_here',     $_LANG['modif_info']);
    $smarty->assign('action_link', array('text' => $_LANG['admin_list'], 'href'=>'admin.php?act=list'));
    $smarty->assign('user',        $user_info);
    $smarty->assign('menus',       $modules);
    $smarty->assign('nav_arr',     $nav_lst);

    $smarty->assign('form_act',    'update_self');
    $smarty->assign('action',      'modif');

    /* 显示页面 */
    assign_query_info();
    $smarty->display('admin_info.htm');
}

/*------------------------------------------------------ */
//-- 为管理员分配权限
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'allot')
{
    include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/priv_action.php');

    admin_priv('allot_priv');
    if ($_SESSION['admin_id'] == $_GET['id'])
    {
        admin_priv('all');
    }

    /* 获得该管理员的权限 */
    $priv_str = $db->getOne("SELECT action_list FROM " .$ecs->table('admin_user'). " WHERE user_id = '$_GET[id]'");

    /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
    if ($priv_str == 'all')
    {
       $link[] = array('text' => "返回管理员列表", 'href'=>'admin.php?act=list');
       sys_msg("此用户为超级管理员，其权限不能被编辑", 0, $link);
    }

    /* 获取权限的分组数据 */
    $sql_query = "SELECT action_id, parent_id, action_code,relevance FROM " .$ecs->table('admin_action').
                 " WHERE parent_id = 0";
    $res = $db->query($sql_query);
    while ($rows = $db->FetchRow($res))
    {
        $priv_arr[$rows['action_id']] = $rows;
    }

    /* 按权限组查询底级的权限名称 */
    $sql = "SELECT action_id, parent_id, action_code,relevance FROM " .$ecs->table('admin_action').
           " WHERE parent_id " .db_create_in(array_keys($priv_arr));
    $result = $db->query($sql);
    while ($priv = $db->FetchRow($result))
    {
        $priv_arr[$priv["parent_id"]]["priv"][$priv["action_code"]] = $priv;
    }

    // 将同一组的权限使用 "," 连接起来，供JS全选
    foreach ($priv_arr AS $action_id => $action_group)
    {
        $priv_arr[$action_id]['priv_list'] = join(',', @array_keys($action_group['priv']));

        foreach ($action_group['priv'] AS $key => $val)
        {
            $priv_arr[$action_id]['priv'][$key]['cando'] = (strpos($priv_str, $val['action_code']) !== false || $priv_str == 'all') ? 1 : 0;
        }
    }

    /* 赋值 */
    $smarty->assign('lang',        $_LANG);
    $smarty->assign('ur_here',     $_LANG['allot_priv'] . ' [ '. $_GET['user'] . ' ] ');
    $smarty->assign('action_link', array('href'=>'admin.php?act=list', 'text' => $_LANG['admin_list']));
    $smarty->assign('priv_arr',    $priv_arr);
    $smarty->assign('form_act',    'update_allot');
    $smarty->assign('user_id',     $_GET['id']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('admin_allot.htm');
}

/*------------------------------------------------------ */
//-- 更新管理员的权限
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update_allot')
{
    admin_priv('admin_manage');
    if($_POST['token']!=$_CFG['token'])
    {
         sys_msg('update_allot_error', 1);
    }
    /* 取得当前管理员用户名 */
    $admin_name = $db->getOne("SELECT user_name FROM " .$ecs->table('admin_user'). " WHERE user_id = '$_POST[id]'");

    /* 更新管理员的权限 */
    $act_list = @join(",", $_POST['action_code']);
    $sql = "UPDATE " .$ecs->table('admin_user'). " SET action_list = '$act_list', role_id = '' ".
           "WHERE user_id = '$_POST[id]'";

    $db->query($sql);
    /* 动态更新管理员的SESSION */
    if ($_SESSION["admin_id"] == $_POST['id'])
    {
        $_SESSION["action_list"] = $act_list;
    }

    /* 记录管理员操作 */
    admin_log(addslashes($admin_name), 'edit', 'admin');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['back_admin_list'], 'href'=>'admin.php?act=list');
    sys_msg($_LANG['edit'] . "&nbsp;" . $admin_name . "&nbsp;" . $_LANG['action_succeed'], 0, $link);

}




/*------------------------------------------------------ */
//-- 编辑email
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_email')
{
	/* 检查权限 */
	check_authz_json('admin_manage');

	$id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	$email = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

	$sql = "SELECT user_name FROM " . $ecs->table('admin_user') . " WHERE user_id = '$id'";
	$user_name = $db->getOne($sql);

	if (is_email($email))
	{
		$sql = "update ".$ecs->table('admin_user')." set email='".$email."' WHERE user_id = '$id'";
		$db->query($sql);
		 
		admin_log(addslashes($user_name), 'edit', 'admin');

		make_json_result(stripcslashes($email));
	}
	else
	{
		make_json_error("您输入的邮箱不合法！");
	}
}

/*------------------------------------------------------ */
//-- 编辑mobtel
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_cellphone')
{
	/* 检查权限 */
	check_authz_json('admin_manage');

	$id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	$mobtel = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

	$sql = "SELECT user_name FROM " . $ecs->table('admin_user') . " WHERE user_id = '$id'";
	$user_name = $db->getOne($sql);

	$sql = "update ".$ecs->table('admin_user')." set cellphone='".$mobtel."' WHERE user_id = '$id'";
	$db->query($sql);

	admin_log(addslashes($user_name), 'edit', 'admin');

	make_json_result(stripcslashes($mobtel));
}

/*------------------------------------------------------ */
//-- 切换是active: 激活或者关闭此学校管理员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_active')
{
	check_authz_json('admin_manage');

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);
	
	//不能将super设置为不可用
	if ($id == 1)
	{
		make_json_error("super为最高管理员，不能设置为无效");
	}

	$sql = "update ".$ecs->table('admin_user')." set is_active=".$val." WHERE user_id = '$id'";
	$db->query($sql);

	clear_cache_files();

	make_json_result($val);
}


/*------------------------------------------------------ */
//-- 删除一个管理员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('admin_drop');

    $id = intval($_GET['id']);

    /* 获得管理员用户名 */
    $admin_name = $db->getOne('SELECT user_name FROM '.$ecs->table('admin_user')." WHERE user_id='$id'");

    /* super这个管理员不允许删除 */
    if ($admin_name == 'super' || $id == 1)
    {
        make_json_error("super为最高管理员，不能被删除！");
    }

    /* 管理员不能删除自己 */
    if ($id == $_SESSION['admin_id'])
    {
        make_json_error("不能删除自己");
    }

    if ($exc->drop($id))
    {
        $sess->delete_spec_admin_session($id); // 删除session中该管理员的记录

        admin_log(addslashes($admin_name), 'remove', 'admin');
        clear_cache_files();
    }

    $url = 'admin.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/* 获取管理员列表 */
function get_admin_list()
{
    $list = array();
    $sql  = 'SELECT * '.
            'FROM ' .$GLOBALS['ecs']->table('admin_user').' where status_id=0 ORDER BY user_id DESC';
    $list = $GLOBALS['db']->getAll($sql);

    foreach ($list AS $key=>$val)
    {
        $list[$key]['add_time']     = local_date($GLOBALS['_CFG']['time_format'], $val['add_time']);
        $list[$key]['last_login']   = local_date($GLOBALS['_CFG']['time_format'], $val['last_login']);
    }

    return $list;
}



?>
