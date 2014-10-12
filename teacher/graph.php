<?php 

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH.'data/jpgraph/jpgraph.php');

$graph_width = empty($_REQUEST['graph_width']) ? 800 : intval($_REQUEST['graph_width']);
$graph_height = empty($_REQUEST['graph_height']) ? 600 : intval($_REQUEST['graph_width']);

//班级单科成绩柱形图
if ($_REQUEST['act'] == 'class_subject')
{
	$subject = empty($_REQUEST['subject']) ? '' : trim($_REQUEST['subject']);//考试编码
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试编码
	
	
	$list = get_scores_by_exam($class_code, $prj_id, "", $subject, " s.student_code");
// 	print_r($list);
	if(count($list)<1){
		die("目前没有数据！");
	}
	
	$X = array();//学生
	$datay = array();
	foreach($list as $k=>$v){
		$X[] = $v["student_code"]."-".iconv("UTF-8","GB2312//IGNORE",$v["student_name"]);
// 		$X[] = $v["student_code"]."-".$v["student_name"]; 
		$datay[] = $v["score"] + $v["add_score"];
	}
	
	$title = $class_code."班，《".$list[0]["prj_name"]."-".$subject."》成绩柱形图";
	
// 	print_r($X); echo '<br/>';
// 	print_r($datay);echo '<br/>';
// 	print_r($title);echo '<br/>';
	$tickPos = array();
	for($i=0;$i<80;$i++){
		$tickPos[] = $i*2;
	}
	
	require (ROOT_PATH.'data/jpgraph/jpgraph_bar.php');
	
	// Create the graph. These two calls are always required
	$graph = new Graph($graph_width,$graph_height,'auto');//
	
	$graph->SetScale("textlin");
	
	//$theme_class="DefaultTheme";
	//$graph->SetTheme(new $theme_class());
	
	// set major and minor tick positions manually
	$graph->yaxis->SetTickPositions($tickPos);
	$graph->SetBox(false);
	
	//$graph->ygrid->SetColor('gray');
	$graph->ygrid->SetFill(false);
	$graph->xaxis->SetTickLabels($X);
	$graph->xaxis->SetFont(FF_SIMSUN, FS_BOLD);
	
	$graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","学生"));
	$graph->xaxis->title->SetFont(FF_SIMSUN, FS_BOLD);
	
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","分数"));
	$graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD);
	
	// Create the bar plots
	$b1plot = new BarPlot($datay);
	
	// ...and add it to the graPH
	$graph->Add($b1plot);
	
	$b1plot->value->show();
	$b1plot->SetColor("white");
	$b1plot->SetFillGradient("#4B0082","white",GRAD_LEFT_REFLECTION);
	$b1plot->SetWidth(45);
	
	$graph->title->Set(iconv("UTF-8","GB2312//IGNORE",$title)) ;
	$graph->title->SetFont(FF_SIMSUN, FS_BOLD);
	
	// Display the graph
	$graph->Stroke();
	exit();
}


//班级单科成绩柱形图
elseif ($_REQUEST['act'] == 'class_total')
{
	$prj_id = empty($_REQUEST['prj_id']) ? '' : trim($_REQUEST['prj_id']);//考试编码

	$list = get_scores_by_exam($class_code, $prj_id, "", "", " s.student_code");
	if(count($list)<1){
		die("目前没有数据！");
	}
	
	$students = array();
	foreach($list as $k=>$v){
		$name = $v["student_code"]."-".iconv("UTF-8","GB2312//IGNORE",$v["student_name"]);
		$score = $v["score"] + $v["add_score"];
		$students[$name]=$students[$name]+$score;
	}
	
	$X = array();//学生
	$datay = array();//总成绩
	foreach($students as $k=>$v){
		$X[] = $k;
		$datay[] = $v;
	}

	$title = $class_code."班，".$list[0]["prj_code"]."总成绩柱形图";

	$tickPos = array();
	for($i=0;$i<180;$i++){
		$tickPos[] = $i*10;
	}

	require (ROOT_PATH.'data/jpgraph/jpgraph_bar.php');

	// 	$datay=array(62,105,85,50);
	// 	$X = array('A','B','C','D');

	// Create the graph. These two calls are always required
	$graph = new Graph($graph_width,$graph_height,'auto');//

	$graph->SetScale("textlin");

	//$theme_class="DefaultTheme";
	//$graph->SetTheme(new $theme_class());

	// set major and minor tick positions manually
	$graph->yaxis->SetTickPositions($tickPos);
	$graph->SetBox(false);

	//$graph->ygrid->SetColor('gray');
	$graph->ygrid->SetFill(false);
	$graph->xaxis->SetTickLabels($X);
	$graph->xaxis->SetFont(FF_SIMSUN, FS_BOLD);

	$graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","学生"));
	$graph->xaxis->title->SetFont(FF_SIMSUN, FS_BOLD);

	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);

	$graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","总分数"));
	$graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD);

	// Create the bar plots
	$b1plot = new BarPlot($datay);

	// ...and add it to the graPH
	$graph->Add($b1plot);

	$b1plot->value->show();
	$b1plot->SetColor("white");
	$b1plot->SetFillGradient("#4B0082","white",GRAD_LEFT_REFLECTION);
	$b1plot->SetWidth(45);

	$graph->title->Set(iconv("UTF-8","GB2312//IGNORE",$title)) ;
	$graph->title->SetFont(FF_SIMSUN, FS_BOLD);

	// Display the graph
	$graph->Stroke();
	exit();
}


