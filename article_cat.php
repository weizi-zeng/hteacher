<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');

	$_REQUEST['id'] = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$cat_id     = $_REQUEST['id'];
	
	$_REQUEST['is_important'] = isset($_REQUEST['is_important']) ? intval($_REQUEST['is_important']) : -1;
	$is_important     = $_REQUEST['is_important'];
	
	$page_size = isset($_REQUEST['page_size']) ? intval($_REQUEST['page_size']) : 30;
	$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	$keyword = isset($_REQUEST['keyword']) ? trim($_REQUEST['keyword']) : '';
	
	$article_cat = get_articale_cat($cat_id);
	
	$title = $is_important>-1?"教育要闻":$article_cat['cat_name'];
	if($keyword){
		$title .= $keyword;
	}
	
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
						<div id="bannerCareers"><img src="images/education.jpg" width="620px" height="200px" alt=""></div>
						<!--banner结束-->
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<div id="containerM1">
							<div class="ariticle_cat_list_container" >
							
							<?php 
								$articles = array();
								
								if($is_important>-1){
									$articles = get_articles_list(0, $page_size, 1, $keyword);
								}else {
									$articles = get_articles_list($cat_id, $page_size, -1, $keyword);
								}
							?>
									<div >
									<div style="font-size: 14px;padding: 0 15px;font-weight: bold;"><?=$title ?></div>
										<ul class="right_list">
										<?
										foreach($articles['arr'] as $k=>$edu){
											?>
											<li>
												<a target="_blank" href="article.php?act=view&id=<?=$edu[article_id] ?>"><?=$edu[title] ?>  <?=$edu["add_time"] ?></a>
											</li>
											<?php
										}
										 ?>
									   </ul>
								  </div> 
								  
								  <div>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tbody><tr>
									<td height="20" align="left">
									<?php 
									if($page>1){
									?>
									<a href="article_cat.php?id=<?=$cat_id ?>&is_important=<?=$is_important ?>&keyword=<?=$keyword ?>&page_size=<?=$page_size ?>&page=1">首页</a>
									<a href="article_cat.php?id=<?=$cat_id ?>&is_important=<?=$is_important ?>&keyword=<?=$keyword ?>&page_size=<?=$page_size ?>&page=<?=$page>1?($page-1):$page ?>">上一页</a>
									<?php 
									}
									if($page<$articles['page_count']){
									?>
									<a href="article_cat.php?id=<?=$cat_id ?>&is_important=<?=$is_important ?>&keyword=<?=$keyword ?>&page_size=<?=$page_size ?>&page=<?=$page<$articles['page_count']?($page+1):$page ?>">下一页</a>
									<a href="article_cat.php?id=<?=$cat_id ?>&is_important=<?=$is_important ?>&keyword=<?=$keyword ?>&page_size=<?=$page_size ?>&page=<?=$articles['page_count'] ?>">末页</a>
									<?php 
									}
									?>
									&nbsp;<b><?=$page_size ?></b>条/页&nbsp;
									<b><font color="red"><?=$page ?></font></b>页/<b><?=$articles['page_count'] ?></b>页&nbsp;
									共<b><?=$articles['record_count'] ?></b>条记录
									</td></tr>
									</tbody></table>
								  </div>
								  
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
  
  
  <div id="toTop" class="toTop" >
  	置顶
  </div>
  <script type="text/javascript" >
		$(function(){
			$('#toTop').click(function(){
				 $('html,body').animate({
		                scrollTop: '0px'
		            },
		            800);
			});

			$('#toTop').hover(
			 	function () {
				    $(this).css({opacity:1});
				},
				function () {
				    $(this).css({opacity:0.5});
				}
			);
		});
		    
  </script>

 <?php 
	require_once 'themes/default/footer.htm';
?>

<?php 


/**
 * 获得指定的文章的详细信息
 *
 * @access  private
 * @param   integer     $article_id
 * @return  array
 */
function get_article_info($article_id)
{
	/* 获得文章的信息 */
	$sql = "SELECT a.* ".
            "FROM " .$GLOBALS['ecs']->table('article'). " AS a ".
            "WHERE a.is_open = 1 AND a.article_id = '$article_id' ";
	$row = $GLOBALS['db']->getRow($sql);

	if ($row !== false)
	{
		$row['date'] = local_date('Y-m-d H:i:s', $row['add_time']);
		/* 作者信息如果为空，则用网站名称替换 */
		if (empty($row['author']))
		{
			$row['author'] = $GLOBALS['_CFG']['shop_name'];
		}
	}

	return $row;
}

//获取文章类型
function get_articale_cat($cat_id)
{
	$sql = "select * from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$cat_id;
	$row = $GLOBALS['db']->getRow($sql);
	return $row;
}

function article_review_count_increment($article_id)
{
	$sql = "update " .$GLOBALS['ecs']->table('article'). " set view_count=view_count+1 where article_id=".$article_id;
	$GLOBALS['db']->query($sql);
}

?>