<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: wangleisvn $
 * $Id: flashplay.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 批量生成注册码
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'create')
{
	/* 检查权限 */
	admin_priv('license_manage');
	assign_query_info();
	$smarty->assign('ur_here',      "生成注册码");
	$smarty->display('license_create.htm');
}

/*------------------------------------------------------ */
//-- 批量生成注册码
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'createLicense')
{
    /* 检查权限 */
    admin_priv('license_manage');
	
	$license = create($sdate, $edate, $sum);
	
	//记录日志
	admin_log("创建注册码", "createLicense", "sum:".$sum.",sdate:".$sdate.",edate:".$edate);
	
	//导出注册码
	license_export($license);
	
// 	$links[] = array('text' => "返回", 'href' => 'license.php?act=list');
// 	sys_msg("创建注册码成功，成功创建了".$sum."个注册码！", 0, $links);
}

/*------------------------------------------------------ */
//-- 批量导出注册码
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'exportLicense')
{
	/* 检查权限 */
	admin_priv('license_manage');

	$license = license_list_export();
	$lic = array();
	foreach($license as $l){
		$lic[] = $l["license"];
	}
	//导出注册码
	license_export($lic);
}

/*------------------------------------------------------ */
//-- 切换是否有效
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_removed')
{
	check_authz_json('license_manage');

	$id     = intval($_POST['id']);
	$val    = intval($_POST['val']);

	$db->query("update ".$ecs->table("license")." set removed='".($val?"0":"1")."' where license_id=".$id);
	clear_cache_files();

	make_json_result($val);
}

/*------------------------------------------------------ */
//-- 查看注册码
/*------------------------------------------------------ */
elseif ($_REQUEST['act']== 'list')
{
    /* 检查权限 */
    admin_priv('license_manage');
    
    $smarty->assign('ur_here',      "注册码列表");
    $smarty->assign('action_link',  array('text' => "生成注册码", 'href'=>'license.php?act=create'));
    
    $license_list = license_list();
    
    $smarty->assign('license_list',       $license_list['license_list']);
    $smarty->assign('filter',       $license_list['filter']);
    $smarty->assign('record_count', $license_list['record_count']);
    $smarty->assign('page_count',   $license_list['page_count']);
    $smarty->assign('full_page',    1);
    
    assign_query_info();
    $smarty->display('license_list.htm');
}

/*------------------------------------------------------ */
//-- ajax返回注册码列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
	 $license_list = license_list();

	$smarty->assign('license_list',       $license_list['license_list']);
    $smarty->assign('filter',       $license_list['filter']);
    $smarty->assign('record_count', $license_list['record_count']);
    $smarty->assign('page_count',   $license_list['page_count']);

	make_json_result($smarty->fetch('license_list.htm'), '', array('filter' => $license_list['filter'], 'page_count' => $license_list['page_count']));
}

