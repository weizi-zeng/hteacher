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
    <script src="js/person.js" type="text/javascript"></script>
    

    <%--界面JS--%>
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
    
    <div title="数据列表" region="center" fit="true" border="false">
    	 <div style="height:40px;" >
          <form id="search_form" method="post">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                        <td style="text-align: right; width:80px;">姓名：</td>
                        <td style="text-align: left; width:150px;">
                            <input id="search_name" name="search_name" style="width: 150px;" />
                        </td>
                        
                         <th style="text-align: right; width:80px;">电话：</th>
                        <td style="text-align: left; width:150px;">
                            <input id="search_tel" name="search_tel" type="text" class="easyui-validatebox" data-options="required:false" maxlength="20" validType="telephone" style="width: 150px" />
                        </td>
                        
                        <th style="text-align: right; width:80px;">身份：</th>
                        <td style="text-align: left; width:150px;">
                           <select id="search_iden" name="search_iden" >
                        		<option value="教师">教师</option>
                        		<option value="学生">学生</option>
                        		<option value="家长">家长</option>
                        		<option value="其他">其他</option>
                        	</select>
                        </td>
                        <td style="text-align: left; width:150px;">
                        	<a href="javascript:void(0)" onclick="search();" class="easyui-linkbutton" icon="icon-search">查找</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     
        <table id="dgData"></table>
    </div>
    
    
    <div id="edit_window" class="easyui-window" closed="true" title="数据维护窗口" style="width: 480px;
        height: 360px; padding: 0px;" >
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" fit="true" style="padding: 0px; background: #fff; border: 0px;">
                <form id="edit_form" name="edit_form" method="post" style="padding-top:20px;">
                
                <div title="隐藏参数">
                    <input type="hidden" id="person_id" name="person_id" value="0" />
                </div>
                
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                        <th style="text-align: right">姓名：</th>
                        <td style="width:70%"><input id="name" name="name" type="text" class="easyui-validatebox" data-options="required:true" maxlength="10" style="width: 150px" /></td>
                    </tr>
                    <tr>    
                        <th style="text-align: right">性别：</th>
                        <td>
                        <label><input type="radio" value="1" name="sex" style="width:20px;"/>男</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="0" name="sex" style="width:20px;"/>女</label>
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">身份：</th>
                        <td>
                        	<select id="iden" name="iden" >
                        		<option value="教师">教师</option>
                        		<option value="学生">学生</option>
                        		<option value="家长">家长</option>
                        		<option value="其他">其他</option>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: right">名族：</th>
                        <td><input id="nation" name="nation" value="汉族" type="text" maxlength="20" style="width: 150px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">出生年月：</th>
                        <td><input id=bthday name="bthday" type="text" maxlength="20" style="width: 150px" /></td>
                    </tr>
                     <tr> 
                        <th style="text-align: right">电话：</th>
                        <td><input id="tel" name="tel" type="text" class="easyui-validatebox" data-options="required:true" maxlength="20" validType="telephone" style="width: 150px" /></td>
                    </tr>
                     <tr> 
                        <th style="text-align: right">短号：</th>
                        <td><input id="shorttel" name="shorttel" type="text" maxlength="20" style="width: 150px" /></td>
                    </tr>
                    <tr>
                        <th style="text-align: right">身份证：</th>
                        <td><input id="id_card" name="id_card" type="text" maxlength="20" style="width: 200px" /></td>
                    </tr>
                    <tr> 
                        <th style="text-align: right">邮箱：</th>
                        <td><input id="email" name="email" type="text" class="easyui-validatebox" data-options="required:true" maxlength="20" validType="email" style="width: 200px" /></td>
                    </tr>
                    
                    <tr>
                        <th style="text-align: right">地址：</th>
                        <td><input id="address" name="address" type="text" class="easyui-validatebox" data-options="required:true" maxlength="60" style="width: 200px" /></td>
                      </tr>
                   	 <tr>
                        <th style="text-align: right">单位：</th>
                        <td><input id="unit" name="unit" type="text" class="easyui-validatebox" data-options="required:false" maxlength="60" style="width: 200px" /></td>
                      </tr>
                      <tr>
                        <th style="text-align: right">是否已离校：</th>
                        <td>
                        <label><input type="radio" value="0" name="has_left" style="width:20px;"/>否</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="1" name="has_left" style="width:20px;"/>是</label>
                        </td>
                      </tr>
                </table>
                </form>
            </div>
            <div region="south" border="false" style="text-align: center;  height:35px; line-height: 10px; padding: 0px;">
                <a id="btn_edit_ok" onclick="Save();" icon="icon-save" class="easyui-linkbutton" href="javascript:void(0)">确定</a>
                <a id="btn_edit_cancel" onclick="me.edit_window.window('close');" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)" data-options="draggable:false">关闭</a>
            </div>
        </div>
    </div>
    
    
</body>
</html>