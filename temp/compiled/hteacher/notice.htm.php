<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>通知通告</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />
	<link href="css/notice.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/notice.js" type="text/javascript"></script>
    

    <script type="text/javascript">
    	var error = "<?php echo $this->_var['error']; ?>";
    	$(function(){
    		if(error){
    			showError(error);
    		}
    		
    	});
    	
    	function goback(){
    		
    	}
       //公共变量初始化
    </script>
</head>
<body class="easyui-layout">
    <noscript>
        <div style="position: absolute; z-index: 100000; height: 2046px; top: 0px; left: 0px;
            width: 100%; text-align: center;">
            <img src="images/noscript.gif" alt='抱歉，请开启脚本支持！' />
        </div>
    </noscript>
    
    
     <!-- 固定写法，用于加载ajax正在处理提示 -->
    <div id="loadingDiv" style="display:none;"></div>
    
   <div style="border:4px solid #fcf8f7; background-color:#fff; padding:20px 15px;max-width:1200px;">
      <div class="tc" style="padding:8px;">
      	<font class="f5 f6"><?php echo htmlspecialchars($this->_var['notice']['title']); ?></font><br /><font class="f3"><?php echo htmlspecialchars($this->_var['notice']['author']); ?> / <?php echo $this->_var['notice']['created']; ?></font>
      </div>
      <!-- <?php if ($this->_var['notice']['content']): ?> -->
       <?php echo $this->_var['notice']['content']; ?>
      <!-- <?php endif; ?> -->
      
      <div style="margin-top:20px;"></div>
      <?php if ($this->_var['attachs']): ?>
      <div style=" float: left;"><span>附　件</span></div>
      <div style="clear:both;"></div>
       <?php $_from = $this->_var['attachs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'at');if (count($_from)):
    foreach ($_from AS $this->_var['at']):
?>
       <div><a href="download.php?path=<?php echo $this->_var['at']['path']; ?>" target="_blank"><?php echo $this->_var['at']['name']; ?>&nbsp;&nbsp;(<?php echo $this->_var['at']['size']; ?>kb)</a></div>
       <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
       <?php endif; ?>
       
      <div style="padding:8px; margin-top:15px; text-align:left; border-top:1px solid #ccc;">
      <?php if ($this->_var['show_back'] == 1): ?>
      	<a href="javascript:void(0)" onclick="history.go(-1);" class="easyui-linkbutton" iconCls="icon-back" >返回</a>
      <?php endif; ?>
      <!-- 上一篇文章 -->
      <?php if ($this->_var['prev_notice']): ?>
         	上一篇:<a href="notice.php?act=view&notice_id=<?php echo $this->_var['prev_notice']['notice_id']; ?>" class="f6"><?php echo $this->_var['prev_notice']['title']; ?></a><br />
       <?php endif; ?>
        <!-- 下一篇文章 -->
       <?php if ($this->_var['next_notice']): ?>
         	下一篇:<a href="notice.php?act=view&notice_id=<?php echo $this->_var['next_notice']['notice_id']; ?>" class="f6"><?php echo $this->_var['next_notice']['title']; ?></a>
       <?php endif; ?>
      </div>
   </div>
  <div class="blank"></div>
  <!-- #BeginLibraryItem "/library/comments.lbi" -->
<!-- #EndLibraryItem -->
    
                
</body>
</html>
