<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');

	$title = "系统介绍";
	$msg = "";
	require_once 'themes/default/header.htm';
?>
  
  <div class="content" id="cont" style="overflow:hidden">
    <div class="left" style="overflow:hidden">
    	<div width="100%" width="674px">
			
			<form id="form1">
				<div class="left">
				 
				  <div class="left_box">
				  
				  
						<!--banner-->
						<div id="bannerCareers"><img src="images/bannerGYDH.jpg" alt=""></div>
						<!--banner结束-->
								
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<div id="containerM1">
							<p>您的支持，我们将做得更好！</p>
						
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