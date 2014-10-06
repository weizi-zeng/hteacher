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
require(ROOT_PATH . 'includes/lib_sms_template.php');
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

	$_REQUEST['username'] = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : '';
	$_REQUEST['password'] = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : '';
	$_REQUEST['status'] = isset($_REQUEST['status']) ? trim($_REQUEST['status']) : 'admin';
	
	$row = array();
	if($_REQUEST['status']=='guardian'){//如果是家长登陆，走家长登陆逻辑
		
		$guardian = getGuardianByUsername($_REQUEST['username']);
		if($guardian){
			if($guardian["has_left"]){//已经离校
				login_display("此账号相关人已经毕业离校");
				exit;
			}
			
			if($guardian["license"]){
				if($guardian["is_active"]){
					
					if($guardian["password"]==md5($_REQUEST['password'])){
						$row = $guardian;//进行正常登陆
						$row['status_id']= 4;
						$row['user_id'] = $guardian["student_id"];
						$row['user_name']= $guardian["guardian_name"];
						$row['school_code']= $guardian["school_code"];
						$row['class_code']= $guardian["class_code"];
						$row['password']= md5($guardian["password"]);
						$row['student_code']= $guardian["code"];
						$row['cellphone']= $guardian["guardian_phone"];
					}else {
						login_display("密码错误");
					}
				
				}else {
					login_display("此账号未激活");
				}
			}else {
				//调转到注册页面
				$warn = "请确认上面的信息正确无误，然后输入注册码，进行注册；<br/>若信息有误，请找管理员确认之后再进行注册！";
				register_display($guardian, $warn);
			}
			
		}else {
			login_display("账号不正确");
		}
		
	}else {
		
		
		//管理员正常登陆逻辑， 包括班主任（班级管理员）
		
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
	
		}
		else
		{
			login_display("账号或密码不正确");
		}
	
	}
	
	$row['school_code'] = str_replace("_school", "", $row['school_code']);
	//将用户信息记录到session
	set_admin_session($row['user_id'], $row['user_name'], $row['action_list'],
	$row['role_id'], $row['status_id'],$row['school_code'],$row['class_code'],$row['student_code'], $row['cellphone']);
	
	if (isset($_POST['remember']))
	{
		$time = gmtime() + 3600 * 24 * 365;
		setcookie('ECSCP[admin_id]',   $row['user_id'],                            $time);
		setcookie('ECSCP[admin_pass]', md5($row['password'] . $_CFG['hash_code']), $time);
		setcookie('ECSCP[status_id]',   $row['status_id'],                            $time);
		setcookie('ECSCP[school_code]',   $row['school_code'],                            $time);
		setcookie('ECSCP[class_code]',   $row['class_code'],                            $time);
		setcookie('ECSCP[student_code]',   $row['student_code'],                            $time);
		setcookie('ECSCP[phone]',   $row['cellphone'],                            $time);
	}
	
	/**
	 * status_id=0 : 超级管理员
	 * status_id=1 : 学校管理员
	 * status_id=2 : 班级管理员
	 * status_id=3 : 教师
	 * status_id=4 : 家长
	 */
	if($row['status_id']==0){
		//超级管理员系统
		ecs_header("Location: admin/index.php?act=signin\n");
	
	}else if($row['status_id']==1){
		//学校管理系统
		ecs_header("Location: school/index.php?act=signin\n");
	
	}else if($row['status_id']==2){
		//班级管理系统
		ecs_header("Location: teacher/index.php?act=signin\n");
	
	}else{
		//家长系统
		ecs_header("Location: guardian/index.php?act=signin\n");
	
	}
	exit;
	
}
/*------------------------------------------------------ */
//-- 加载提示信息跳转到注册页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'toReg')
{
	$guardian = getGuardianByUsername($_REQUEST['username']);
	if($guardian){
		if($guardian["has_left"]){
			//已经离校
			login_display("此账号相关人已经毕业离校");
			exit;
		}
		//调转到注册页面
		$warn = "请确认上面的信息正确无误，然后输入注册码，进行注册；<br/>若信息有误，请找管理员确认之后再进行注册！";
		register_display($guardian, $warn);
	}
	login_display("您输入的预留号码不正确");
}

