<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>人员信息</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/exam.js" type="text/javascript"></script>
    

    <script type="text/javascript">
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
            <a href="javascript:void(0)" onclick="add();" class="easyui-linkbutton" iconCls="icon-add" >新增</a>
            <a href="javascript:void(0)" onclick="update();" class="easyui-linkbutton" iconCls="icon-edit" >修改</a>
            <a href="javascript:void(0)" onclick="deleteexam();" class="easyui-linkbutton" iconCls="icon-remove" >删除</a>
            
            <a href="javascript:void(0)" onclick="importexams();" class="easyui-linkbutton" iconCls="icon-application_put" >导入</a>
            <a href="javascript:void(0)" onclick="template();" class="easyui-linkbutton" iconCls="icon-arrow_down" >下载模板</a>
            
         </div>
    	 <div style="height:40px;margin-bottom:5px;" >
          <form id="search_form" method="post">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                   		<td style="text-align: right; width:80px;">考试编号：</td>
                        <td style="text-align: left; width:150px;">
                        	<select id="search_code" name="search_code" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['exam_codes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'exam_code');if (count($_from)):
    foreach ($_from AS $this->_var['exam_code']):
?>
                        		<option value="<?php echo $this->_var['exam_code']; ?>"><?php echo $this->_var['exam_code']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: right; width:80px;">考试项目：</td>
                        <td style="text-align: left; width:150px;">
                        	<select id="search_name" name="search_name" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['exam_names']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'exam_name');if (count($_from)):
    foreach ($_from AS $this->_var['exam_name']):
?>
                        		<option value="<?php echo $this->_var['exam_name']; ?>"><?php echo $this->_var['exam_name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: right; width:80px;">考试科目：</td>
                        <td style="text-align: left; width:150px;">
                        	<select id="search_subject" name="search_subject" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['exam_subjects']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'exam_subject');if (count($_from)):
    foreach ($_from AS $this->_var['exam_subject']):
?>
                        		<option value="<?php echo $this->_var['exam_subject']; ?>"><?php echo $this->_var['exam_subject']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: left; width:450px;">
                        	 <a href="javascript:void(0)" onclick="searchexams();" class="easyui-linkbutton" icon="icon-search">查找</a>
                        	 <a href="javascript:void(0)" onclick="publicexam();" class="easyui-linkbutton" iconCls="icon-application_view_list" >发布考试</a>
           					 <a href="javascript:void(0)" onclick="sendexam();" class="easyui-linkbutton" iconCls="icon-note_go" >短信通知</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     </div>
        
    </div>
    
    
    <div id="edit_window" class="easyui-window" closed="true" title="数据维护" style="width: 480px;
        height: 300px; padding: 0px;" >
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" fit="true" style="padding: 0px; background: #fff; border: 0px;">
                <form id="edit_form" name="edit_form" method="post" style="padding-top:20px;">
                
                <div title="隐藏参数">
                    <input type="hidden" id="exam_id" name="exam_id" value="0" />
                </div>
                
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                	<tr>
                        <th style="text-align: right">考试编号：</th>
                        <td style="width:70%">
                        	<input id="code" name="code" value="2014030201" class="easyui-validatebox" data-options="required:true"  maxlength="20" style="width: 170px" />
                        	<span style="color:red;">必须唯一</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th style="text-align: right">考试项目：</th>
                        <td style="width:70%">
                        	<input id="name" name="name" value="期末统考" class="easyui-validatebox" data-options="required:true"  maxlength="20" style="width: 170px" />
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">考试科目：</th>
                        <td><input id="subject" name="subject" value="数学" class="easyui-validatebox" data-options="required:true"  maxlength="20" style="width: 170px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">监考老师：</th>
                        <td><input id="teacher" name="teacher" class="easyui-validatebox" data-options="required:true"  maxlength="20" style="width: 170px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">考试日期：</th>
                        <td><input id="examdate" name="examdate" class="easyui-datebox" data-options="required:true"  maxlength="20" style="width: 170px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">考试起止时间：</th>
                        <td>
                        	<input id="stime" name="stime" type="text" class="easyui-timespinner" style="width:80px;" data-options="required:true"/>
                        	-
                        	<input id="etime" name="etime" type="text" class="easyui-timespinner" style="width:80px;" data-options="required:true"/>
                        </td>
                    </tr>
                    <tr> 
                        <th style="text-align: right">所在教室：</th>
                        <td><input id="classroom" name="classroom" type="text" class="easyui-validatebox" data-options="required:true" maxlength="20" style="width: 170px" /></td>
                    </tr>
                    <tr> 
                        <th style="text-align: right">是否归档：</th>
                         <td>
                        <label><input type="radio" value="0" name="closed" checked="checked" style="width:20px;"/>否</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="1" name="closed" style="width:20px;"/>是</label>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            <div region="south" border="false" style="text-align: center;  height:35px; line-height: 10px; padding: 0px;">
                <a id="save" icon="icon-save" class="easyui-linkbutton" href="javascript:void(0)">确定</a>
                <a id="btn_edit_cancel" onclick="me.edit_window.window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
            </div>
        </div>
    </div>
    
    <div id="import_window" class="easyui-window" closed="true" title="导入数据" style="width: 480px;
        height: 220px; padding: 0px;" >
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" fit="true" style="padding: 0px; background: #fff; border: 0px;">
                <form id="import_form" action="exam.php?act=import" name="import_form" method="post" enctype="multipart/form-data" style="padding-top:20px;text-align:center;">
                	<div><input type="file" id="importFile" name="importFile"/></div>
                	<div style="text-align: center; margin-top:50px; height:35px; line-height: 10px; padding: 0px;">
		                <a onclick="importExams_import();" icon="icon-save" class="easyui-linkbutton" href="javascript:void(0)">确定</a>
		                <a onclick="$('#import_window').window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
		            </div>
            
                </form>
            </div>
            
        </div>
    </div>
    
    
    
    <div title="发送短信" id="send_sms_window" class="easyui-window" closed="true" style="width: 680px;height:500px;padding: 0px;" border="false">
    	<div class="easyui-layout" fit="true">
    	
    		<table id="student_dgData" style="height:400px"></table>
    
     	 <div id="student_toolbar" style="padding:5px;height:auto">
     		<div style="color:red;">不能发送带广告性质或者营销性质的短信，否则短信账号会被冻结</div>
     		<div>短信内容不能超过140个字符，当前还可以输入<span style="color:red" id="tip">140</span>个字符</div>
	     	 <div style="margin-bottom:5px">
	     	 	短信内容：
	     	 	<textarea rows="4" id="sms_content" onkeyup="check()" style="width:460px;" maxlength="140"></textarea>
	     	 	<label><input type="checkbox" id="sms_copy" checked="checked" style="width:20px;"/>抄送给自己</label>
	            <a href="javascript:void(0)" id="sms_send" class="easyui-linkbutton" iconCls="icon-arrow_up" >发送短信</a>
	            <a onclick="$('#send_sms_window').window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
	         </div>
	     </div>
        </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
                
</body>
</html>