<!-- $Id: agency_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form method="post" action="grade.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label">年级编号</td>
    <td><input type="text" name="code" id="code"  maxlength="60" value="<?php echo $this->_var['grade']['code']; ?>" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
    <td class="label">年级</td>
    <td><input type="text" name="name" id="name" maxlength="60" value="<?php echo $this->_var['grade']['name']; ?>" /><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  
</table>

<table align="center">
	<tr>
		<td colspan="2" align="center"><div style="color:red" id="warn" ></div></td>
	</tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" class="button" value="<?php echo $this->_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input type="hidden" name="id" value="<?php echo $this->_var['grade']['grade_id']; ?>" />
    </td>
  </tr>
</table>
</form>
</div>

<script language="JavaScript">
<!--

/**
 * 检查表单输入的数据
 */
function validate()
{
	var code = document.getElementById("code");
	if(!code.value){
		code.focus();
		document.getElementById("warn").innerHTML = "年级编号不能为空！";
		return false;
	}
	
	var name = document.getElementById("name");
	if(!name.value){
		name.focus();
		document.getElementById("warn").innerHTML = "年级不能为空！";
		return false;
	}
	
	return true;
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>