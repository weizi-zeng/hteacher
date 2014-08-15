<!-- $Id: problems_info.htm 16780 2009-11-09 09:28:30Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,selectzone.js,validator.js')); ?>
<!-- start goods form -->
<div class="tab-div">
    <form  action="problems.php" method="post" enctype="multipart/form-data" name="theForm" onsubmit="return validate();">
    <table width="90%" id="general-table">
      <tr>
        <td class="narrow-label">标题</td>
        <td><input type="text" name="title" id="title" size ="40" maxlength="60" value="<?php echo htmlspecialchars($this->_var['problems']['title']); ?>" /><?php echo $this->_var['lang']['require_field']; ?></td>
      </tr>
      <tr><td colspan="2" ><?php echo $this->_var['FCKeditor']; ?></td></tr>
    </table>
    <div class="button-div">
      <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
      <input type="hidden" name="old_title" value="<?php echo $this->_var['problems']['title']; ?>"/>
      <input type="hidden" name="id" value="<?php echo $this->_var['problems']['problems_id']; ?>" />
      <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button"  />
      <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button" />
    </div>
    </form>
</div>
<!-- end goods form -->
<script language="JavaScript">

var problemsId = <?php echo empty($this->_var['problems']['problems_id']) ? '0' : $this->_var['problems']['problems_id']; ?>;
var elements  = document.forms['theForm'].elements;

function validate()
{
  var title = document.getElementById("title");
  if(!title.value){
	  alert("请输入标题和内容！");
	  return false;
  }
  return true;
}

</script>
<?php echo $this->fetch('pagefooter.htm'); ?>