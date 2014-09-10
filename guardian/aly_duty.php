<?php 
define('IN_ECS', true);

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
    <script src="js/score.js" type="text/javascript"></script>
    
    <script type="text/javascript">

		function getGraph(){
			var student_code = $("#search_student_code").val();
			if(!student_code){
				showError('请选择学生学号!'); 
				return; 
			}
			
			var htm = '<iframe src="graph.php?act=duty&student_code='+student_code+'" scrolling="auto" frameborder="0" style="width:100%;height:100%;"></iframe>';
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
                    	<td style="text-align: right; width:80px;">学生学号：</td>
                        <td style="text-align: left; width:230px;">
                        	<select id="search_student_code" name="search_student_code" style="width:220px;">
                        		<option value="">所有...</option>
                        		<?php 
                        		require(dirname(__FILE__) . '/includes/init.php');
                        		$student = get_student($class_code, $_SESSION["student_code"]);
                        				?>
                        				<option value="<?=$student["code"] ?>"><?=$student["code"] ."-".$student["name"] ?></option>
                        				<?php 
                        		?>
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