/*------------------------------------------------------ */
//-- 导出注册码
/*------------------------------------------------------ */
elseif($_REQUEST['act'] == 'export')
{
	/* 检查权限 */
	admin_priv('license_manage');

	include_once('includes/cls_phpzip.php');
	$zip = new PHPZip;

	$where = get_export_where_sql($_POST);

	$sql = "SELECT g.*, b.brand_name as brandname " .
           " FROM " . $ecs->table('goods') . " AS g LEFT JOIN " . $ecs->table('brand') . " AS b " .
           "ON g.brand_id = b.brand_id" . $where;

	$res = $db->query($sql);

	/* csv文件数组 */
	$goods_value = array();
	$goods_value['goods_name'] = '""';
	$goods_value['goods_sn'] = '""';
	$goods_value['brand_name'] = '""';
	$goods_value['market_price'] = 0;
	$goods_value['shop_price'] = 0;
	$goods_value['integral'] = 0;
	$goods_value['original_img'] = '""';
	$goods_value['goods_img'] = '""';
	$goods_value['goods_thumb'] = '""';
	$goods_value['keywords'] = '""';
	$goods_value['goods_brief'] = '""';
	$goods_value['goods_desc'] = '""';
	$goods_value['goods_weight'] = 0;
	$goods_value['goods_number'] = 0;
	$goods_value['warn_number'] = 0;
	$goods_value['is_best'] = 0;
	$goods_value['is_new'] = 0;
	$goods_value['is_hot'] = 0;
	$goods_value['is_on_sale'] = 1;
	$goods_value['is_alone_sale'] = 1;
	$goods_value['is_real'] = 1;
	$content = '"' . implode('","', $_LANG['ecshop']) . "\"\n";

	while ($row = $db->fetchRow($res))
	{
		$goods_value['is_on_sale'] = $row['is_on_sale'];
		$goods_value['is_alone_sale'] = $row['is_alone_sale'];
		$goods_value['is_real'] = $row['is_real'];

		$content .= implode(",", $goods_value) . "\n";

	}
	$charset = empty($_POST['charset']) ? 'UTF8' : trim($_POST['charset']);

	$zip->add_file(ecs_iconv(EC_CHARSET, $charset, $content), 'goods_list.csv');

	header("Content-Disposition: attachment; filename=goods_list.zip");
	header("Content-Type: application/unknown");
	die($zip->file());
}

/*------------------------------------------------------ */
//-- 证书上传
/*------------------------------------------------------ */

elseif ($_REQUEST['act']== 'upload')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    /* 接收上传文件 */
    /* 取出证书内容 */
    $license_arr = array();
    if (isset($_FILES['license']['error']) && $_FILES['license']['error'] == 0 && preg_match('/CER$/i' ,$_FILES['license']['name']))
    {
        if (file_exists($_FILES['license']['tmp_name']) && is_readable($_FILES['license']['tmp_name']))
        {
            if ($license_f = fopen($_FILES['license']['tmp_name'], 'r'))
            {
                $license_content = '';
                while (!feof($license_f))
                {
                    $license_content .= fgets($license_f, 4096);
                }
                $license_content = trim($license_content);
                $license_content = addslashes_deep($license_content);
                $license_arr = explode('|', $license_content);
            }
        }
    }

    /* 恢复证书 */
    if (count($license_arr) != 2 || $license_arr[0] == '' || $license_arr[1] == '')
    {
        $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
        sys_msg($_LANG['fail_license'], 1, $links);
    }
    else
    {
        include_once(ROOT_PATH . 'includes/cls_transport.php');
        include_once(ROOT_PATH . 'includes/cls_json.php');
        include_once(ROOT_PATH . 'includes/lib_main.php');
        include_once(ROOT_PATH . 'includes/lib_license.php');

        // 证书登录
        $login_result = license_login();
        if ($login_result['flag'] != 'login_succ')
        {
            $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
            sys_msg($_LANG['fail_license_login'], 1, $links);
        }

        $sql = "UPDATE " . $ecs->table('shop_config') . "
                SET value = '" . $license_arr[0] . "'
                WHERE code = 'certificate_id'";
        $db->query($sql);
        $sql = "UPDATE " . $ecs->table('shop_config') . "
                SET value = '" . $license_arr[1] . "'
                WHERE code = 'token'";
        $db->query($sql);

        $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
        sys_msg($_LANG['recover_license'], 0, $links);
    }

}



/**
 * 生成注册码
 * @param unknown_type $sdate
 * @param unknown_type $edate
 * @param unknown_type $sum
 */
function create($sdate, $edate, $sum=500){
	if($sum<1) return array();
	
	$max_id = $GLOBALS['db']->getOne("select max(license_id) from ".$GLOBALS['ecs']->table('license'));
	$license = createLicense($max_id, $sum);
	
	$sql = "insert into ".$GLOBALS['ecs']->table('license')." (license, sdate, edate) values ";
	
	foreach($license as $k=>$v){
		$sql .= "('".$v."','".$sdate."','".$edate."'),";
	}
	
	$sql = sub_str($sql, str_len($sql)-1, false);
	
	$GLOBALS['db']->query($sql);
	
	return $license;
}


