<?php if ($this->_var['full_page']): ?>
<!-- $Id: classs_list.htm 17053 2010-03-15 06:50:26Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchclass()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    &nbsp;班级编号&nbsp;<input type="text" name="code" size="8" />
    &nbsp;班级名称&nbsp;<input type="text" name="keyword" /> <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" />
  </form>
</div>

<form method="POST" action="" name="listForm" >

<!-- start classs list -->
<div class="list-div" id="listDiv">
<?php endif; ?>
<!--用户列表部分-->
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      <a href="javascript:listTable.sort('class_id'); ">ID</a><?php echo $this->_var['sort_class_id']; ?>
    </th>
    <th><a href="javascript:listTable.sort('code'); ">编号</a><?php echo $this->_var['sort_code']; ?></th>
    <th>名称</th>
    <th>所属年级</th>
    <th>班主任</th>
    <th>主要教室</th>
    <th><a href="javascript:listTable.sort('has_left'); ">是否已经毕业</a><?php echo $this->_var['sort_has_left']; ?></th>
    <th><a href="javascript:listTable.sort('is_active'); ">是否注册本系统</a><?php echo $this->_var['sort_is_active']; ?></th>
    <th><a href="javascript:listTable.sort('created'); ">添加时间</a><?php echo $this->_var['sort_created']; ?></th>
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  <tr>
  
  <?php $_from = $this->_var['class_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'class');if (count($_from)):
    foreach ($_from AS $this->_var['class']):
?>
  <tr>
    <td><?php echo $this->_var['class']['class_id']; ?></td>
    <td class="first-cell"><?php echo htmlspecialchars($this->_var['class']['code']); ?></td>
    <td><?php echo $this->_var['class']['name']; ?></td>
    <td><?php echo $this->_var['class']['gradename']; ?></td>
    <td><span onclick="listTable.edit(this, 'edit_hteacher', <?php echo $this->_var['class']['class_id']; ?>)"><?php echo $this->_var['class']['hteacher']; ?></span></td>
    <td><span onclick="listTable.edit(this, 'edit_classroom', <?php echo $this->_var['class']['class_id']; ?>)"><?php echo $this->_var['class']['classroom']; ?></span></td>
    
    <td align="center">
    <img style="cursor:pointer;" src="images/<?php if ($this->_var['class']['has_left'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_left', <?php echo $this->_var['class']['class_id']; ?>)" />
    </td>
    
    <td align="center">
    <img style="cursor:pointer;" src="images/<?php if ($this->_var['class']['is_active'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_active', <?php echo $this->_var['class']['class_id']; ?>)" />
    </td>
    
    <td align="center"><?php echo $this->_var['class']['created']; ?></td>
    
    <td align="center">
      <a href="class.php?act=edit&id=<?php echo $this->_var['class']['class_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><img src="images/icon_edit.gif" border="0" height="16" width="16" /></a>
      <a href="javascript:void(0);" onclick="listTable.remove(<?php echo $this->_var['class']['class_id']; ?>, '确认删除，如果此班级的信息被使用需要先删除关联的其他表的数据，否则将会报错？')" title="删除"><img src="images/icon_drop.gif" border="0" height="16" width="16" /></a>
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
<!-- end classs list -->
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
function searchclass()
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