else if ($_REQUEST['act'] == 'history_score')//学生历次考试成绩走势图
{
	$student_code = empty($_REQUEST['student_code']) ? '' : trim($_REQUEST['student_code']);//学生学号
	$subject = empty($_REQUEST['subject']) ? '' : trim($_REQUEST['subject']);//考试编码
	
	$list = get_scores_by_exam($class_code, "", $student_code, $subject, " s.prj_id");
	if(count($list)<1){
		die("目前没有数据！");
	}
	
	$title = $student_code."-".$list[0]["student_name"]."同学历次考试成绩走势图";
	
	$subject_scores = array();
	foreach($list as $k=>$v){
		$subject_scores[$v["prj_name"]][$v["subject"]] = $v["score"] + $v["add_score"];
	}
	
	$X = array();//横轴
	$datas = array();
	foreach($subject_scores as $k=>$v){
		$X[] = iconv("UTF-8","GB2312//IGNORE",$k);
// 		$X[] = $k;
		foreach($v as $sk=>$sv){
			$datas[$sk][] = $sv;
		}
// 		print_r($v);echo '<br>';
	}
// 	print_r($X);echo '<br>';
// 	print_r($datas);echo '<br>';
// 	die("test");
	
	require (ROOT_PATH.'data/jpgraph/jpgraph_line.php');
	require (ROOT_PATH.'data/jpgraph/jpgraph_scatter.php');
	
	// Setup the graph
	$graph = new Graph($graph_width,$graph_height, "auto");
	
	$graph->SetScale("textlin",0,100);
	
	$theme_class= new UniversalTheme;
	$graph->SetTheme($theme_class);
	
	$graph->title->Set(iconv("UTF-8","GB2312//IGNORE",$title)) ;
	$graph->title->SetFont(FF_SIMSUN, FS_BOLD);
	
	$graph->SetBox(false);
	$graph->ygrid->SetFill(false);
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","分数"));
	$graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD);
	
	$graph->xaxis->SetTickLabels($X);
	$graph->xaxis->SetFont(FF_SIMSUN, FS_BOLD);
	$graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","考试名称"));
	$graph->xaxis->title->SetFont(FF_SIMSUN, FS_BOLD);
	
	$i = 0;
	foreach($datas as $k=>$v){
		// Create the plot
		$p = new LinePlot($v);
		$graph->Add($p);
		
		$color = getColor($i++);
		$p->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
		$p->mark->SetColor($color);
		$p->mark->SetFillColor($color);
		$p->SetCenter();
		
		$p->SetColor($color);
		$p->value->SetFormat('%d');
		$p->value->Show();
		$p->value->SetColor($color);
		
		$p->SetLegend(iconv("UTF-8","GB2312//IGNORE",$k));
	}
	
	$graph->Stroke();
	exit();
	
}