function createLicense($max_id, $sum){
	$yard = yard();
	
	$license = array();
	
	for($i=0;$i<$sum;$i++){
		$time = time()+(++$max_id);		//时间戳加上当前最大的id号表示唯一
		$li = dechex($time);			//将唯一编号转换为十六进制
		
		$total = 0;
		for($j=0;$j<str_len($li);$j++){
			$res = ord($li[$j]);		//计算这些十六进制asic码总和
			$total += $res;
		}
		$total = $total%36;				//将total模上36，获取最后一位校验码 
		$y = $yard[$total];
		
		$li = $li ."". $y;
		$license[] = $li;
		
// 		echo $li.'<br><br>';
	}
	
	return $license;
}


/**
 * 生成最后一位校验码
 * Enter description here ...
 * @return multitype:number string
 */
function yard(){
	$yard = array();
	$i=0;
	for(;$i<10;$i++){
		$yard[$i] = $i;
	}
	
	for($j='a';$j<='z';$j++){
		$yard[$i++] = $j;
	}
	
	return $yard;
}



/**
 *  返回学校管理员列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function license_list()
{
	$result = get_filter();
	if ($result === false)
	{
		
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//注册码
		$filter['is_active'] = $_REQUEST['is_active']==="" ? -1 : intval($_REQUEST['is_active']);//注册码
		$filter['sdate'] = empty($_REQUEST['sdate']) ? '' : trim($_REQUEST['sdate']);//注册码
		$filter['edate'] = empty($_REQUEST['edate']) ? '' : trim($_REQUEST['edate']);//注册码
		
		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'license_id' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);

		$ex_where = ' WHERE 1  ';
		if ($filter['keywords'])
		{
			$ex_where .= " AND license LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['is_active']>-1)
		{
			$ex_where .= " AND is_active = '" . $filter['is_active'] ."'";
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND sdate = '" . $filter['sdate'] ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND edate = '" . $filter['edate'] ."'";
		}

		$filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('license') . $ex_where);

		/* 分页大小 */
		$filter = page_and_size($filter);
		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table('license') . $ex_where .
                " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

// 		echo $sql ; 
		
		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}

	$list = $GLOBALS['db']->getAll($sql);

	$arr = array('license_list' => $list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

	return $arr;
}




/**
 * @access  public
 * @param
 *
 * @return void
 */
function license_list_export()
{
		/* 过滤条件 */
		$filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);//注册码
		$filter['is_active'] = $_REQUEST['is_active']==="" ? -1 : intval($_REQUEST['is_active']);//注册码
		$filter['sdate'] = empty($_REQUEST['sdate']) ? '' : trim($_REQUEST['sdate']);//注册码
		$filter['edate'] = empty($_REQUEST['edate']) ? '' : trim($_REQUEST['edate']);//注册码

		if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
		{
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$ex_where = ' WHERE 1  ';
		if ($filter['keywords'])
		{
			$ex_where .= " AND license LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
		}
		if ($filter['is_active']>-1)
		{
			$ex_where .= " AND is_active = '" . $filter['is_active'] ."'";
		}
		if ($filter['sdate'])
		{
			$ex_where .= " AND sdate = '" . $filter['sdate'] ."'";
		}
		if ($filter['edate'])
		{
			$ex_where .= " AND edate = '" . $filter['edate'] ."'";
		}

		$sql = "SELECT * ".
                " FROM " . $GLOBALS['ecs']->table('license') . $ex_where .
                " ORDER by license_id ";

	$list = $GLOBALS['db']->getAll($sql);
	return $list;
}



function license_export($license){
	
	$content = "注册码\n";
	
	foreach ($license as $k=>$v)
	{
		$content .= $v . "\n";
	}
	
	$charset = empty($_POST['charset']) ? 'GBK' : trim($_POST['charset']);//UTF8
	
	$file = ecs_iconv(EC_CHARSET, $charset, $content);
	
	header("Content-Disposition: attachment; filename=license_list.csv");
	header("Content-Type: application/unknown");
	die($file);
}

?>