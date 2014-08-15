<!-- $Id: sms_my_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<form method="POST" action="sms.php?act=register" onsubmit="return validate(this);">
<script type="text/javascript" >
	function validate(o){
		var warn = document.getElementById('warn');
		var user = document.getElementById('user');
		if(!user.value){
			user.focus();
			warn.innerHTML="账号不能为空";
			return false;
		}
		var pass = document.getElementById('pass');
		if(!pass.value){
			pass.focus();
			warn.innerHTML="密码不能为空";
			return false;
		}
		var server = document.getElementById('server');
		if(!server.value){
			server.focus();
			warn.innerHTML="服务器不能为空";
			return false;
		}
		return true;
	}
</script>
<div class="main-div">
	<table  border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td class="label">账号</td>
	    <td>
	    	<input type="text" name="user" id="user" value="<?php echo $this->_var['sms_my_info']['user']; ?>" maxlength="40" /><span style="color:red;">*</span>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">密码</td>
	    <td>
	    	<input type="password" name="pass" id="pass" value="<?php echo $this->_var['sms_my_info']['pass']; ?>" maxlength="40" /><span style="color:red;">*</span>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">服务器</td>
	    <td>
	    	<input type="text" name="server" id="server" value="<?php echo $this->_var['sms_my_info']['server']; ?>" maxlength="128" size="60"/><span style="color:red;">*</span>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">端口</td>
	    <td>
	    	<input type="text" name="port" value="<?php echo $this->_var['sms_my_info']['port']; ?>" maxlength="20" size="60"/>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">短信总数</td>
	    <td>
	    	<input type="text" name="total" value="<?php echo $this->_var['sms_my_info']['total']; ?>" maxlength="20" size="60"/>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">是否立即生效</td>
	    <td>
	    	<label><input type="radio" value="1" checked="checked" name="is_active">是</label>
	    	<label><input type="radio" value="0" name="is_active">否</label>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">测试号码</td>
	    <td>
	    	<input type="text" name="phone" value="<?php echo $this->_var['phone']; ?>" maxlength="20" size="60"/>
	    </td>
	  </tr>
	  <tr>
	  	<td>&nbsp;</td>
	    <td colspan="2" style="text-align:left;color:red;">
	    	<div id="warn" ></div>
	    </td>
	  </tr>
	  <tr>
	  	<td>&nbsp;</td>
	    <td colspan="2" style="text-align:left;">
	    	<input type="hidden" value="<?php echo $this->_var['sms_my_info']['sms_server_id']; ?>" name="sms_server_id"/>
	    	<input type="submit" value="设置" />
	    </td>
	  </tr>
	</table>
	</div>
 </form>
<?php echo $this->fetch('pagefooter.htm'); ?>