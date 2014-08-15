<!-- $Id: sms_my_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<form method="POST" action="sms.php?act=update_sensitive" >
	<input type="submit" value="更新敏感词汇" />
	<div class="main-div">
		<textarea name="sensitive" rows="40" cols="200"><?php echo $this->_var['sensitive']; ?></textarea>
	</div>
 </form>
<?php echo $this->fetch('pagefooter.htm'); ?>