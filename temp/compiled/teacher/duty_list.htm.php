<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>值日记录</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/duty.js" type="text/javascript"></script>
    

    <script type="text/javascript">
    	var item_score = [];
    	<?php $_from = $this->_var['duty_items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'it');if (count($_from)):
    foreach ($_from AS $this->_var['it']):
?>
    		item_score['<?php echo $this->_var['it']['name']; ?>']='<?php echo $this->_var['it']['score']; ?>';
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    	
	    function setScore(o){
	    	$('#score').numberbox('setValue', item_score[o.value]);
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
    
    <div title="数据列表" region="center" style="height:300px;" border="false">
    	<table id="dgData" style="height:400px"></table>
    
     	<div id="toolbar" style="padding:5px;height:auto">
     	 <div style="margin-bottom:5px">
            <a href="javascript:void(0)" onclick="add();" class="easyui-linkbutton" iconCls="icon-add" >新增</a>
            <a href="javascript:void(0)" onclick="update();" class="easyui-linkbutton" iconCls="icon-edit" >修改</a>
            <a href="javascript:void(0)" onclick="deleteduty();" class="easyui-linkbutton" iconCls="icon-remove" >删除</a>
            <a href="javascript:void(0)" onclick="exportRank();" class="easyui-linkbutton" icon="icon-search">导出量化排名表</a>
         </div>
    	 <div style="height:40px;margin-bottom:5px;" >
          <form id="search_form" method="post">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                   		<td style="text-align: right; width:80px;">学生学号：</td>
                        <td style="text-align: left; width:150px;">
                        	<select id="search_student_code" name="search_student_code" style="width:110px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['students']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'student');if (count($_from)):
    foreach ($_from AS $this->_var['student']):
?>
                        		<option value="<?php echo $this->_var['student']['code']; ?>"><?php echo $this->_var['student']['code']; ?>-<?php echo $this->_var['student']['name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: right; width:80px;">量化项目：</td>
                        <td style="text-align: left; width:150px;">
                        	<select id="search_name" name="search_name" style="width:110px;">
                        		<option value="">所有...</option>
                        		<?php $_from = $this->_var['duty_items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'it');if (count($_from)):
    foreach ($_from AS $this->_var['it']):
?>
                        		<option value="<?php echo $this->_var['it']['name']; ?>"><?php echo $this->_var['it']['name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                        
                        <td style="text-align: right; width:80px;">起始日期：</td>
                        <td style="text-align: left; width:120px;">
                            <input id="search_sdate" name="search_sdate" class="easyui-datebox" data-options="required:false"  maxlength="20" style="width: 110px" />
                        </td>
                        
                        <td style="text-align: right; width:80px;">截止日期：</td>
                        <td style="text-align: left; width:120px;">
                            <input id="search_edate" name="search_edate" class="easyui-datebox" data-options="required:false"  maxlength="20" style="width: 110px" />
                        </td>
                        
                        <td style="text-align: left; width:150px;">
                        	<a href="javascript:void(0)" onclick="searchdutys();" class="easyui-linkbutton" icon="icon-search">查找</a>
                        	<a href="javascript:void(0)" onclick="exportdutys();" class="easyui-linkbutton" iconCls="icon-application_get" >按条件导出</a>
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
                    <input type="hidden" id="duty_id" name="duty_id" value="0" />
                </div>
                
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                	<tr>
                        <th style="text-align: right">学生学号：</th>
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
                        <th style="text-align: right">量化项目：</th>
                        <td style="width:70%">
                        	<select id="duty_item" name="duty_item" style="width:120px;" onchange="setScore(this);">
                        		<?php $_from = $this->_var['duty_items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'it');if (count($_from)):
    foreach ($_from AS $this->_var['it']):
?>
                        		<option value="<?php echo $this->_var['it']['name']; ?>"><?php echo $this->_var['it']['name']; ?></option>
                        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">记录分数：</th>
                        <td><input id="score" name="score" class="easyui-numberbox" data-options="required:true"  maxlength="20" style="width: 114px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">事发日期：</th>
                        <td><input id="date_" name="date_" class="easyui-datebox"  maxlength="20" style="width: 120px" /></td>
                    </tr>
                    <tr> 
                        <th style="text-align: right">备注描述：</th>
                        <td>
                        	<!-- <textarea id="desc_" name="desc_" rows="4" cols="30"></textarea>  -->
                        	<input type="text" id="desc_" name="desc_" maxlength="200" style="width: 250px"  />
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
                
</body>
</html>
