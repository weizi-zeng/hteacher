<?php

/**
 * ECSHOP 短信模块 之 模型（类库）
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: douqinghua $
 * $Id: cls_sms.php 17155 2010-05-06 06:29:05Z douqinghua $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}
define('SOURCE_TOKEN', 'b11983d30cb6821158744d5d065d0f70');
define('SOURCE_ID', '620386');

/* 短信模块主类 */
class sms
{
	var $sms_server_id;
	var $user;		//账号
	var $pass;		//密码
	var $server;	//服务器
	var $port; 		//端口号
	
	var $total         = 0;
	var $used      = 0;
	
	var $sense = "";	//敏感词汇
    
	var $db         = null;
	var $ecs        = null;
	
	/*编码格式的转换*/
	var $scode  	= "GBK//IGNORE";
	var $dcode  	= "UTF-8";
	
	var $suf		="【我爱我班】";
	
	function __construct()
	{
		$this->sms();
	}
	
	function sms()
	{
		/* 由于要包含init.php，所以这两个对象一定是存在的，因此直接赋值 */
		$this->db = $GLOBALS['db'];
		$this->ecs = $GLOBALS['ecs'];
		
		$sql = "select * from hteacher.ht_sms_server where is_active=1 limit 1";
		$server = $this->db->getRow($sql);
		
		$this->sms_server_id = $server['sms_server_id'];
		$this->user = $server['user'];
		$this->pass = $server['pass'];
		$this->server = $server['server'];
		$this->port = $server['port'];
		$this->total = $server['total'];
		$this->used = $server['used'];
		$this->sense = explode("|", $server['sense']);/*加载敏感字符*/
		
		return $server;
	}
	
	/**
	 * 封装短信发送接口
	 * url最长不能超过2083
	 */
	function send($phones, $msg, $school_code, $class_code, $creator, $suf=''){
		$result = array('error'=>0,'msg'=>'');
		
		//检查手机号码是否正确，手机号总长度不超过1024
		if(empty($phones) || str_len($phones)==0){
			return array('error'=>1,'msg'=>'手机号码不能为空');
		}
		if(str_len($phones)>1024){
			return array('error'=>1,'msg'=>'手机号码过长，最多可同时发送给100个客户');
		}
		$phones_tmp = explode(",", $phones);
		$error_phones = array();
		foreach ($phones_tmp as $k=>$p){
			if(!is_moblie($p)){
				$error_phones[] = $p;
			}
		}
		if(count($error_phones)>0){
			$erro = implode(",", $error_phones);
			return array('error'=>1,'msg'=>'您发送的号码中有错误号码：'.$erro.'');
		}
		//对号码进行去重
		$phones = removeRepeat($phones_tmp);
		
		//检查短信内容是否包含有敏感字符，短信内容不能超过512个字符
		if(empty($msg) || str_len($msg)==0){
			return array('error'=>1,'msg'=>'短信内容不能为空');
		}
		if(str_len($msg)>512){
			return array('error'=>1,'msg'=>'短信内容不能超过512个字符');
		}
		//检查是否带了签名
		if(stripos($msg, "【")<=-1 || stripos($msg, "】")<=-1){
			if($suf){
				$this->suf = suf;
			}
			$msg.= $this->suf;
		}
		
		$error_words = array();
		foreach($this->sense as $k=>$v){
			if(stripos($msg, $v)>-1){
				$error_words[] = $v;
			}
		}
		if(count($error_words)>0){
			$erro = implode("|", $error_words);
			return array('error'=>1,'msg'=>'您发送的短信内容中存在敏感词汇：'.$erro.'');
		}
		
		/**
		 * 1、将短信插入到短信队列
		 * 2、发送短信
		 * 3、更新短信队列中的状态
		 * 4、更新短信服务器的总条数记录
		 */
		$database = $school_code?$school_code.'_school':'hteacher';
		
		$sql="insert into ".$database.".ht_sms (content,phones, status, class_code, creator, created ) 
			values ('".$msg."','".$phones."',0,'".$class_code."','".$creator."',now())";
		$this->db->query($sql);
		$sms_id = $this->db->insert_id();
		
		$info = $this->sendSMS($phones, $msg);
		if(empty($info[0]) || $info[0]==0 ){
			$sql = "update ".$database.".ht_sms set status=2 where sms_id=".$sms_id;
			$this->db->query($sql);
			return array('error'=>1,'msg'=>'短信发送失败：'.$info[1].'');
		}
		
		$sql = "update ".$database.".ht_sms set status=1, num='".$info[1]."',sended=now() where sms_id=".$sms_id;
		$this->db->query($sql);
		
		$sql = "update hteacher.ht_sms_server set used=used+".$info[1]." where sms_server_id=".$this->sms_server_id;
		$this->db->query($sql);
		
		return $result;
	}
	
	
	
	/*************************************************************基础函数 start********************************************************************************/
		
	/**
	* 发送短信
	* http://www.chinaweimei.com.cn/apihttp/SMSSend.aspx?user=testUser&pass=testPass&context=testText&mobile=15800000000
	* 获取短信余额
	* http://www.chinaweimei.com.cn/apihttp/GetBalance.aspx?user=testUser&pass=testPass
	* 获取短信关键词库
	* http://www.chinaweimei.com.cn/apihttp/GetSmsKeys.aspx
	*/
	function sendSMS($phones,$msg){
		$msg = iconv($this->dcode,$this->scode,$msg);
		$url = $this->server. "SMSSend.aspx?user=".$this->user."&pass=".$this->pass."&mobile=".$phones."&context=".$msg;
		return $this->getResult($url);
	}
	
	
	/**
	*  获取短信余额
	*  		成功返回："1|帐号剩余条"  例如："1|99999" "1|90000"
	失败返回："0|失败原因"   例如："0|帐号或密码为空" "0|帐号或密码错误，请区分大小写"
	*/
	function getBalance(){
		$url = $this->server. "GetBalance.aspx?user=".$this->user."&pass=".$this->pass;
		return $this->getResult($url);
	}
	
	
	/**
	* 获取敏感词汇
	 * 		成功返回："关键词A|关键词B|关键词C……… "
	*/
	function getSmsKeys(){
		$url = $this->server. "GetSmsKeys.aspx";
		$this->sense = $this->getResult($url);
		return $this->sense;
	}
	
	
	/**
	* 根据url获取返回值
	 */
	function getResult($url){
		$result = file_get_contents($url);
		$result = iconv($this->scode,$this->dcode,$result);
		$result = explode("|", $result);
		return $result;
	}
	
	/*************************************************************基础函数 end********************************************************************************/
	
	
}

/**
* 检测手机号码是否正确
*/
function is_moblie($moblie)
{
	return  preg_match("/^0?1((3|8)[0-9]|5[0-35-9]|4[57])\d{8}$/", $moblie);
}

function removeRepeat($ary){
	$res = array_unique($ary);
	$res = implode(",", $res);
	return $res;
}
?>