<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>通知通告</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

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
    
    <div title="数据列表" region="center" style="height:300px;" border="false">
    	<table id="dgData" style="height:400px"></table>
    
     	<div id="toolbar" style="padding:5px;height:auto">
     	 <div style="margin-bottom:5px">
            <a href="javascript:void(0)" onclick="checknotices();" class="easyui-linkbutton" iconCls="icon-view" >查看</a>
         </div>
    	 <div style="height:40px;margin-bottom:5px;" >
          <form id="search_form" method="post">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                   		<td style="text-align: right; width:80px;">关键字：</td>
                        <td style="text-align: left; width:150px;">
                        	<input type="text" id="search_keywords" name="search_keywords" style="width: 170px;" />
                        </td>
                        
                        <td style="text-align: right; width:80px;">发布日期：</td>
                        <td style="text-align: left; width:150px;">
                        	<input id="search_created" name="search_created" class="easyui-datebox" data-options="required:false"  maxlength="20" style="width: 170px" />
                        </td>
                        
                        <td style="text-align: left; width:150px;">
                        	<a href="javascript:void(0)" onclick="searchnotices();" class="easyui-linkbutton" icon="icon-search">查找</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     </div>
        
    </div>
    
    
    <div id="edit_window" class="easyui-window" closed="true" title="数据维护" style="width: 740px;
        height: 500px; padding: 0px;" >
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" fit="true" style="padding: 0px; background: #fff; border: 0px;">
                <form id="edit_form" name="edit_form" action="notice.php?act=ajax_save" method="post" enctype="multipart/form-data"  style="padding-top:20px;" onsubmit="return savecheck(this);">
                
                <div title="隐藏参数">
                    <input type="hidden" id="notice_id" name="notice_id" value="0" />
                </div>
                
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                	<tr>
                        <th style="text-align: right">标题：</th>
                        <td style="width:70%">
                        	<input id="title" name="title" class="easyui-validatebox" data-options="required:true"  maxlength="255" style="width: 400px" />
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">重要性：</th>
                        <td>
                        	<select id="urgency" name="urgency" style="width: 400px" >
                        		<option value="3" >3级重要</option>
                        		<option value="2" >2级重要</option>
                        		<option value="1" >1级重要</option>
                        	</select>
                        </td>
                    </tr>
                    <tr> 
                        <th style="text-align: right">是否显示：</th>
                         <td>
                        <label><input type="radio" value="0" name="is_active" style="width:20px;"/>否</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="1" name="is_active" checked="checked"  style="width:20px;"/>是</label>
                        </td>
                    </tr>
                    <tr>
                    	<th style="text-align: right">上传附件：</th>
			    		<td>
			    			<div><input type="file" id="file1" name="file1"/></div>
			    			<div><input type="file" id="file2" name="file2"/></div>
			    			<div><input type="file" id="file3" name="file3"/></div>
			    		</td>
			    	</tr>
                </table>
                
                <table width="100%" id="detail-table" >
			     <tr><td><?php echo $this->_var['FCKeditor']; ?></td></tr>
			    </table>
			    
                </form>
            </div>
            <div region="south" border="false" style="text-align: center;  height:35px; line-height: 10px; padding: 0px;">
                <a id="btn_edit_ok" onclick="savesubmit(this);" icon="icon-save" class="easyui-linkbutton" href="javascript:void(0)">确定</a>
                <a id="btn_edit_cancel" onclick="me.edit_window.window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
            </div>
        </div>
    </div>
    
                
</body>
</html>
