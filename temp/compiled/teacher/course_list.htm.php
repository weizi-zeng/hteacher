<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>课程安排表</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/course.js" type="text/javascript"></script>
    
	<style type="text/css" >
		#dgData td{
			text-align:center;
			background-color:rgb(217, 240, 238);
			padding:0;
		}
		#dgData input{
			width:90%;
		}
	</style>
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
     <form id="edit_form" method="post">
     	<div id="toolbar" style="padding:5px;height:auto">
    	 <div style="height:40px;margin-bottom:5px;" >
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                        <td style="text-align: right; width:80px;">学期：</td>
                        <td style="text-align: left; width:150px;">
                            <select id="search_semster" name="search_semster" style="width: 170px;">
                        		<option value="2014年上学期">2014年上学期</option>
                        		<option value="2014年下学期" selected>2014年下学期</option>
                        		<option value="2015年上学期">2015年上学期</option>
                        		<option value="2015年下学期">2015年下学期</option>
                        		<option value="2016年上学期">2016年上学期</option>
                        		<option value="2016年下学期">2016年下学期</option>
                        	</select>
                        </td>
                        
                        <td style="text-align: left; width:150px;">
                        	<a href="javascript:void(0)" id="search_btn" class="easyui-linkbutton" icon="icon-search">查找</a>
                        	<a href="javascript:void(0)" onclick="exportcourses();" class="easyui-linkbutton" iconCls="icon-application_get" >按条件导出</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
     	</div>
     	<div style="margin-bottom:5px">
            <a href="javascript:void(0)" id="save" class="easyui-linkbutton" iconCls="icon-save" >保存</a>
         </div>
     </div>
     
    
     <table id="dgData" style="width:98%;" border="0">
     		<tr><td colspan="9"><h1 id="semster"></h1></td></tr>
    		<tr><td>节次</td><td>时间</td><td>星期一</td><td>星期二</td><td>星期三</td><td>星期四</td><td>星期五</td><td>星期六</td><td>星期日</td></tr>
    		<tr>
	    		<td>第一节早自习</td>
	    		<td><input type="text" id="z1_time" name="z1_time"/>
	    			<input type="hidden" id="course_id" name="course_id"/>
	    		</td>
	    		<td><input type="text" id="z1_w1" name="z1_w1"/></td>
	    		<td><input type="text" id="z1_w2" name="z1_w2"/></td>
	    		<td><input type="text" id="z1_w3" name="z1_w3"/></td>
	    		<td><input type="text" id="z1_w4" name="z1_w4"/></td>
	    		<td><input type="text" id="z1_w5" name="z1_w5"/></td>
	    		<td><input type="text" id="z1_w6" name="z1_w6"/></td>
	    		<td><input type="text" id="z1_w7" name="z1_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第二节早自习</td>
	    		<td><input type="text" id="z2_time" name="z2_time"/></td>
	    		<td><input type="text" id="z2_w1" name="z2_w1"/></td>
	    		<td><input type="text" id="z2_w2" name="z2_w2"/></td>
	    		<td><input type="text" id="z2_w3" name="z2_w3"/></td>
	    		<td><input type="text" id="z2_w4" name="z2_w4"/></td>
	    		<td><input type="text" id="z2_w5" name="z2_w5"/></td>
	    		<td><input type="text" id="z2_w6" name="z2_w6"/></td>
	    		<td><input type="text" id="z2_w7" name="z2_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第一节课</td>
	    		<td><input type="text" id="1_time" name="1_time"/></td>
	    		<td><input type="text" id="1_w1" name="1_w1"/></td>
	    		<td><input type="text" id="1_w2" name="1_w2"/></td>
	    		<td><input type="text" id="1_w3" name="1_w3"/></td>
	    		<td><input type="text" id="1_w4" name="1_w4"/></td>
	    		<td><input type="text" id="1_w5" name="1_w5"/></td>
	    		<td><input type="text" id="1_w6" name="1_w6"/></td>
	    		<td><input type="text" id="1_w7" name="1_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第二节课</td>
	    		<td><input type="text" id="2_time" name="2_time"/></td>
	    		<td><input type="text" id="2_w1" name="2_w1"/></td>
	    		<td><input type="text" id="2_w2" name="2_w2"/></td>
	    		<td><input type="text" id="2_w3" name="2_w3"/></td>
	    		<td><input type="text" id="2_w4" name="2_w4"/></td>
	    		<td><input type="text" id="2_w5" name="2_w5"/></td>
	    		<td><input type="text" id="2_w6" name="2_w6"/></td>
	    		<td><input type="text" id="2_w7" name="2_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第三节课</td>
	    		<td><input type="text" id="3_time" name="3_time"/></td>
	    		<td><input type="text" id="3_w1" name="3_w1"/></td>
	    		<td><input type="text" id="3_w2" name="3_w2"/></td>
	    		<td><input type="text" id="3_w3" name="3_w3"/></td>
	    		<td><input type="text" id="3_w4" name="3_w4"/></td>
	    		<td><input type="text" id="3_w5" name="3_w5"/></td>
	    		<td><input type="text" id="3_w6" name="3_w6"/></td>
	    		<td><input type="text" id="3_w7" name="3_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第四节课</td>
	    		<td><input type="text" id="4_time" name="4_time"/></td>
	    		<td><input type="text" id="4_w1" name="4_w1"/></td>
	    		<td><input type="text" id="4_w2" name="4_w2"/></td>
	    		<td><input type="text" id="4_w3" name="4_w3"/></td>
	    		<td><input type="text" id="4_w4" name="4_w4"/></td>
	    		<td><input type="text" id="4_w5" name="4_w5"/></td>
	    		<td><input type="text" id="4_w6" name="4_w6"/></td>
	    		<td><input type="text" id="4_w7" name="4_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第五节课</td>
	    		<td><input type="text" id="5_time" name="5_time"/></td>
	    		<td><input type="text" id="5_w1" name="5_w1"/></td>
	    		<td><input type="text" id="5_w2" name="5_w2"/></td>
	    		<td><input type="text" id="5_w3" name="5_w3"/></td>
	    		<td><input type="text" id="5_w4" name="5_w4"/></td>
	    		<td><input type="text" id="5_w5" name="5_w5"/></td>
	    		<td><input type="text" id="5_w6" name="5_w6"/></td>
	    		<td><input type="text" id="5_w7" name="5_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第六节课</td>
	    		<td><input type="text" id="6_time" name="6_time"/></td>
	    		<td><input type="text" id="6_w1" name="6_w1"/></td>
	    		<td><input type="text" id="6_w2" name="6_w2"/></td>
	    		<td><input type="text" id="6_w3" name="6_w3"/></td>
	    		<td><input type="text" id="6_w4" name="6_w4"/></td>
	    		<td><input type="text" id="6_w5" name="6_w5"/></td>
	    		<td><input type="text" id="6_w6" name="6_w6"/></td>
	    		<td><input type="text" id="6_w7" name="6_w7"/></td>
    		</tr>
    		<tr>
	    		<td>第七节课</td>
	    		<td><input type="text" id="7_time" name="7_time"/></td>
	    		<td><input type="text" id="7_w1" name="7_w1"/></td>
	    		<td><input type="text" id="7_w2" name="7_w2"/></td>
	    		<td><input type="text" id="7_w3" name="7_w3"/></td>
	    		<td><input type="text" id="7_w4" name="7_w4"/></td>
	    		<td><input type="text" id="7_w5" name="7_w5"/></td>
	    		<td><input type="text" id="7_w6" name="7_w6"/></td>
	    		<td><input type="text" id="7_w7" name="z1_w6"/></td>
    		</tr>
    		<tr>
	    		<td>第八节课</td>
	    		<td><input type="text" id="8_time" name="8_time"/></td>
	    		<td><input type="text" id="8_w1" name="z1_w1"/></td>
	    		<td><input type="text" id="8_w2" name="8_w2"/></td>
	    		<td><input type="text" id="8_w3" name="8_w3"/></td>
	    		<td><input type="text" id="8_w4" name="8_w4"/></td>
	    		<td><input type="text" id="8_w5" name="8_w5"/></td>
	    		<td><input type="text" id="8_w6" name="8_w6"/></td>
	    		<td><input type="text" id="8_w7" name="8_w7"/></td>
    		</tr>
    		
    		<tr>
	    		<td>第一节晚自习</td>
	    		<td><input type="text" id="w1_time" name="w1_time"/></td>
	    		<td><input type="text" id="w1_w1" name="w1_w1"/></td>
	    		<td><input type="text" id="w1_w2" name="w1_w2"/></td>
	    		<td><input type="text" id="w1_w3" name="w1_w3"/></td>
	    		<td><input type="text" id="w1_w4" name="w1_w4"/></td>
	    		<td><input type="text" id="w1_w5" name="w1_w5"/></td>
	    		<td><input type="text" id="w1_w6" name="w1_w6"/></td>
	    		<td><input type="text" id="w1_w7" name="w1_w7"/></td>
    		</tr>
    		
    		<tr>
	    		<td>第二节晚自习</td>
	    		<td><input type="text" id="w2_time" name="w2_time"/></td>
	    		<td><input type="text" id="w2_w1" name="w2_w1"/></td>
	    		<td><input type="text" id="w2_w2" name="w2_w2"/></td>
	    		<td><input type="text" id="w2_w3" name="w2_w3"/></td>
	    		<td><input type="text" id="w2_w4" name="w2_w4"/></td>
	    		<td><input type="text" id="w2_w5" name="w2_w5"/></td>
	    		<td><input type="text" id="w2_w6" name="w2_w6"/></td>
	    		<td><input type="text" id="w2_w7" name="w2_w7"/></td>
    		</tr>
    		
    		<tr>
	    		<td>第三节晚自习</td>
	    		<td><input type="text" id="w3_time" name="w3_time"/></td>
	    		<td><input type="text" id="w3_w1" name="w3_w1"/></td>
	    		<td><input type="text" id="w3_w2" name="w3_w2"/></td>
	    		<td><input type="text" id="w3_w3" name="w3_w3"/></td>
	    		<td><input type="text" id="w3_w4" name="w3_w4"/></td>
	    		<td><input type="text" id="w3_w5" name="w3_w5"/></td>
	    		<td><input type="text" id="w3_w6" name="w3_w6"/></td>
	    		<td><input type="text" id="w3_w7" name="w3_w7"/></td>
    		</tr>
    	</table>
    	</form>
    </div>
    
</body>
</html>
