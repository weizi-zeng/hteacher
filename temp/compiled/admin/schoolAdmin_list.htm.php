<!-- $Id: admin_list.htm 14216 2008-03-10 02:27:21Z testyang $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchSchoolAdmin()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    
    &nbsp;所属学校&nbsp;
    <select name="school_code" >
    	<option value="" >所有...</option>
    	<?php $_from = $this->_var['school_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
    	<option value="<?php echo $this->_var['list']['code']; ?>" ><?php echo $this->_var['list']['name']; ?></option>
    	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
    &nbsp;学校管理员名称&nbsp;<input type="text" name="keyword" /> <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" />
  </form>
</div>


<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>账户</th>
    <th>电话</th>
    <th>邮箱</th>
    <th>所属学校</th>
    <th>添加时间</th>
    <th>最后登录时间</th>
    <th>是否有效</th>
    <th>操作</th>
  </tr>
  <?php $_from = $this->_var['schoolAdmin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td class="first-cell" ><?php echo $this->_var['list']['user_name']; ?></td>
    <td><span onclick="listTable.edit(this, 'edit_cellphone', <?php echo $this->_var['list']['user_id']; ?>)"><?php echo $this->_var['list']['cellphone']; ?></span></td>
    <td><span onclick="listTable.edit(this, 'edit_email', <?php echo $this->_var['list']['user_id']; ?>)"><?php echo $this->_var['list']['email']; ?></span></td>
    <td align="left"><?php echo $this->_var['list']['school']; ?></td>
    <td align="center"><?php echo $this->_var['list']['add_time']; ?></td>
    <td align="center"><?php echo empty($this->_var['list']['last_login']) ? 'N/A' : $this->_var['list']['last_login']; ?></td>
    
    <td align="center">
    <img style="cursor:pointer;" src="images/<?php if ($this->_var['list']['is_active'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_active', <?php echo $this->_var['list']['user_id']; ?>)" />
    </td>
    
    <td align="center">
   <!--   <a href="schoolAdmin.php?act=allot&id=<?php echo $this->_var['list']['user_id']; ?>&user=<?php echo $this->_var['list']['user_name']; ?>" title="<?php echo $this->_var['lang']['allot_priv']; ?>"><img src="images/icon_priv.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;
      <a href="admin_logs.php?act=list&id=<?php echo $this->_var['list']['user_id']; ?>" title="<?php echo $this->_var['lang']['view_log']; ?>"><img src="images/icon_view.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp; --> 
      <a href="schoolAdmin.php?act=edit&id=<?php echo $this->_var['list']['user_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><img src="images/icon_edit.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;
      <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['user_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>"><img src="images/icon_drop.gif" border="0" height="16" width="16"></a></td>
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
function searchSchoolAdmin()
{
    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['school_code'] = Utils.trim(document.forms['searchForm'].elements['school_code'].value);
    listTable.filter['page'] = 1;
    listTable.loadList();
}

//-->
</script>


<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
