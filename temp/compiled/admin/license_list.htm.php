<!-- $Id: admin_list.htm 14216 2008-03-10 02:27:21Z testyang $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="form-div">
  <form action="javascript:searchLicense()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    &nbsp;注册码&nbsp;<input type="text" name="keyword" /> 
     &nbsp;是否已注册使用&nbsp;
     <select name="is_active">
     	<option value="">所有...</option>
     	<option value="0">否</option>
     	<option value="1">是</option>
     </select>
      &nbsp;生效日期&nbsp;<input type="text" name="sdate" />
      &nbsp;失效日期&nbsp;<input type="text" name="edate" />
       <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" />
  </form>
</div>


<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>ID</th>
    <th>注册码</th>
    <th>生效日期</th>
    <th>失效日期</th>
    <th>是否已注册使用</th>
    <th>注册使用时间</th>
  </tr>
  <?php $_from = $this->_var['license_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td class="first-cell" ><?php echo $this->_var['list']['license_id']; ?></td>
    <td align="center"><?php echo $this->_var['list']['license']; ?></td>
    <td align="center"><?php echo $this->_var['list']['sdate']; ?></td>
    <td align="center"><?php echo $this->_var['list']['edate']; ?></td>
    <td align="center">
    <img src="images/<?php if ($this->_var['list']['is_active'] == 1): ?>yes<?php else: ?>no<?php endif; ?>.gif" />
    </td>
    <td align="center"><?php echo empty($this->_var['list']['regtime']) ? '未使用' : $this->_var['list']['regtime']; ?></td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="6"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>
      <td align="right" nowrap="true" colspan="6">
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
function searchLicense()
{
    listTable.filter['keywords'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter['is_active'] = Utils.trim(document.forms['searchForm'].elements['is_active'].value);
    listTable.filter['sdate'] = Utils.trim(document.forms['searchForm'].elements['sdate'].value);
    listTable.filter['edate'] = Utils.trim(document.forms['searchForm'].elements['edate'].value);
    
    listTable.filter['page'] = 1;
    listTable.loadList();
}

//-->
</script>


<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
