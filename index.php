<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');
	
	$title = "首页";
	$msg = "";
	require_once 'themes/default/header.htm';
?>

  <div class="content" id="cont" style="overflow:hidden">
    <div class="left" style="overflow:hidden">
    	<div width="100%" width="674px">
			
			<form id="form1">
				<div class="left">
				 
				  <div class="left_box">
				  
						<!--banner hehe-->
						<div id="bannerCareers"><img src="images/trailblazer.png" width="620px" alt=""></div>
						<!--banner结束-->
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<div id="containerM1">
						
							<div class="ariticle_cat_list_container" >
							<?php 
								$article_cat = get_article_syscat();
								foreach($article_cat as $cat){
									?>
									<div style="width:300px;height:226px;float:left;margin:2px;background:#ECF2F7;">
										<div class="right_title"><?=$cat['cat_name'] ?>   <a href="article_cat.php?id=<?=$cat['cat_id'] ?>">更多...</a></div>
										<ul class="right_list">
										<?
											$articles = get_article_by_cat($cat['cat_id'], 8);
											foreach($articles as $k=>$art){
												?>
												<li align="left" style="height:20px;" title="<?=$art['title'] ?>">
												<table width="100%" style="BORDER-BOTTOM: 333333 1px dotted">
													<tbody><tr style="cursor:hand;">
													<td align="left" valign="BOTTOM" title="<?=$art['title'] ?>"><img style="width:12px;margin-right:2px;" src="resource/easyui/themes/icons/control_play_blue.png"><a href="article.php?id=<?=$art['article_id'] ?>" target="_blank" class="lzt2"><?=$art['short_title'] ?></a></td><td align="right" >[<?=$art['date'] ?>]</td>
													</tr>
													</tbody>
													</table>
												</li>
												<?php
											}
										 ?>
									   </ul>
									  </div>
									
									<?php 
								}
							?>
							<div style="clear:both;"></div>
							</div>
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

<?php 
//functions 
/**
 * 
 */





?>