<?php if ($this->_var['full_page']): ?>
<!-- $Id: smss_list.htm 17053 2010-03-15 06:50:26Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchsms()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    &nbsp;学校编号&nbsp;
    <select name="school" >
    	<option value="">超级系统管理员</option>
    	<?php $_from = $this->_var['schools']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sch');if (count($_from)):
    foreach ($_from AS $this->_var['sch']):
?>
    	<option value="<?php echo $this->_var['sch']['code']; ?>"><?php echo $this->_var['sch']['name']; ?></option>
    	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
    &nbsp;关键字&nbsp;<input type="text" name="keyword" /> <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" />
  </form>
</div>

<div style="color:red;">
	短信状态说明：0：未发送或者是正在发送；1：发送成功；2：发送失败；
</div>
<form method="POST" action="" name="listForm" >

<!-- start smss list -->
<div class="list-div" id="listDiv">
<?php endif; ?>
<!--用户列表部分-->
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      ID
    </th>
    <th>内容</th>
    <th>电话</th>
    <th>状态</th>
    <th>短信数量</th>
    <th>班级</th>
    <th>发送时间</th>
    <th>发送人</th>
    <th>创建时间</th>
  <tr>
  
  <?php $_from = $this->_var['sms_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sms');if (count($_from)):
    foreach ($_from AS $this->_var['sms']):
?>
  <tr>
    <td><?php echo $this->_var['sms']['sms_id']; ?></td>
    <td class="first-cell"><?php echo htmlspecialchars($this->_var['sms']['content']); ?></td>
    <td><?php echo $this->_var['sms']['phones']; ?></td>
    <td><?php echo $this->_var['sms']['status']; ?></td>
    <td><?php echo $this->_var['sms']['num']; ?></td>
    <td><?php echo $this->_var['sms']['class_code']; ?></td>
    
    <td><?php echo $this->_var['sms']['sended']; ?></td>
    <td><?php echo $this->_var['sms']['creator']; ?></td>
    <td><?php echo $this->_var['sms']['created']; ?></td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="13"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>
      <td align="right" nowrap="true" colspan="13">
      <?php echo $this->fetch('page.htm'); ?>
      </td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>
<!-- end smss list -->
</form>

<script type="text/javascript" language="JavaScript">
<!--
listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>



/**
 * 搜索用户
 */
function searchsms()
{
    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['school'] = Utils.trim(document.forms['searchForm'].elements['school'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();
}

//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>