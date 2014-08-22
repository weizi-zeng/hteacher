<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $this->_var['status']; ?>后台管理中心</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>

    <link href="css/index.css" rel="stylesheet" type="text/css" />
    <script src="js/index.js" type="text/javascript"></script>
    <script src="js/nav.js" type="text/javascript"></script>

	<script type="Text/Javascript" language="JavaScript">
		<!--
		
		if (window.top != window)
		{
		  window.top.location.href = document.location.href;
		}
		
		//-->
	</script>
</head>
<body class="easyui-layout" id="fullScreen">
    <noscript>
        <div style="position: absolute; z-index: 100000; height: 2046px; top: 0px; left: 0px; width: 100%; background: white; text-align: center;">
            <img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
        </div>
    </noscript>

     <!-- 固定写法，用于加载ajax正在处理提示 -->
    <div id="loadingDiv" style="display:none;"></div>
    
    <div region="north" split="true" class="winHeader">
         <div class="userOpera">
            <a href="javascript:void(0);" id="editpass">修改密码</a>|
            <a href="javascript:void(0);" onclick="logOut()">安全退出</a>
        </div>

        <div class="userInfo">
       		欢迎您——<?php echo $this->_var['admin']['school']; ?>,<?php echo $this->_var['admin']['class']; ?>,监护人<?php echo $this->_var['admin']['admin_name']; ?>！
        </div>
    </div>


    <div region="west" title="导航菜单"  split="true" title="west" style="width:180px;" id="west">
        <div id="nav" class="easyui-accordion" data-options="fit:true,border:true">
        	
        	<?php $_from = $this->_var['menus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu');if (count($_from)):
    foreach ($_from AS $this->_var['menu']):
?>
			<div title="<?php echo $this->_var['menu']['title']; ?>">
			
				<ul class="navlist">
				<?php $_from = $this->_var['menu']['submenus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sm');if (count($_from)):
    foreach ($_from AS $this->_var['sm']):
?>
				<li><div><a ref="<?php echo $this->_var['sm']['id']; ?>" href="#" rel="<?php echo $this->_var['sm']['url']; ?>" ><span class="icon-nav icon"></span><?php echo $this->_var['sm']['title']; ?></a></div></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				
			</div>
        	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        	
        </div>
    </div>


    <div region="south" class="winFooter" split="true" style="height: 26px;">
        版权所有@ 2014 长沙开拓者
    </div>


 
    <div id="center" region="center" style="background: #eee; overflow-y: hidden">
        <div id="tabs" class="easyui-tabs" fit="true" border="false">

            <div title="首页" iconCls="icon-code" style="overflow: hidden; color:Black; display: block;">
                
                <div class="easyui-layout" data-options="fit:true,border:false">
				    <div data-options="region:'center',border:false" style="width:600px;padding-left:20px;padding-top:20px;">
				    	<div id="home-notice" class="easyui-panel" title="通知通告" data-options="collapsible:true" style="width:540px;">
		                    <ul>
		                    <?php $_from = $this->_var['notices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'notice');if (count($_from)):
    foreach ($_from AS $this->_var['notice']):
?>
		                    	<li><a href="notice.php?act=view&notice_id=<?php echo $this->_var['notice']['notice_id']; ?>" target="blank_"><?php echo $this->_var['notice']['title']; ?>——<?php echo $this->_var['notice']['created']; ?></a></li>
		                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		                    </ul>
		                </div>
		                <br/>
		                <div id="home-notice" class="easyui-panel" title="短信消息" data-options="collapsible:true" style="width:540px;">
		                    <ul>
		                         <?php $_from = $this->_var['sms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sms_item');if (count($_from)):
    foreach ($_from AS $this->_var['sms_item']):
?>
		                    	<li><p><?php echo $this->_var['sms_item']['content']; ?>——<?php echo $this->_var['sms_item']['created']; ?></p></li>
		                   		 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		                    </ul>
		                </div>
				    </div>
				    <div data-options="region:'west',border:false" style="width:600px;padding-left:20px;padding-top:20px;">
				    	<div id="home-notice" class="easyui-panel" title="讨论区" data-options="collapsible:true" style="width:540px;">
		                    <ul>
		                           <?php $_from = $this->_var['forums']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'forum');if (count($_from)):
    foreach ($_from AS $this->_var['forum']):
?>
		                    	<li><a href="forum.php?act=view&forum_id=<?php echo $this->_var['forum']['forum_id']; ?>" target="blank_"><?php echo $this->_var['forum']['title']; ?>——<?php echo $this->_var['forum']['created']; ?></a></li>
		                   		 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		                    </ul>
		                </div>
		                <br/>
		                <div id="home-notice" class="easyui-panel" title="意见箱" data-options="collapsible:true" style="width:540px;">
		                    <ul>
		                          <?php $_from = $this->_var['msg_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'msg_item');if (count($_from)):
    foreach ($_from AS $this->_var['msg_item']):
?>
		                    	<li><p><?php echo $this->_var['msg_item']['msg_title']; ?>——<?php echo $this->_var['msg_item']['msg_time']; ?></p>
		                    		<p><?php echo $this->_var['msg_item']['msg_reply']; ?></p>
		                    	</li>
		                   		 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		                    </ul>
		                </div>
				    </div>
				</div>
            </div>

        </div>
    </div>
    
    <div id="win_changepwd" class="easyui-window" closed="true" title="修改密码" icon="icon-save" style="width: 350px; height: 220px; padding: 5px; background: #fafafa;">
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" style="padding: 10px; background: #fff; border: 1px solid #ccc;">
                <form id="win_changepwd_form" name="win_changepwd_form" method="post">
                <table cellpadding="3" align="center">
                    <tr>
                        <td>
                            旧密码：
                        </td>
                        <td>
                            <input id="OldPassword" name="OldPassword" type="password" class="easyui-validatebox" required="true" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            新密码：
                        </td>
                        <td>
                            <input id="NewPassword" name="NewPassword" type="password" class="easyui-validatebox" required="true" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            确认密码：
                        </td>
                        <td>
                        	<input type="hidden" name="student_id" value="<?php echo $this->_var['student_id']; ?>"/>
                            <input id="NewPasswordRe" name="NewPasswordRe" type="password" class="easyui-validatebox" required="true" validtype="equalTo['#NewPassword']" />
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            <div region="south" border="false" style="text-align: center; height: 40px; line-height: 38px;">
                
                <a id="btnEp" class="easyui-linkbutton" icon="icon-ok" href="javascript:void(0)">确定</a> <a id="btnCancel" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)">关闭</a>
            </div>
        </div>
    </div>
    
    <div id="mm" class="easyui-menu" style="width: 150px;" title="多标签右键菜单">
        <div id="mm-tabupdate">
            刷新</div>
        <div id="mm-tabopen">
            独立打开</div>
        <div class="menu-sep">
        </div>
        <div id="mm-tabclose">
            关闭</div>
        <div id="mm-tabcloseall">
            全部关闭</div>
        <div id="mm-tabcloseother">
            除此之外全部关闭</div>
        <div class="menu-sep">
        </div>
        <div id="mm-tabcloseright">
            当前页右侧全部关闭</div>
        <div id="mm-tabcloseleft">
            当前页左侧全部关闭</div>
    </div>
</body>
</html>
