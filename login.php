<?php

/**
 * ECSHOP 管理中心公用文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: init.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('ECS_ADMIN', true);

error_reporting(E_ALL);

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}


/* 初始化设置 */
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);
setlocale(LC_ALL, 'zh_CN');

include('data/config.php');

define('ROOT_PATH', str_replace('login.php', '', str_replace('\\', '/', __FILE__)));

if (DIRECTORY_SEPARATOR == '\\')
{
	@ini_set('include_path',      '.;' . ROOT_PATH);
}
else
{
	@ini_set('include_path',      '.:' . ROOT_PATH);
}

if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

if (PHP_VERSION >= '5.1' && !empty($timezone))
{
    date_default_timezone_set($timezone);
}

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

define('IN_ECS',true);

require(ROOT_PATH . 'includes/inc_constant.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_error.php');
require(ROOT_PATH . 'includes/lib_time.php');
require(ROOT_PATH . 'includes/lib_base.php');
require(ROOT_PATH . 'includes/lib_common.php');
require(ROOT_PATH . ADMIN_PATH . '/includes/lib_main.php');
require(ROOT_PATH . ADMIN_PATH . '/includes/cls_exchange.php');


/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}

/* 对路径进行安全处理 */
if (strpos(PHP_SELF, '.php/') !== false)
{
    ecs_header("Location:" . substr(PHP_SELF, 0, strpos(PHP_SELF, '.php/') + 4) . "\n");
    exit();
}

/* 创建 ECSHOP 对象  TODO*/
$ecs = new ECS($db_name, $prefix);
define('DATA_DIR', $ecs->data_dir());
define('IMAGE_DIR', $ecs->image_dir());

