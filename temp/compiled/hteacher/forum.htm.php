<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>讨论区</title>
    
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
    

    <script type="text/javascript">
    	var error = "<?php echo $this->_var['error']; ?>";
    	$(function(){
    		if(error){
    			showError(error);
    		}
    		
    	});
    	
       //公共变量初始化
       function reply(){
    	   if ($("#reply_form").form('validate')) {
				
				$("#reply_form").submit();	
			}    	   
       }
       
       
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
   	
   		<div>
   		<table style="width:100%;">
   			<tbody>
				<tr>
					<td style="width:20%;border:1px solid rgb(197, 238, 228);">
						<div class="tc" style="padding:8px;">
      						<font class="f3">作者：<?php echo htmlspecialchars($this->_var['forum_title']['creator']); ?> </font><br/>
      						<font class="f3">发表于<?php echo $this->_var['forum_title']['created']; ?></font>
      					</div>
					</td>
					<td style="text-align:left;border:1px solid rgb(197, 238, 228);">
						<div class="tc" style="text-align:left;padding:8px;border-bottom:1px dashed green;">
					      	<font class="f5 f6"><?php echo htmlspecialchars($this->_var['forum_title']['title']); ?></font>
					    </div>
					      <div style="padding-bottom: 1em;min-height:80px;">
					       <?php echo $this->_var['forum_title']['content']; ?>
					      </div>
					</td>
				</tr>
			</tbody>
   		</table>
   		</div>
   		
   		 <?php $_from = $this->_var['forum_replys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'reply');if (count($_from)):
    foreach ($_from AS $this->_var['reply']):
?>
   		 	<div id="<?php echo $this->_var['reply']['forum_id']; ?>">
	   		<table style="width:100%;">
	   			<tbody>
					<tr>
						<td style="width:20%;border:1px solid rgb(197, 238, 228);">
							<div class="tc" style="padding:8px;">
	      						<font class="f3">作者：<?php echo htmlspecialchars($this->_var['reply']['creator']); ?> </font><br/>
	      						<font class="f3">发表于<?php echo $this->_var['reply']['created']; ?></font>
	      					</div>
						</td>
						<td style="text-align:left;border:1px solid rgb(197, 238, 228);">
							<?php if ($this->_var['reply']['title']): ?>
							<div class="tc" style="text-align:left;padding:8px;border-bottom:1px dashed green;">
						      	<font class="f5 f6"><?php echo htmlspecialchars($this->_var['reply']['title']); ?></font>
						    </div>
						    <?php endif; ?>
						      <div style="padding-bottom: 1em;min-height:80px;">
						       <?php echo $this->_var['reply']['content']; ?>
						      </div>
						</td>
					</tr>
				</tbody>
	   		</table>
	   		</div>
   		 <?php endforeach; else: ?>
	  	<div>目前还没有人回复此话题</div>
	 	<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	 	
	 	<div>
	 		<form id="reply_form" action="forum.php?act=reply" method="post">
				<table style="width:100%;border:1px solid rgb(197, 238, 228);">
	   			<tbody>
					<tr>
						<td style="text-align:right;">
						      	标题：
						</td>
						<td>
							 <input type="text" id="title" name="title" style="width:350px;"/>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">
							内容：
						</td>
						<td>
						    <textarea id="content" name="content" rows="8" cols="40" class="easyui-validatebox" data-options="required:true" maxlength="1024" style="width: 350px" ></textarea>
						     <input type="hidden" value="<?php echo $this->_var['forum_title']['forum_id']; ?>" name="forum_id" />
						    <div><a href="javascript:void(0)" onclick="reply();" class="easyui-linkbutton" iconCls="icon-report_go" >回复</a></div>
						</td>
					</tr>
				</tbody>
	   		</table>	 			
	 		
	 		</form>
	 	</div>
   </div>
   
   <div class="blank"></div>
    
                
</body>
</html>
