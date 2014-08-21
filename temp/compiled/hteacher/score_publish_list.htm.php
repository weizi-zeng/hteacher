<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>考试成绩发布</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/score_publish.js" type="text/javascript"></script>
    

    <script type="text/javascript">
       //公共变量初始化
    </script>
</head>
<body class="easyui-layout">
    <noscript>
        <div style="position: absolute; z-index: 100000; height: 2046px; top: 0px; left: 0px;
            width: 100%; text-align: center;">
            <img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
        </div>
    </noscript>
    
     <!-- 固定写法，用于加载ajax正在处理提示 -->
    <div id="loadingDiv" style="display:none;"></div>
    
    <div data-options="region:'north'" style="height:50px;margin-bottom:5px;">
          <form id="search_form" method="post" action="analysis.php?act=score_trend" style="margin-top:10px;">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                    	
                        <td style="text-align: right; width:80px;">考试项目：</td>
                        <td style="text-align: left; width:130px;">
                        	<select id="search_prj_code" name="search_prj_code" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['prjs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'prj');if (count($_from)):
    foreach ($_from AS $this->_var['prj']):
?>
                        		<option value="<?php echo $this->_var['prj']['code']; ?>"><?php echo $this->_var['prj']['code']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: left; width:430px;">
                        	<a href="javascript:void(0)" onclick="publish();" class="easyui-linkbutton" iconCls="icon-application_view_list" >发布考试</a>
           					<a href="javascript:void(0)" onclick="sendSMS();" class="easyui-linkbutton" iconCls="icon-note_go" >短信通知</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     	
    <div data-options="region:'center'">
     	<div id="result_load" style="width:100%;height:100%;"></div>
    </div>
    
</body>
</html>
