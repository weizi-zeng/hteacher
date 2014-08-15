<!-- $Id: sms_my_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<form method="POST" action="sms.php?act=update_sense" >
	<?php if ($this->_var['info']): ?>
	<div style="color:green;" ><?php echo $this->_var['info']; ?></div>
	<?php endif; ?>
	<input type="submit" value="更新敏感词汇" />
	<div class="main-div">
		<textarea name="sense" rows="40" cols="200"><?php echo $this->_var['sense']; ?></textarea>
	</div>
 </form>
<?php echo $this->fetch('pagefooter.htm'); ?>