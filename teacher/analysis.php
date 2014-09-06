<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
// require_once(ROOT_PATH. 'data/jpgraph/jpgraph.php');
// require_once(ROOT_PATH. 'data/jpgraph/jpgraph_line.php');
// require_once(ROOT_PATH. 'data/jpgraph/jpgraph_scatter.php');

// $graph = new Graph(800,600);	//图形大小

//查询条件加载
//班级所有学生
$students = get_students($_SESSION["class_code"]);
// print_r($students);echo '<br>';
//班级所有考试科目
$subjects = get_subjects($_SESSION["class_code"]);
// print_r($subjects);echo '<br>';

// if ($_REQUEST['act'] == 'score_trend')//学生历次考试成绩走势图
// {
// 	$student_code = empty($_REQUEST['search_student_code']) ? '' : trim($_REQUEST['search_student_code']);//学生学号
// 	$subject = empty($_REQUEST['search_subject']) ? '' : trim($_REQUEST['search_subject']);//科目
	
// 	$list = get_scores_by_exam($class_code, "", "", $student_code, " s.exam_code", $subject);
	
// 	$student = $list[0]["student_name"];
// 	$title = $student_code."-".$student."同学历次考试成绩走势图";
	
// 	$X = array();//横轴——历年的考试名称
// 	foreach($list as $k=>$v){
// 		$X[] = $v["exam_name"];
// 	}
	
// // 	print_r($X);echo '<br>';
// 	$X = array('A','B','C','D');
	
// 	$graph = score_trend($title, $X);
// }




require_once(ROOT_PATH. 'data/jpgraph/jpgraph.php');
require_once(ROOT_PATH. 'data/jpgraph/jpgraph_line.php');
require_once(ROOT_PATH. 'data/jpgraph/jpgraph_scatter.php');

$datay1 = array(33,20,24,5,38,24,22);
$datay2 = array(9,7,10,25,10,8,4);

// Setup the graph
$graph = new Graph(300,250);

$graph->SetScale("textlin",0,50);

$theme_class= new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->title->Set("Line Plots with Markers");

$graph->SetBox(false);
$graph->ygrid->SetFill(false);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->HideZeroLabel();

$graph->xaxis->SetTickLabels(array('A','B','C','D','E','F','G'));
// Create the plot
$p1 = new LinePlot($datay1);
$graph->Add($p1);

$p2 = new LinePlot($datay2);
$graph->Add($p2);

// Use an image of favourite car as marker
// $p1->mark->SetType(MARK_IMG,'new1.gif',0.8);
$p1->SetColor('#aadddd');
$p1->value->SetFormat('%d');
$p1->value->Show();
$p1->value->SetColor('#55bbdd');

// $p2->mark->SetType(MARK_IMG,'new2.gif',0.8);
$p2->SetColor('#ddaa99');
$p2->value->SetFormat('%d');
$p2->value->Show();
$p2->value->SetColor('#55bbdd');


// $graph->Stroke();

// $datay1 = array(33,20,24,5,38,24,22);
// $datay2 = array(9,7,10,25,10,8,4);

// // Setup the graph
// $graph = new Graph(800,600);	//图形大小

// $graph->SetScale("textlin",0,50);

// $theme_class= new UniversalTheme;
// $graph->SetTheme($theme_class);

// $graph->title->Set("title标题");		//标题

// $graph->SetBox(false);
// $graph->ygrid->SetFill(false);
// $graph->yaxis->HideLine(false);
// $graph->yaxis->HideTicks(true,true);
// $graph->yaxis->HideZeroLabel();

// $graph->xaxis->SetTickLabels(array('A','B','C','D','F','G'));
// // Create the plot
// $p1 = new LinePlot($datay1);
// $graph->Add($p1);

// $p2 = new LinePlot($datay2);
// $graph->Add($p2);

// // Use an image of favourite car as marker
// // $p1->mark->SetType(MARK_IMG,'new1.gif',0.8);
// $p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
// $p1->mark->SetColor('#55bbdd');
// $p1->mark->SetFillColor('#55bbdd');

// $p1->SetColor('#aadddd');
// $p1->value->SetFormat('%d');
// $p1->value->Show();
// $p1->value->SetColor('#55bbdd');

// // $p2->mark->SetType(MARK_IMG,'new2.gif',0.8);
// $p2->SetColor('#ddaa99');
// $p2->value->SetFormat('%d');
// $p2->value->Show();
// $p2->value->SetColor('#ddaa99');

// $p2->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
// $p2->mark->SetColor('#ddaa99');
// $p2->mark->SetFillColor('#ddaa99');

?>

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
		function create_trend(){
			$("#search_form").submit();
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
                    	<td style="text-align: right; width:80px;">学号：</td>
                        <td style="text-align: left; width:130px;">
                        	<select id="search_student_code" name="search_student_code" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php 
                        			foreach($students as $k=>$v){
                        				?>
                        				<option value="<?=$v["code"]?>"><?=$v["code"]."-".$v["name"]?></option>
                        				<?php 
                        			}
                        		?>
                        	</select>
                        </td>
                        
                        
                        <td style="text-align: right; width:80px;">考试科目：</td>
                        <td style="text-align: left; width:130px;">
                        	<select id="search_subject" name="search_subject" style="width:120px;">
                        		<option value="">所有...</option>
                        		<?php 
                        			foreach($subjects as $k=>$v){
                        				?>
                        				<option value="<?=$v["subject"]?>"><?=$v["subject"]?></option>
                        				<?php 
                        			}
                        		?>
                        	</select>
                        </td>
                        
                        <td style="text-align: left; width:430px;">
                        	<a href="javascript:void(0)" onclick="create_trend();" class="easyui-linkbutton" icon="icon-search">查看成绩走势图</a>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
           </form>
     	</div>
     	
     <div data-options="region:'center'">
    <?php 
//     if($graph->title){

//     	echo $graph->title->Show();
    	$graph->Stroke();
//     }
    ?>
    
    </div>
                
</body>
</html>
