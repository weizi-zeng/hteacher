<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');

	$title = "教育园地";
	$msg = "";
	require_once 'themes/default/header.htm';
	
	$education_id = empty($_REQUEST["education_id"])?"0":intval($_REQUEST["education_id"]);
?>
  
  <div class="content" id="cont" style="overflow:hidden">
    <div class="left" style="overflow:hidden">
    	<div width="100%" width="674px">
			
			<form id="form1">
				<div class="left">
				 
				  <div class="left_box">
				  
						<!--banner-->
						<div id="bannerCareers"><img src="images/education.jpg" width="620px" height="200px" alt=""></div>
						<!--banner结束-->
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<div id="containerM1">
							<?php 
								if($education_id>0){
									$id = $education_id;
									$sql = "select * from ".$ecs->table("education")." where education_id=".$education_id;
									$row = $db->getRow($sql);
									
									if(empty($row) || $row["is_active"]!=1){
										echo('<font style="color:red;">您所有查看的文章不存在或者是被屏蔽</font>');
										
									}else {
										
										/* 上一篇下一篇文章 */
										$next_education = $db->getRow("SELECT education_id, title FROM " .$ecs->table('education'). " WHERE education_id > $id AND is_active=1 LIMIT 1");
											
										$prev_aid = $db->getOne("SELECT max(education_id) FROM " . $ecs->table('education') . " WHERE education_id < $id AND is_active=1");
										if (!empty($prev_aid))
										{
											$prev_education = $db->getRow("SELECT education_id, title FROM " .$ecs->table('education'). " WHERE education_id = $prev_aid");
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
												if($prev_education){
													?>
													上一篇:<a href="education.php?act=view&education_id=<?=$prev_education[education_id] ?>" class="f6"><?=$prev_education["title"] ?></a><br />
													<?php 	
												}
												if($next_education){
													?>
													下一篇:<a href="education.php?act=view&education_id=<?=$next_education[education_id] ?>" class="f6"><?=$next_education["title"] ?></a><br />
													<?php 	
												}
												
											?>
										</div>
										</div>
										
										<?php 
									}
									?>
									
									<div class="blank"></div>
									<?php 
									
								}else {
									?>
									<div >
									<div style="font-size: 14px;padding: 0 15px;font-weight: bold;">教育园地</div>
										<ul class="right_list">
										<?
										$sql = "select * from ".$ecs->table('education')." where is_active=1 order by education_id desc";
										$education = $db->getAll($sql);
										
										foreach($education as $k=>$edu){
											?>
											<li>
												<a href="education.php?act=view&education_id=<?=$edu[education_id] ?>"><?=$edu[title] ?>  <?=$edu["created"] ?></a>
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