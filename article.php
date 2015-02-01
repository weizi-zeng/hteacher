<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');

	$_REQUEST['id'] = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$article_id     = $_REQUEST['id'];
	
	$article = get_article_info($article_id);
	$article_cat = get_articale_cat($article['cat_id']);
	
	$title = $article['title'].'_'.$article_cat['cat_name'];
	
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
							<?php 
								if($article_id>0){
									$id = $article_id;
									$row = $article;
									//浏览数字自动加一；
									$view_count = ++$row["view_count"];
									article_review_count_increment($article_id);
									
									if(empty($row) || $row["is_open"]!=1){
										echo('<font style="color:red;">您所有查看的文章不存在或者是被屏蔽</font>');
										
									}else {
										
										/* 上一篇下一篇文章 */
										$next_article = $db->getRow("SELECT article_id, title FROM " .$ecs->table('article'). " WHERE article_id > $id AND is_open=1 LIMIT 1");
											
										$prev_aid = $db->getOne("SELECT max(article_id) FROM " . $ecs->table('article') . " WHERE article_id < $id AND is_open=1");
										if (!empty($prev_aid))
										{
											$prev_article = $db->getRow("SELECT article_id, title FROM " .$ecs->table('article'). " WHERE article_id = $prev_aid");
										}
										
										?>
										<div style="border:4px solid #fcf8f7; background-color:#fff; padding:20px 15px;max-width:1200px;">
										<div class="tc" style="padding:8px;">
											<font class="f5 f6"><?=$row["title"] ?></font><br />
											<font class="f3">第<?=$row["view_count"] ?>次浏览 / <?=$row["date"] ?></font>
											</div>
											<?=$row["content"] ?>
											<div style="margin-top:20px;"></div>
											<div style="text-align:center">[<a href="javascript:window.close();">关闭窗口</a>]</div>
											<div style="padding:8px; margin-top:15px; text-align:left; border-top:1px solid #ccc;">
											<?php 
												if($prev_article){
													?>
													上一篇:<a href="article.php?act=view&id=<?=$prev_article[article_id] ?>" class="f6"><?=$prev_article["title"] ?></a><br />
													<?php 	
												}
												if($next_article){
													?>
													下一篇:<a href="article.php?act=view&id=<?=$next_article[article_id] ?>" class="f6"><?=$next_article["title"] ?></a><br />
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