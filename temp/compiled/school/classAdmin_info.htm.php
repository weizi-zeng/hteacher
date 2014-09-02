<!-- $Id: privilege_info.htm 16616 2009-08-27 01:56:35Z liuhui $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form name="theForm" method="post" enctype="multipart/form-data" onsubmit="return validate();">
<table width="100%">
  <tr>
    <td class="label">账号：</td>
    <td>
      <input type="text" name="user_name" maxlength="20" value="<?php echo htmlspecialchars($this->_var['user']['user_name']); ?>" size="34"/><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  
   <?php if ($this->_var['form_action'] == "insert"): ?>
  <tr>
    <td class="label">密码：</td>
    <td>
      <input type="password" name="password" maxlength="32" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">确认密码：</td>
    <td>
      <input type="password" name="pwd_confirm" maxlength="32" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <?php endif; ?>
  
  <?php if ($this->_var['form_action'] == "update"): ?>
  <tr>
    <td class="label">重置密码：</td>
    <td>
      <input type="password" name="new_password" maxlength="32" size="34" /></td>
  </tr>
  <tr>
    <td class="label">确认重置密码：</td>
    <td>
      <input type="password" name="pwd_confirm" value="" size="34" /></td>
  </tr>
  <?php endif; ?>
  
  <tr>
    <td class="label">电话：</td>
    <td>
      <input type="text" name="cellphone" value="<?php echo htmlspecialchars($this->_var['user']['cellphone']); ?>" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  
  <tr>
    <td class="label">邮箱：</td>
    <td>
      <input type="text" name="email" value="<?php echo htmlspecialchars($this->_var['user']['email']); ?>" size="34" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  
    <tr>
   <td class="label">所属班级 ：</td>
    <td>
      <select name="class_code">
        <?php $_from = $this->_var['class_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
        <option value="<?php echo $this->_var['list']['code']; ?>" <?php if ($this->_var['list']['code'] == $this->_var['user']['class_code']): ?> selected="selected" <?php endif; ?> ><?php echo $this->_var['list']['name']; ?></option>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </select><?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr><td>&nbsp;</td><td><div style="color:red;text-align:left;" id="warn"></div></td></tr>
  
  <tr>
  	<td>&nbsp;</td>
    <td align="left">
      <input type="submit" value="确定" class="button" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" value="重置" class="button" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['user']['user_id']; ?>" /></td>
  </tr>
</table>
</form>
</div>
<script language="JavaScript">
var action = "<?php echo $this->_var['form_action']; ?>";
<!--

document.forms['theForm'].elements['user_name'].focus();


/**
* 检查表单输入的数据
*/
function validate()
{
	var user_name = document.forms['theForm'].elements['user_name'];
	if(!user_name.value){
		document.forms['theForm'].elements['user_name'].focus();
		document.getElementById('warn').innerHTML = "用户名不能为空！";
		return false;
	}
	
	  if (action == "insert")
	  {
	    if (document.forms['theForm'].elements['password'])
	    {
	    	var password = document.forms['theForm'].elements['password'];
	    	if(!password.value){
	    		password.focus();
	    		document.getElementById('warn').innerHTML = "密码不能为空！";
	    		return false;
	    	}
	    	
	    	var pwd_confirm = document.forms['theForm'].elements['pwd_confirm'];
	    	if(!pwd_confirm.value){
	    		pwd_confirm.focus();
	    		document.getElementById('warn').innerHTML = "确认密码不能为空！";
	    		return false;
	    	}
	    	
	    	if(password.value!=pwd_confirm.value){
	    		password.focus();
	    		document.getElementById('warn').innerHTML = "你输入的密码与确认密码不一致！";
	    		return false;
	    	}
	    }
	  }
	  else if (action == "update")
	  {
	    if (document.forms['theForm'].elements['new_password'].value.length > 0)
	    {
	    	var pwd_confirm = document.forms['theForm'].elements['pwd_confirm'];
	    	if(!pwd_confirm.value){
	    		pwd_confirm.focus();
	    		document.getElementById('warn').innerHTML = "确认重置密码不能为空！";
	    		return false;
	    	}
	    	
	    	if(new_password.value!=pwd_confirm.value){
	    		password.focus();
	    		document.getElementById('warn').innerHTML = "你输入的重置密码与确认重置密码不一致！";
	    		return false;
	    	}
	    }
	  }
	  
  	var cellphone = document.forms['theForm'].elements['cellphone'];
	if(!cellphone.value){
		cellphone.focus();
		document.getElementById('warn').innerHTML = "电话不能为空！";
		return false;
	}
	 
	var email = document.forms['theForm'].elements['email'];
	if(!email.value){
		email.focus();
		document.getElementById('warn').innerHTML = "邮箱不能为空！";
		return false;
	}
	
  return true;
}

//-->

</script>
<?php echo $this->fetch('pagefooter.htm'); ?>