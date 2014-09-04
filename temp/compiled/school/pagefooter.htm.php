<div id="footer">
<?php echo $this->_var['query_info']; ?><?php echo $this->_var['gzip_enabled']; ?><?php echo $this->_var['memory_info']; ?><br />
<?php echo $this->_var['lang']['copyright']; ?>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js')); ?>

</body>
</html>