/* 初始化数据库类 */
require(ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;

/* 创建错误处理对象 */
$err = new ecs_error('message.htm');

/* 初始化session */
require(ROOT_PATH . 'includes/cls_session.php');
$sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'), 'ECSCP_ID');

/* 载入系统参数 */
// $_CFG = load_config();
$_CFG = array(
	"lang"=>"zh_cn",
	"template"=>"default",
	"hash_code"=>"feb0af8dda696ecc1a9ba790973096a8",
	"captcha"=>"0" //注释掉验证码
);

/* 初始化 action */
if (!isset($_REQUEST['act']))
{
	$_REQUEST['act'] = '';
}

require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/common.php');
require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/log_action.php');

if (file_exists(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/admin/' . basename(PHP_SELF)))
{
	include(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/admin/' . basename(PHP_SELF));
}

/* 创建 Smarty 对象。*/
require(ROOT_PATH . 'includes/cls_template.php');
$smarty = new cls_template;

$smarty->template_dir   = ROOT_PATH . 'themes/' . $_CFG['template'];
$smarty->assign('lang', $_LANG);



/*------------------------------------------------------ */
//-- 验证登陆信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'signin')
{
	
	if (!empty($_SESSION['captcha_word']) && (intval($_CFG['captcha']) & CAPTCHA_ADMIN))
	{
		include_once(ROOT_PATH . 'includes/cls_captcha.php');

		/* 检查验证码是否正确 */
		$validator = new captcha();
		if (!empty($_POST['captcha']) && !$validator->check_word($_POST['captcha']))
		{
			login_display("验证码错误");
		}
	}

	$_POST['username'] = isset($_POST['username']) ? trim($_POST['username']) : '';
	$_POST['password'] = isset($_POST['password']) ? trim($_POST['password']) : '';

	$sql="SELECT `ec_salt` FROM ". $ecs->table('admin_user') ."WHERE user_name = '" . $_POST['username']."'";
	
	$ec_salt =$db->getOne($sql);
	if(!empty($ec_salt))
	{
		/* 检查密码是否正确 */
		$sql = "SELECT * ".
            " FROM " . $ecs->table('admin_user') .
            " WHERE user_name = '" . $_POST['username']. "' AND password = '" . md5(md5($_POST['password']).$ec_salt) . "'";
	}
	else
	{
		/* 检查密码是否正确 */
		$sql = "SELECT * ".
            " FROM " . $ecs->table('admin_user') .
            " WHERE user_name = '" . $_POST['username']. "' AND password = '" . md5($_POST['password']) . "'";
	}
	$row = $db->getRow($sql);
	
	if ($row)// 登录成功
	{
		if(!$row["is_active"])
		{
			login_display("此用户已经被注销，请联系超级管理员激活");
		}
		
		//将用户信息记录到session
		set_admin_session($row['user_id'], $row['user_name'], $row['action_list'],
		 $row['role_id'], $row['status_id'],$row['school_code'],$row['class_code']);
		
		if(empty($row['ec_salt']))
		{
			$ec_salt=rand(1,9999);
			$new_possword=md5(md5($_POST['password']).$ec_salt);
			$db->query("UPDATE " .$ecs->table('admin_user').
		                 " SET ec_salt='" . $ec_salt . "', password='" .$new_possword . "'".
		                 " WHERE user_id='$_SESSION[admin_id]'");
		}

		// 更新最后登录时间和IP
		$db->query("UPDATE " .$ecs->table('admin_user').
                 " SET last_login='" . gmtime() . "', last_ip='" . real_ip() . "'".
                 " WHERE user_id='$_SESSION[admin_id]'");

		if (isset($_POST['remember']))
		{
			$time = gmtime() + 3600 * 24 * 365;
			setcookie('ECSCP[admin_id]',   $row['user_id'],                            $time);
			setcookie('ECSCP[admin_pass]', md5($row['password'] . $_CFG['hash_code']), $time);
			setcookie('ECSCP[status_id]',   $row['status_id'],                            $time);
			setcookie('ECSCP[school_code]',   $row['school_code'],                            $time);
			setcookie('ECSCP[class_code]',   $row['class_code'],                            $time);
		}

		/**
		* status_id=0 : 超级管理员
		* status_id=1 : 学校管理员
		* status_id=2 : 班级管理员
		* status_id=3 : 教师
		* status_id=4 : 监护人
		*/
		if($row['status_id']==0){//超级管理员系统
			ecs_header("Location: admin/index.php?act=signin\n");
			
		}else if($row['status_id']==1){//学校管理系统
			ecs_header("Location: school/index.php?act=signin\n");
			
		}else {//班级管理系统
			ecs_header("Location: hteacher/index.php?act=signin\n");
			
		}
		
		exit;
	}
	else
	{
		login_display("账户或密码不正确");
	}
}

/*------------------------------------------------------ */
//-- 退出登录
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'logout')
{
	/* 清除cookie */
	setcookie('ECSCP[admin_id]',   '', 1);
	setcookie('ECSCP[admin_pass]', '', 1);
	setcookie('ECSCP[status_id]',   '', 1);
	setcookie('ECSCP[school_code]', '', 1);
	setcookie('ECSCP[class_code]', '', 1);
	
	$sess->destroy_session();

	login_display();
}


//获取验证码
elseif ($_REQUEST['act'] == 'captcha')
{
    include(ROOT_PATH . 'includes/cls_captcha.php');

    $img = new captcha('data/captcha/');
    @ob_end_clean(); //清除之前出现的多余输入
    $img->generate_image();

    exit;
}

login_display();


/**
* 系统登录
*
* @access      public
* @param       string      msg_detail      消息内容
* @return      void
*/
function login_display($msg_detail='')
{
	$GLOBALS['smarty']->assign('msg_detail',  $msg_detail);
	if ((intval($GLOBALS['_CFG']['captcha']) & CAPTCHA_ADMIN) && gd_version() > 0)
	{
		$GLOBALS['smarty']->assign('gd_version', gd_version());
		$GLOBALS['smarty']->assign('random',     mt_rand());
	}
	
	//展示登录界面
	$GLOBALS['smarty']->display('login.htm');
	exit;
}

?>
