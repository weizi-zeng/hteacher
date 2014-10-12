<?php 
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
?> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>量化分析</title>
    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/icon/favicon.ico" />

    <link href="../resource/easyui/themes/default/easyui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../resource/easyui/themes/icon.css" type="text/css" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />

    <script src="../resource/easyui/jquery.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="../resource/easyui/easyui-lang-zh_CN.js" type="text/javascript"></script>

    <script src="js/common.js" type="text/javascript"></script>
    
    <script type="text/javascript">
	    $(function(){
			getGraph();
		});

		function getGraph(){
			var graph_size = $("#graph_size").val();
			var graph_width = graph_size.split('*')[0];
			var graph_height = graph_size.split('*')[1];
			var htm = '<iframe src="graph.php?act=duty&graph_width='+graph_width+'&graph_height='+graph_height+'" scrolling="auto" frameborder="0" style="width:100%;height:100%;"></iframe>';
			$("#graph_load").html(htm);
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
    
    <div data-options="region:'north'" style="height:50px;margin-bottom:5px;">
          <form id="search_form" method="post" action="analysis.php?act=score_trend" style="margin-top:10px;">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table">
                    <tr>
                        <td style="text-align: right; width:80px;">图像大小：</td>
                        <td style="text-align: left; width:124px;">
                        	<select id="graph_size" name="graph_size" style="width:120px;">
                        		<option value="400*300">400*300</option>
                        		<option value="800*600" selected>800*600</option>
                        		<option value="900*600">900*600</option>
                        		<option value="1200*600">1200*600</option>
                        		<option value="1200*900">1200*900</option>
                        		<option value="1600*600">1600*600</option>
                        		<option value="1600*1200">1600*1200</option>
                        		<option value="2000*600">2000*600</option>
                        		<option value="2000*1500">2000*1500</option>
                        	</select>
                        </td>
                        
                        
                        <td style="text-align: left; width:430px;">
                        	<a href="javascript:void(0)" onclick="getGraph();" class="easyui-linkbutton" icon="icon-search">查看量化趋势图</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     	
     <div data-options="region:'center'">
     	<div id="graph_load" style="width:100%;height:100%;"></div>
    </div>
                
</body>
</html>