<!-- $Id: agency_list.htm 14216 2008-03-10 02:27:21Z testyang $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<div class="list-div" id="listDiv">
<?php endif; ?>

  <table cellpadding="3" cellspacing="1">
    <tr>
      <th> ID</th>
      <th>编号</th>
      <th>年级</th>
      <th>操作</th>
    </tr>
    <?php $_from = $this->_var['grade_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'grade');if (count($_from)):
    foreach ($_from AS $this->_var['grade']):
?>
    <tr>
      <td style="text-align:center"><?php echo $this->_var['grade']['grade_id']; ?></td>
      <td style="text-align:center"><?php echo $this->_var['grade']['code']; ?></td>  
      <td style="text-align:center"><?php echo $this->_var['grade']['name']; ?></td>
      <td align="center">
        <a href="javascript:void(0);" onclick="listTable.remove(<?php echo $this->_var['grade']['grade_id']; ?>, '确认删除？')" title="删除"><img src="images/icon_drop.gif" border="0" height="16" width="16" /></a></td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="4"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </table>

<?php if ($this->_var['full_page']): ?>
</div>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>