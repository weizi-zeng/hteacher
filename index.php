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
						<!--<p>&nbsp;</p>-->
						<p>让我们对孩子的关注更快捷！更清晰！</p>
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