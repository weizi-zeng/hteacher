<!-- $Id: sms_my_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
	<table  border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td class="label">短信总数：</td>
	    <td><?php echo $this->_var['statics']['total']; ?>条</td>
	  </tr>
	  <tr>
	    <td class="label">共使用：</td>
	    <td><?php echo $this->_var['statics']['used']; ?>条</td>
	  </tr>
	  
	  <tr>
	    <td class="label">短信供应商显示的短信剩余量：</td>
	    <td><?php echo $this->_var['statics']['remainder']; ?>条</td>
	  </tr>
	  
	  <?php $_from = $this->_var['statics']['school']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sch');if (count($_from)):
    foreach ($_from AS $this->_var['sch']):
?>
	  <tr>
	    <td class="label"><?php echo $this->_var['sch']['name']; ?>：</td>
	    <td>共使用了<?php echo $this->_var['sch']['num']; ?>条</td>
	  </tr>
	  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</table>
	</div>
<?php echo $this->fetch('pagefooter.htm'); ?>