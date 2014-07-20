<?php if ($this->_var['full_page']): ?>
<!-- $Id: schools_list.htm 17053 2010-03-15 06:50:26Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchschool()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    &nbsp;学校编号&nbsp;<input type="text" name="code" size="8" />
    &nbsp;学校名称&nbsp;<input type="text" name="keyword" /> <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" />
  </form>
</div>

<form method="POST" action="" name="listForm" >

<!-- start schools list -->
<div class="list-div" id="listDiv">
<?php endif; ?>
<!--用户列表部分-->
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      <a href="javascript:listTable.sort('school_id'); ">ID</a><?php echo $this->_var['sort_school_id']; ?>
    </th>
    <th><a href="javascript:listTable.sort('code'); ">编号</a><?php echo $this->_var['sort_school_code']; ?></th>
    <th>名称</th>
    <th>类型</th>
    <th>地址</th>
    <th>负责人</th>
    <th>电话</th>
    <th>邮箱</th>
    <th>传真</th>
    <th><a href="javascript:listTable.sort('is_active'); ">是否注册本系统</a><?php echo $this->_var['sort_is_active']; ?></th>
    <th><a href="javascript:listTable.sort('createtime'); ">添加时间</a><?php echo $this->_var['sort_createtime']; ?></th>
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  <tr>
  
  <?php $_from = $this->_var['school_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'school');if (count($_from)):
    foreach ($_from AS $this->_var['school']):
?>
  <tr>
    <td><?php echo $this->_var['school']['school_id']; ?></td>
    <td class="first-cell"><?php echo htmlspecialchars($this->_var['school']['code']); ?></td>
    <td><?php echo $this->_var['school']['name']; ?></td>
    <td><?php echo $this->_var['school']['type']; ?></td>
    <td><?php echo $this->_var['school']['address']; ?></td>
    <td><?php echo $this->_var['school']['header']; ?></td>
    <td><span onclick="listTable.edit(this, 'edit_mobtel', <?php echo $this->_var['school']['school_id']; ?>)"><?php echo $this->_var['school']['mobtel']; ?></span></td>
    <td><span onclick="listTable.edit(this, 'edit_email', <?php echo $this->_var['school']['school_id']; ?>)"><?php echo $this->_var['school']['email']; ?></span></td>
    <td><?php echo $this->_var['school']['fox']; ?></td>
    
    <td align="center">
    <img style="cursor:pointer;" src="images/<?php if ($this->_var['school']['is_active'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_active', <?php echo $this->_var['school']['school_id']; ?>)" />
    </td>
    
    <td align="center"><?php echo $this->_var['school']['createtime']; ?></td>
    <td align="center">
      <a href="school.php?act=edit&id=<?php echo $this->_var['school']['school_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><img src="images/icon_edit.gif" border="0" height="16" width="16" /></a>
      <a href="javascript:confirm_redirect('删除此学效将导致整个学习的所有数据不可用！确认删除吗？', 'school.php?act=remove&id=<?php echo $this->_var['school']['school_id']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>"><img src="images/icon_drop.gif" border="0" height="16" width="16" /></a>
    </td>
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
<!-- end schools list -->
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
function searchschool()
{
    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['code'] = Utils.trim(document.forms['searchForm'].elements['code'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();
}

//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>