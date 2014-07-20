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
    <script src="js/score.js" type="text/javascript"></script>
    

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
    
    <div title="数据列表" region="center" style="height:300px;" border="false">
    	<table id="dgData" style="height:400px"></table>
    
     	<div id="toolbar" style="padding:5px;height:auto">
     	 <div style="margin-bottom:5px">
            <a href="javascript:void(0)" onclick="add();" class="easyui-linkbutton" iconCls="icon-add" >新增</a>
            <a href="javascript:void(0)" onclick="update();" class="easyui-linkbutton" iconCls="icon-edit" >修改</a>
            <a href="javascript:void(0)" onclick="deletescore();" class="easyui-linkbutton" iconCls="icon-remove" >删除</a>
            <a href="javascript:void(0)" onclick="importscores();" class="easyui-linkbutton" iconCls="icon-application_put" >导入</a>
            <a href="javascript:void(0)" onclick="template();" class="easyui-linkbutton" iconCls="icon-arrow_down" >下载模板</a>
         </div>
    	 <div style="height:40px;margin-bottom:5px;" >
          <form id="search_form" method="post">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                    	
                        <td style="text-align: right; width:80px;">考试项目：</td>
                        <td style="text-align: left; width:130px;">
                        	<select id="search_exam_name" name="search_exam_name" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['exam_names']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'exam_name');if (count($_from)):
    foreach ($_from AS $this->_var['exam_name']):
?>
                        		<option value="<?php echo $this->_var['exam_name']; ?>"><?php echo $this->_var['exam_name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: right; width:80px;">考试编号：</td>
                        <td style="text-align: left; width:130px;">
                        	<select id="search_exam_code" name="search_exam_code" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['exam_codes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'exam_code');if (count($_from)):
    foreach ($_from AS $this->_var['exam_code']):
?>
                        		<option value="<?php echo $this->_var['exam_code']; ?>"><?php echo $this->_var['exam_code']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: right; width:80px;">学号：</td>
                        <td style="text-align: left; width:130px;">
                        	<select id="search_student_code" name="search_student_code" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['students']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'student');if (count($_from)):
    foreach ($_from AS $this->_var['student']):
?>
                        		<option value="<?php echo $this->_var['student']['code']; ?>"><?php echo $this->_var['student']['code']; ?>-<?php echo $this->_var['student']['name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: left; width:430px;">
                        	<a href="javascript:void(0)" onclick="searchscores();" class="easyui-linkbutton" icon="icon-search">查找</a>
                        	<a href="javascript:void(0)" onclick="exportscoresbyexamname();" class="easyui-linkbutton" iconCls="icon-application_get" >按考试项目导出</a>
                        	<a href="javascript:void(0)" onclick="exportscoresbyexamcode();" class="easyui-linkbutton" iconCls="icon-application_get" >按考试编号导出</a>
                        	<a href="javascript:void(0)" onclick="exportscoresbystudentcode();" class="easyui-linkbutton" iconCls="icon-application_get" >按学生学号导出</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     </div>
        
    </div>
    
    
    <div id="edit_window" class="easyui-window" closed="true" title="数据维护" style="width: 480px;
        height: 220px; padding: 0px;" >
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" fit="true" style="padding: 0px; background: #fff; border: 0px;">
                <form id="edit_form" name="edit_form" method="post" style="padding-top:20px;">
                
                <div title="隐藏参数">
                    <input type="hidden" id="score_id" name="score_id" value="0" />
                </div>
                
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                	<tr>
                        <th style="text-align: right">考试编码：</th>
                        <td style="width:70%">
                        	<select id="exam_code" name="exam_code" style="width:120px;">
                        		<?php $_from = $this->_var['exam_codes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'exam_code');if (count($_from)):
    foreach ($_from AS $this->_var['exam_code']):
?>
                        		<option value="<?php echo $this->_var['exam_code']; ?>"><?php echo $this->_var['exam_code']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">学号：</th>
                        <td style="width:70%">
                        	<select id="student_code" name="student_code" style="width:120px;">
                        		<?php $_from = $this->_var['students']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'student');if (count($_from)):
    foreach ($_from AS $this->_var['student']):
?>
                        		<option value="<?php echo $this->_var['student']['code']; ?>"><?php echo $this->_var['student']['code']; ?>-<?php echo $this->_var['student']['name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">分数：</th>
                        <td><input id="score" name="score" type="text" class="easyui-numberbox" value="" data-options="requird:true,min:0,precision:2" maxlength="20" style="width: 150px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">附加分数：</th>
                        <td><input id="add_score" name="add_score" type="text" class="easyui-numberbox" value="0" data-options="requird:true,min:0,precision:2"  maxlength="20" style="width: 150px" /></td>
                    </tr>
                </table>
                </form>
            </div>
            <div region="south" border="false" style="text-align: center;  height:35px; line-height: 10px; padding: 0px;">
                <a id="btn_edit_ok" onclick="save();" icon="icon-save" class="easyui-linkbutton" href="javascript:void(0)">确定</a>
                <a id="btn_edit_cancel" onclick="me.edit_window.window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
            </div>
        </div>
    </div>
    
    
    <div id="import_window" class="easyui-window" closed="true" title="导入数据" style="width: 480px;
        height: 220px; padding: 0px;" >
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" fit="true" style="padding: 0px; background: #fff; border: 0px;">
                <form id="import_form" action="score.php?act=import" name="import_form" method="post" enctype="multipart/form-data" style="padding-top:20px;text-align:center;">
                	<div><input type="file" id="importFile" name="importFile"/></div>
                	<div style="text-align: center; margin-top:50px; height:35px; line-height: 10px; padding: 0px;">
		                <a onclick="importscores_import();" icon="icon-save" class="easyui-linkbutton" href="javascript:void(0)">确定</a>
		                <a onclick="$('#import_window').window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
		            </div>
            
                </form>
            </div>
            
        </div>
    </div>
    
                
</body>
</html>
