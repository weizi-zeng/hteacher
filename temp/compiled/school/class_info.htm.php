<!-- $Id: class_info.htm 16854 2009-12-07 06:20:09Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form method="post" action="class.php" name="theForm" onsubmit="return validate()">
<table width="100%" >
  <tr>
    <td class="label">ID:</td>
    <td><?php echo $this->_var['class']['class_id']; ?></td>
  </tr>
  
  <tr>
    <td class="label">班级编号:</td>
    <td>
    <?php if ($this->_var['form_action'] == "update"): ?>
    	<?php echo $this->_var['class']['code']; ?>
    <?php else: ?>
    <input type="text" name="code" maxlength="60" value="<?php echo $this->_var['class']['code']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    <?php endif; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">班级名称:</td>
    <td>
    <input type="text" name="name" maxlength="60" value="<?php echo $this->_var['class']['name']; ?>" />
    <?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">所属年级:</td>
    <td>
    <select name="grade" >
    	<?php $_from = $this->_var['grade_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'grade');if (count($_from)):
    foreach ($_from AS $this->_var['grade']):
?>
    		<?php if ($this->_var['class']['grade'] == $this->_var['grade']['grade_id']): ?>
    		<option value="<?php echo $this->_var['grade']['grade_id']; ?>" selected="selected"><?php echo $this->_var['grade']['name']; ?></option>
    		<?php else: ?>
    		<option value="<?php echo $this->_var['grade']['grade_id']; ?>"><?php echo $this->_var['grade']['name']; ?></option>
    		<?php endif; ?>
    	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
    <?php echo $this->_var['lang']['require_field']; ?>
    </td>
  </tr>
  
  <tr>
    <td class="label">班主任:</td>
    <td>
    <input type="text" name="hteacher" maxlength="60" value="<?php echo $this->_var['class']['hteacher']; ?>" />
    </td>
  </tr>
  
  <tr>
    <td class="label">主要教室:</td>
    <td>
    <input type="text" name="classroom" maxlength="60" value="<?php echo $this->_var['class']['classroom']; ?>" />
    </td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><div style="color:red" id="warn"></div></td>
  </tr>
  
  <tr>
  	<td>&nbsp;</td>
    <td align="left">
      <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />
      <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['class']['class_id']; ?>" />    </td>
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
	if (document.forms['theForm'].elements['act'].value == "insert")
    {
    	var code = $('input[name="code"]').val();
    	if(!code){
    		$('#warn').text("班级编号不能为空");
    		$('input[name="code"]').focus();
    		return false;
    	}
    }
	
	var name = $('input[name="name"]').val();
	if(!name){
		$('#warn').text("班级名称不能为空");
		$('input[name="name"]').focus();
		return false;
	}
    
    $('#warn').text("");
    return true;
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
