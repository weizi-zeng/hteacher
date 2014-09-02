<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');

	$title = "系统相关";
	$msg = "";
	require_once 'themes/default/header.htm';
	
	$problems_id = empty($_REQUEST["problems_id"])?"0":intval($_REQUEST["problems_id"]);
?>
  <link href="themes/default/css/notice.css" rel="stylesheet" type="text/css" />
  
  <div class="content" id="cont" style="overflow:hidden">
    <div class="left" style="overflow:hidden">
    	<div width="100%" width="674px">
			
			<form id="form1">
				<div class="left">
				 
				  <div class="left_box">
				  
						<!--banner-->
						<div id="bannerCareers"><img src="images/problems.jpg" width="620px" height="200px" alt=""></div>
						<!--banner结束-->
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<div id="containerM1">
							<?php 
								if($problems_id>0){
									$id = $problems_id;
									$sql = "select * from ".$ecs->table("problems")." where problems_id=".$problems_id;
									$row = $db->getRow($sql);
									
									if(empty($row) || $row["is_active"]!=1){
										echo('您所有查看的通知不存在或者是被屏蔽');
									}
									
									/* 上一篇下一篇文章 */
									$next_problems = $db->getRow("SELECT problems_id, title FROM " .$ecs->table('problems'). " WHERE problems_id > $id AND is_active=1 LIMIT 1");
									
									$prev_aid = $db->getOne("SELECT max(problems_id) FROM " . $ecs->table('problems') . " WHERE problems_id < $id AND is_active=1");
									if (!empty($prev_aid))
									{
										$prev_problems = $db->getRow("SELECT problems_id, title FROM " .$ecs->table('problems'). " WHERE problems_id = $prev_aid");
									}
									?>
									
									<div style="border:4px solid #fcf8f7; background-color:#fff; padding:20px 15px;max-width:1200px;">
										<div class="tc" style="padding:8px;">
										<font class="f5 f6"><?=$row["title"] ?></font><br />
										<font class="f3"><?=$row["author"] ?> / <?=$row["created"] ?></font>
										</div>
										<?=$row["content"] ?>
										<div style="margin-top:20px;"></div>
										
										<div style="padding:8px; margin-top:15px; text-align:left; border-top:1px solid #ccc;">
										<?php 
											if($prev_problems){
												?>
												上一篇:<a href="problems.php?act=view&problems_id=<?=$prev_problems[problems_id] ?>" class="f6"><?=$prev_problems["title"] ?></a><br />
												<?php 	
											}
											if($next_problems){
												?>
												下一篇:<a href="problems.php?act=view&problems_id=<?=$next_problems[problems_id] ?>" class="f6"><?=$next_problems["title"] ?></a><br />
												<?php 	
											}
											
										?>
									</div>
									</div>
									<div class="blank"></div>
									<?php 
									
								}else {
									?>
									<div >
									<div style="font-size: 14px;padding: 0 15px;font-weight: bold;">系统相关</div>
										<ul class="right_list">
										<?
										$sql = "select * from ".$ecs->table('problems')." where is_active=1 order by problems_id desc";
										$problems = $db->getAll($sql);
										
										foreach($problems as $k=>$problem){
											?>
											<li>
												<a href="problems.php?act=view&problems_id=<?=$problem[problems_id] ?>"><?=$problem[title] ?>  <?=$problem["created"] ?></a>
											</li>
											<?php
										}
										 ?>
									   </ul>
								  </div> 
									<?php 
								}
							
							?>
						</div>

						<div id="containerB"><img src="images/container_b.gif" alt="主要内容部分容器下边框"></div>
						</div>
						<!--主要内容部分容器结束-->


				  </div>
				   
				</div><!--left[end]-->
			 </form>
		
    	</div>
    </div>
	
<?php 
	require_once 'themes/default/right.htm';
?>
	
  </div>

 <?php 
	require_once 'themes/default/footer.htm';
?>