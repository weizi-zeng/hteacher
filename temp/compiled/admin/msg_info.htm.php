<!-- $Id: msg_info.htm 16854 2009-12-07 06:20:09Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
  <table width="98%">
    <tr>
      <td style="padding: 0px 20px">
        <h3><?php echo htmlspecialchars($this->_var['msg']['msg_title']); ?></h3>
        <hr size="1" />
        <div><?php echo nl2br(htmlspecialchars($this->_var['msg']['msg_content'])); ?></div>
        <?php if ($this->_var['msg']['message_img']): ?>
        <div align="center">
          <a href="../data/feedbackimg/<?php echo $this->_var['msg']['message_img']; ?>" target="_bank" ><img src="../data/feedbackimg/<?php echo $this->_var['msg']['message_img']; ?>" width="90" height="120"></a>
          <a href="user_msg.php?act=drop_file&id=<?php echo $this->_var['msg']['msg_id']; ?>&file=<?php echo $this->_var['msg']['message_img']; ?>"><?php echo $this->_var['lang']['drop']; ?></a>
        </div>
        <?php endif; ?>
        <div align="right"  nowrap="nowrap">【 <?php if ($this->_var['msg']['msg_area'] == "1"): ?><?php echo $this->_var['lang']['message_board']; ?><?php else: ?><?php echo $this->_var['lang']['user_center']; ?><?php endif; ?> 】<a href="mailto:<?php echo $this->_var['msg']['user_email']; ?>"><?php echo $this->_var['msg']['user_name']; ?></a> @ <?php echo $this->_var['msg']['msg_time']; ?></div>
      </td>
    </tr>
<?php if ($this->_var['msg']['msg_area'] == "1"): ?>
    <tr>
      <td align="center">
        <?php if ($this->_var['msg']['msg_status'] == "0"): ?>
        <input type="button" onclick="location.href='user_msg.php?act=check&check=allow&id=<?php echo $this->_var['msg']['msg_id']; ?>'" value="<?php echo $this->_var['lang']['allow']; ?>" class="button" />
        <?php else: ?>
        <input type="button" onclick="location.href='user_msg.php?act=check&check=forbid&id=<?php echo $this->_var['msg']['msg_id']; ?>'" value="<?php echo $this->_var['lang']['forbid']; ?>" class="button" />
        <?php endif; ?>
    </td>
    </tr>
<?php endif; ?>
  </table>
</div>

<?php if ($this->_var['msg']['reply_id']): ?>
<div class="main-div">
  <table width="98%">
    <tr>
      <td style="padding: 0px 20px">
        <h3><?php echo $this->_var['msg']['reply_name']; ?> <?php echo $this->_var['lang']['from']; ?> <?php echo $this->_var['msg']['reply_time']; ?> <?php echo $this->_var['lang']['reply']; ?>:</h3>
        <hr size="1" />
        <div><?php echo nl2br(htmlspecialchars($this->_var['msg']['reply_content'])); ?></div>
      </td>
    </tr>
  </table>
</div>
<?php endif; ?>

<?php if ($this->_var['send_fail']): ?>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
<li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;" ><?php echo $this->_var['lang']['mail_send_fail']; ?></li>
</ul>
<?php endif; ?>

<div class="main-div">
<form method="post" action="user_msg.php?act=action" name="theForm"  onsubmit="return validate()">
<table border="0" width="98%">
  <tr>
    <td><?php echo $this->_var['lang']['email']; ?>:</td>
    <td><input name="user_email" id="user_email"  type="text" value="<?php echo $this->_var['msg']['reply_email']; ?>"></td>
  </tr>
  <tr>
    <td><?php echo $this->_var['lang']['reply_content']; ?>:</td>
    <td rowspan="2"><textarea name="msg_content" cols="50" rows="4" wrap="VIRTUAL" id="msg_content"><?php echo $this->_var['msg']['reply_content']; ?></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td><input name="send_email_notice" type="checkbox" value='1'/><?php echo $this->_var['lang']['send_email_notice']; ?></td>
  </tr>
  <?php if ($this->_var['msg']['reply_id']): ?>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo $this->_var['lang']['have_reply_content']; ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="hidden" name="msg_id" value="<?php echo $this->_var['msg']['msg_id']; ?>">      
      <input type="hidden" name="parent_id" value="<?php echo $this->_var['msg']['reply_id']; ?>">
      <input name="Submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" type="submit" class="button">
      <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button">
      <?php if ($this->_var['msg']['reply_id']): ?><input type="submit" name="remail" value="<?php echo $this->_var['lang']['remail']; ?>" class="button"><?php endif; ?>
    </td>
  </tr>
</table>
</form>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,validator.js')); ?>
<script language="JavaScript">
<!--

document.forms['theForm'].elements['msg_content'].focus();

/**
 * 检查表单输入的数据
 */
function validate()
{
    validator = new Validator("theForm");
    validator.required("msg_content",  no_reply_content);
    return validator.passed();
}

//-->

</script>
<?php echo $this->fetch('pagefooter.htm'); ?>