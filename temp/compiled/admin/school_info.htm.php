<!-- $Id: school_info.htm 16854 2009-12-07 06:20:09Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form method="post" action="school.php" name="theForm" onsubmit="return validate()">
<table width="100%" >
  <tr>
    <td class="label">ID:</td>
    <td><?php echo $this->_var['school']['school_id']; ?></td>
  </tr>
  
  <tr>
    <td class="label">学校编号:</td>
    <td>
    <?php if ($this->_var['form_action'] == "update"): ?>
    	<?php echo $this->_var['school']['code']; ?>
    <?php else: ?>
    <input type="text" name="code" maxlength="60" value="<?php echo $this->_var['school']['code']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    <?php endif; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">学校名称:</td>
    <td>
    <input type="text" name="name" maxlength="60" value="<?php echo $this->_var['school']['name']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">学校类型:</td>
    <td>
    <input type="text" name="type" maxlength="60" value="<?php echo $this->_var['school']['type']; ?>" />
    </td>
  </tr>
  
  <tr>
    <td class="label">学校地址:</td>
    <td>
    <input type="text" name="address" maxlength="60" value="<?php echo $this->_var['school']['address']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">负责人:</td>
    <td>
    <input type="text" name="header" maxlength="60" value="<?php echo $this->_var['school']['header']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">移动电话:</td>
    <td>
    <input type="text" name="mobtel" maxlength="60" value="<?php echo $this->_var['school']['mobtel']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">邮箱:</td>
    <td><input type="text" name="email" maxlength="60" size="40" value="<?php echo $this->_var['school']['email']; ?>" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  
  <tr>
    <td class="label">固定电话:</td>
    <td>
    <input type="text" name="tel" maxlength="60" value="<?php echo $this->_var['school']['tel']; ?>" />
    </td>
  </tr>
  
   <tr>
    <td class="label">传真:</td>
    <td>
    <input type="text" name="fox" maxlength="60" value="<?php echo $this->_var['school']['fox']; ?>" />
    </td>
  </tr>
  
  <tr>
    <td class="label">校训:</td>
    <td><input type="text" name="motto" maxlength="60" size="40" value="<?php echo $this->_var['school']['motto']; ?>" /></td>
  </tr>
  
  <tr>
    <td class="label">建校时间:</td>
    <td><input type="text" name="create_date" maxlength="60" size="40" value="<?php echo $this->_var['school']['create_date']; ?>" /></td>
  </tr>
  
  <tr>
    <td class="label">校庆日:</td>
    <td><input type="text" name="dec_day" maxlength="60" size="40" value="<?php echo $this->_var['school']['dec_day']; ?>" /></td>
  </tr>
  
  <tr>
    <td class="label">法人代表（校长）:</td>
    <td><input type="text" name="ceo" maxlength="60" size="40" value="<?php echo $this->_var['school']['ceo']; ?>" /></td>
  </tr>
  
  <tr>
    <td class="label">组织代码:</td>
    <td><input type="text" name="org_code" maxlength="60" size="40" value="<?php echo $this->_var['school']['org_code']; ?>" /></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><div style="color:red" id="warn"></div></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />
      <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['school']['school_id']; ?>" />    </td>
  </tr>
</table>

</form>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,validator.js')); ?>

<script language="JavaScript">
<!--

/**
 * 检查表单输入的数据
 */
function validate()
{
	var email = $('input[name="email"]').val();
	if(!email){
		$('#warn').text("邮箱不能为空");
		$('#input[name=email]').focus();
		return false;
	}
	
	var name = $('input[name="name"]').val();
	if(!name){
		$('#warn').text("学校名称不能为空");
		$('input[name="name"]').focus();
		return false;
	}
	
	var address = $('input[name="address"]').val();
	if(!address){
		$('#warn').text("学校地址不能为空");
		$('input[name="address"]').focus();
		return false;
	}
	
	var header = $('input[name="header"]').val();
	if(!header){
		$('#warn').text("负责人不能为空");
		$('input[name="header"]').focus();
		return false;
	}
	
	var mobtel = $('input[name="mobtel"]').val();
	if(!mobtel){
		$('#warn').text("移动电话不能为空");
		$('input[name="mobtel"]').focus();
		return false;
	}
	
    if (document.forms['theForm'].elements['act'].value == "insert")
    {
    	var code = $('input[name="code"]').val();
    	if(!code){
    		$('#warn').text("学校编号不能为空");
    		$('input[name="code"]').focus();
    		return false;
    	}
    }
    $('#warn').text("");
    return true;
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