else if ($_REQUEST['act'] == 'history_rank')//学生历次考试排名走势图
{
	$student_code = empty($_REQUEST['student_code']) ? '' : trim($_REQUEST['student_code']);//学生学号
	$subject = empty($_REQUEST['subject']) ? '' : trim($_REQUEST['subject']);//考试编码
	
	$prjs = get_exam_prjs($class_code);
	
	$prj_rank = array();
	foreach($prjs as $k=>$e){
		$list = get_scores_by_exam($class_code, "", $e["code"], "");
		$exam_rank[$e["prj_code"]][$e["subject"]][] = get_rank($student_code, $list);
	}
	
	if(count($exam_rank)<1){
		die("目前没有数据！");
	}
	
// 	print_r($exam_rank);echo '<br>';
	
	$title = $student_code."-".$list[0]["student_name"]."同学历次考试名次走势图";

	$X = array();//横轴

	$datas = array();
	foreach($exam_rank as $k=>$v){
		$X[] = iconv("UTF-8","GB2312//IGNORE",$k);
		foreach($v as $sk=>$sv){
			$datas[$sk][] = $sv[0];
		}
	}

	
	
	require (ROOT_PATH.'data/jpgraph/jpgraph_line.php');
	require (ROOT_PATH.'data/jpgraph/jpgraph_scatter.php');

	// Setup the graph
	$graph = new Graph($graph_width,$graph_height, "auto");

	$graph->SetScale("textlin",0,40);

	$theme_class= new UniversalTheme;
	$graph->SetTheme($theme_class);

	$graph->title->Set(iconv("UTF-8","GB2312//IGNORE",$title)) ;
	$graph->title->SetFont(FF_SIMSUN, FS_BOLD);

	$graph->SetBox(false);
	$graph->ygrid->SetFill(false);
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","排名"));
	$graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD);

	$graph->xaxis->SetTickLabels($X);
	$graph->xaxis->SetFont(FF_SIMSUN, FS_BOLD);
	$graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","考试名称"));
	$graph->xaxis->title->SetFont(FF_SIMSUN, FS_BOLD);

	$i = 0;
	foreach($datas as $k=>$v){
		// Create the plot
		$p = new LinePlot($v);
		$graph->Add($p);

		$color = getColor($i++);
		$p->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
		$p->mark->SetColor($color);
		$p->mark->SetFillColor($color);
		$p->SetCenter();

		$p->SetColor($color);
		$p->value->SetFormat('%d');
		$p->value->Show();
		$p->value->SetColor($color);

		$p->SetLegend(iconv("UTF-8","GB2312//IGNORE",$k));
	}

	$graph->Stroke();
	exit();

}


else if ($_REQUEST['act'] == 'duty')//学生量化走势图
{
	$student_code = empty($_REQUEST['student_code']) ? '' : trim($_REQUEST['student_code']);//学生学号
	$sdate = empty($_REQUEST['search_sdate']) ? '' : trim($_REQUEST['search_sdate']);//起始日期
	$edate = empty($_REQUEST['search_edate']) ? '' : trim($_REQUEST['search_edate']);//截止日期
	
	$list = get_dutys($class_code, $student_code, "", $sdate, $edate, " d.date_ ", " d.date_ ");
	if(count($list)<1){
		die("目前没有数据！");
	}
	
	$student_name = get_student_name($class_code, $student_code);

	$title = $student_code."-".$student_name."同学日常规范打分走势图";

	$X = array();//横轴

	$datas = array();
	foreach($list as $k=>$v){
		$X[] = $v["date_"]."日";;
		$datas[] = $v["score"];
	}
// 			print_r($X);echo '<br>';
// 			print_r($datas);echo '<br>';
// 			die("test");

	require (ROOT_PATH.'data/jpgraph/jpgraph_line.php');
	require (ROOT_PATH.'data/jpgraph/jpgraph_scatter.php');

	// Setup the graph
	$graph = new Graph($graph_width,$graph_height, "auto");

	$graph->SetScale("textlin",-50,50);

	$theme_class= new UniversalTheme;
	$graph->SetTheme($theme_class);

	$graph->title->Set(iconv("UTF-8","GB2312//IGNORE",$title)) ;
	$graph->title->SetFont(FF_SIMSUN, FS_BOLD);

	$graph->SetBox(false);
	$graph->ygrid->SetFill(false);
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","得分"));
	$graph->yaxis->title->SetFont(FF_SIMSUN, FS_BOLD);

	$graph->xaxis->SetTickLabels($X);
	$graph->xaxis->SetFont(FF_SIMSUN, FS_BOLD);
	$graph->xaxis->title->Set(iconv("UTF-8","GB2312//IGNORE","日期"));
	$graph->xaxis->title->SetFont(FF_SIMSUN, FS_BOLD);

	$i = 0;
	$p = new LinePlot($datas);
	$graph->Add($p);

	$color = getColor($i++);
	$p->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
	$p->mark->SetColor($color);
	$p->mark->SetFillColor($color);
	$p->SetCenter();

	$p->SetColor($color);
	$p->value->SetFormat('%d');
	$p->value->Show();
	$p->value->SetColor($color);

	$graph->Stroke();
	exit();

}


else {
	
	die("No graph, please check your params!");
}


function getColor($i){
	$color = array(
		"#FF8080",
		"#0080FF",
		"#66C2EB",
		"#F83E07",
		"#42FB04",
		"#22DDDD",
		"#7B32CD",
		"#C52FD0",
		"#400040",
		"#FF8000",
		"#55bbdd",
		"#aadddd"
	);
	return $color[$i];
}

/**
 * 获取某个学生在某个科目中的排名
 * @param unknown_type $student_code
 * @param unknown_type $list
 */
function get_rank($student_code, $list){
	$score = 0;
	foreach($list as $k=>$v){
		if($v["student_code"]==$student_code){
			$score = $v["score"]+$v["add_score"];
		}
	}
	
	$rank = 1;
	foreach($list as $k=>$v){
		$score_tmp = $v["score"]+$v["add_score"];
		if($score_tmp>$score){
			$rank++;
		}
	}
	
	return $rank;
}
