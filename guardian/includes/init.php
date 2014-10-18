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

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

define('ECS_ADMIN', true);

error_reporting(E_ALL);

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}


include('../data/config.php');

/* 取得当前ecshop所在的根目录 */
define('ROOT_PATH', str_replace(GURADIAN_PATH . '/includes/init.php', '', str_replace('\\', '/', __FILE__)));

if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

// if (PHP_VERSION >= '5.1' && !empty($timezone))
// {
//     date_default_timezone_set($timezone);
// }

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

require(ROOT_PATH . 'includes/inc_constant.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_error.php');
require(ROOT_PATH . 'includes/lib_time.php');
require(ROOT_PATH . 'includes/lib_base.php');
require(ROOT_PATH . 'includes/lib_common.php');
require(ROOT_PATH . 'includes/lib_new_common.php');
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

/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);

/* 初始化数据库类 */
require(ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);

/* 创建错误处理对象 */
$err = new ecs_error('message.htm');

/* 初始化session */
require(ROOT_PATH . 'includes/cls_session.php');
$sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'), 'ECSCP_ID');

/* 必须获取学校代码*/
$school_code = "";
if (!isset($_SESSION['school_code']) || trim($_SESSION["school_code"])=='')
{
	/* session 不存在，检查cookie */
	if (!empty($_COOKIE['ECSCP']['school_code']) && !empty($_COOKIE['ECSCP']['school_code']))
	{
		$school_code = $_COOKIE['ECSCP']['school_code'];
	}else {
		ecs_header("Location: ../login.php\n");
		exit();
	}
}else {
	$school_code = $_SESSION["school_code"];
}

/* 必须获取班级代码*/
$class_code = "";
if (!isset($_SESSION['class_code']) || trim($_SESSION["class_code"])=='')
{
	/* session 不存在，检查cookie */
	if (!empty($_COOKIE['ECSCP']['class_code']) && !empty($_COOKIE['ECSCP']['class_code']))
	{
		$class_code = $_COOKIE['ECSCP']['class_code'];
	}else {
		ecs_header("Location: ../login.php\n");
		exit();
	}
}else {
	$class_code = $_SESSION["class_code"];
}

if($school_code=='super' || $class_code=='super'){
	clearstatcache();
	ecs_header("Location: ../login.php\n");
	exit();
}

$db_name = $school_code."_school";
/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);
define('DATA_DIR', $ecs->data_dir());
define('IMAGE_DIR', $ecs->image_dir());

/*重新连接数据库*/
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;


/* 初始化 action */
if (!isset($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}

/* 载入系统参数 */
$_CFG = load_config();

require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/common.php');
require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/log_action.php');
if (file_exists(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/admin/' . basename(PHP_SELF)))
{
	include(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/admin/' . basename(PHP_SELF));
}

if (!file_exists('../temp/caches'))
{
    @mkdir('../temp/caches', 0777);
    @chmod('../temp/caches', 0777);
}

if (!file_exists('../temp/compiled/'.GURADIAN_PATH))
{
    @mkdir('../temp/compiled/'.GURADIAN_PATH, 0777);
    @chmod('../temp/compiled/'.GURADIAN_PATH, 0777);
}

clearstatcache();


/* 创建 Smarty 对象。*/
require(ROOT_PATH . 'includes/cls_template.php');
$smarty = new cls_template;

$smarty->template_dir  = ROOT_PATH . GURADIAN_PATH . '/templates';
$smarty->compile_dir   = ROOT_PATH . 'temp/compiled/'.GURADIAN_PATH;
if ((DEBUG_MODE & 2) == 2)
{
    $smarty->force_compile = true;
}

$smarty->assign('lang', $_LANG);

/* 验证管理员身份 */
if (!isset($_SESSION['admin_id']) || intval($_SESSION['admin_id']) <= 0)
{
	
    /* session 不存在，检查cookie */
    if (!empty($_COOKIE['ECSCP']['admin_id']) && !empty($_COOKIE['ECSCP']['admin_pass']))
    {
        // 找到了cookie, 验证cookie信息
        $sql = 'SELECT * ' .
                ' FROM hteacher.ht_admin_user '.
                " WHERE user_id = '" . intval($_COOKIE['ECSCP']['admin_id']) . "'";
        $row = $db->GetRow($sql);

        if (!$row)
        {
            // 没有找到这个记录
            setcookie($_COOKIE['ECSCP']['admin_id'],   '', 1);
            setcookie($_COOKIE['ECSCP']['admin_pass'], '', 1);

            if (!empty($_REQUEST['is_ajax']))
            {
                make_json_error($_LANG['priv_error']);
            }
            else
            {
//             	die("HTTP_REFERER2");
                ecs_header("Location: ../login.php\n");
            }

            exit;
        }
        else
        {
            // 检查密码是否正确
            if (md5($row['password'] . $_CFG['hash_code']) == $_COOKIE['ECSCP']['admin_pass'])
            {
                !isset($row['last_time']) && $row['last_time'] = '';
                set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['role_id'], $row['status_id'], $row['school_code'], $row['class_code']);

                // 更新最后登录时间和IP
                $db->query('UPDATE hteacher.ht_admin_user '.
                            " SET last_login = '" . gmtime() . "', last_ip = '" . real_ip() . "'" .
                            " WHERE user_id = '" . $_SESSION['admin_id'] . "'");
            }
            else
            {
                setcookie($_COOKIE['ECSCP']['admin_id'],   '', 1);
                setcookie($_COOKIE['ECSCP']['admin_pass'], '', 1);

                if (!empty($_REQUEST['is_ajax']))
                {
                    make_json_error($_LANG['priv_error']);
                }
                else
                {
//                 	die("HTTP_REFERER3");
                    ecs_header("Location: ../login.php\n");
                }

                exit;
            }
        }
    }
    else
    {
        if (!empty($_REQUEST['is_ajax']))
        {
            make_json_error($_LANG['priv_error']);
        }
        else
        {
        	die("HTTP_REFERER4");
            ecs_header("Location: ../login.php\n");
        }

        exit;
    }
}


if ($_REQUEST['act'] != 'signin' )
{
    $admin_path = preg_replace('/:\d+/', '', $ecs->url()) . GURADIAN_PATH;
    
    if (!empty($_SERVER['HTTP_REFERER']) &&
        strpos(preg_replace('/:\d+/', '', $_SERVER['HTTP_REFERER']), $admin_path) === false)
    {
    	
        if (!empty($_REQUEST['is_ajax']))
        {
            make_json_error($_LANG['priv_error']);
        }
        else
        {
        	die("HTTP_REFERER5");
            ecs_header("Location: ../login.php\n");
        }

        exit;
    }
}


//header('Cache-control: private');
header('content-type: text/html; charset=' . EC_CHARSET);
header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if ((DEBUG_MODE & 1) == 1)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(E_ALL ^ E_NOTICE);
}
if ((DEBUG_MODE & 4) == 4)
{
    include(ROOT_PATH . 'includes/lib.debug.php');
}

/* 判断是否支持gzip模式 */
if (gzip_enabled())
{
    ob_start('ob_gzhandler');
}
else
{
    ob_start();
}

/* 允许上传的文件类型 */
$allow_file_types = '|CSV|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|XLSX|CSV|';

?>
