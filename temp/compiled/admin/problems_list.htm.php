<!-- $Id: problems_list.htm 16783 2009-11-09 09:59:06Z liuhui $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchproblems()" name="searchForm" >
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    	标题 <input type="text" name="keyword" id="keyword" />
    <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<!-- start cat list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>
      <a href="javascript:listTable.sort('problems_id'); ">ID</a><?php echo $this->_var['sort_problems_id']; ?></th>
    <th><a href="javascript:listTable.sort('title'); ">标题</a><?php echo $this->_var['sort_title']; ?></th>
    <th><a href="javascript:listTable.sort('is_active'); ">是否有效</a><?php echo $this->_var['sort_is_active']; ?></th>
    <th><a href="javascript:listTable.sort('creator'); ">添加人</a><?php echo $this->_var['sort_creator']; ?></th>
    <th><a href="javascript:listTable.sort('created'); ">添加时间</a><?php echo $this->_var['sort_add_time']; ?></th>
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  </tr>
  
  <?php $_from = $this->_var['problems_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td><span><?php echo $this->_var['list']['problems_id']; ?></span></td>
    <td class="first-cell">
    	<span onclick="javascript:listTable.edit(this, 'edit_title', <?php echo $this->_var['list']['problems_id']; ?>)"><?php echo htmlspecialchars($this->_var['list']['title']); ?></span>
    </td>
    <td align="center">
    	<span><img src="images/<?php if ($this->_var['list']['is_active'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_show', <?php echo $this->_var['list']['problems_id']; ?>)" /></span>
    </td>
    <td align="center"><span><?php echo $this->_var['list']['author']; ?></span></td>
    <td align="center"><span><?php echo $this->_var['list']['created']; ?></span></td>
    
    <td align="center" nowrap="true"><span>
      <a href="../problems.php?act=view&problems_id=<?php echo $this->_var['list']['problems_id']; ?>" target="_blank" title="<?php echo $this->_var['lang']['view']; ?>"><img src="images/icon_view.gif" border="0" height="16" width="16" /></a>&nbsp;
      <a href="problems.php?act=edit&id=<?php echo $this->_var['list']['problems_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><img src="images/icon_edit.gif" border="0" height="16" width="16" /></a>&nbsp;
      <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['problems_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>"><img src="images/icon_drop.gif" border="0" height="16" width="16"></a></span>
    </td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10">当前还没有数据</td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>&nbsp;
    <td align="right" nowrap="true" colspan="8"><?php echo $this->fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>

<!-- end cat list -->
<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
  listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  


 /* 搜索文章 */
 function searchproblems()
 {
    listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }

 
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