/*------------------------------------------------------ */
//-- 家长注册
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'register')
{
	$_POST['regCode'] = isset($_POST['regCode']) ? trim($_POST['regCode']) : '';
	$_POST['school_code'] = isset($_POST['school_code']) ? trim($_POST['school_code']) : '';
	$_POST['password'] = isset($_POST['password']) ? trim($_POST['password']) : '';
	$_POST['student_id'] = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
	
	if(!$_POST['school_code'] || !$_POST['student_id']){
		login_display("会话失效");
	}
	
	$guardian = getGuardianById($_POST['student_id'], $_POST['school_code']);
	$guardian["school_code"] = $_POST['school_code'];
	
	//检验注册码
	$res = validateRegCode($_POST['regCode']);
	if($res["error"]>0){
		register_display($guardian, $res["msg"]);
		exit;
	}
	
	//进行注册
	$result = register_system($guardian, $_POST['school_code'], $_POST['regCode'], $_POST['password']);
	if($result["error"]!=0){
		die($result["msg"]);
	}
	ecs_header("Location: login.php?act=signin&username=".$guardian["guardian_phone"]."&password=".$_POST['password']."&status=guardian\n");
	exit;
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

/**
 * 用户注册
 * Enter description here ...
 */
function register_display($guardian, $warn){
	//展示注册界面
	if($guardian["sexuality"]==1){
		$guardian["sex"]="男";
	}else {
		$guardian["sex"]="女";
	}
// 	echo "guardian:"; print_r($guardian);
	$GLOBALS['smarty']->assign('warn',     $warn);
	$GLOBALS['smarty']->assign('guardian',     $guardian);
	$GLOBALS['smarty']->display('register.htm');
	exit;
}

/**
* 通过电话号码获取家长的信息
*/
function getGuardianByUsername($username){
	$sql = "show databases like '%_school' ";
	$rows = $GLOBALS['db']->getAll($sql);

	$res = false;
	if(count($rows)>0){
		foreach($rows as $row){
			foreach($row as $s){
				$sql = "select * from ".$s.".ht_student where guardian_phone='".$username."' or guardian_name='".$username."' limit 1";
				$res = $GLOBALS['db']->getRow($sql);
				if($res){
					$res["school_code"] = $s;
					return $res;
				}
			}
		}
	}
	return $res;
}

/**
 * 检验校验码
 * 1、是否正确
 * 2、是否有效
 */
function validateRegCode($regCode){
	$res = array("error"=>0,"msg"=>$regCode);
	$table = "hteacher.ht_license";
	$sql = "select * from ".$table." where license='".$regCode."'";
	$license = $GLOBALS["db"]->getRow($sql);
	if($license){
		if($license["removed"]){
			return array("error"=>1,"msg"=>"您的注册码已经被废弃！");
		}
		if($license["is_active"]){
			return array("error"=>1,"msg"=>"您的注册码已经被使用！");
		}
		
		$today = date("Y-m-d");
		if($license["sdate"]>$today){
			return array("error"=>1,"msg"=>"您的注册码要到".$license["sdate"]."才能生效！");
		}
		if($license["edate"]<$today){
			return array("error"=>1,"msg"=>"您的注册码在".$license["edate"]."已经失效！");
		}
	}else {
		return array("error"=>1,"msg"=>"您的注册码不正确！");
	}
	return $res;
}

//通过ID获取家长信息
function getGuardianById($id, $school){
	$table = $school.".ht_student";
	$sql = "select * from ".$table." where student_id='".$id."'";
	return $GLOBALS["db"]->getRow($sql);
}

//注册用户信息
function register_system($guardian,$school,$regCode,$password){
	$table = $school.".ht_student";
	$sql = "update ".$table." set license='$regCode',password='".md5($password)."', is_active=1 where student_id=".$guardian['student_id'];
	$GLOBALS["db"]->query($sql);
	
	$sql = "update hteacher.ht_license set is_active=1, regtime=now() where license='$regCode'";
	$GLOBALS["db"]->query($sql);
	
	//发送短信提醒
	require_once(ROOT_PATH . '/includes/cls_sms.php');
	$content = sms_tmp_reg_success($guardian, $password, $regCode);
	$sms = new sms();
	return $sms->send($guardian["guardian_phone"], $content, $school, $guardian["class_code"], "system");
}
?>
