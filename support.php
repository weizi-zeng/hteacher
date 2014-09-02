<?php 
	define('IN_ECS', true);
	require (dirname(__FILE__) . '/includes/sinit.php');

	$act = empty($_REQUEST["act"])?"":trim($_REQUEST["act"]);
// 	$token = empty($_REQUEST["token"])?"0":intval($_REQUEST["token"]);
	$msg = "";
	if($act=='subMsg'){
		
		$recorder = empty($_REQUEST["recorder"])?"匿名":trim($_REQUEST["recorder"]);
		$e_mail = empty($_REQUEST["e_mail"])?"":trim($_REQUEST["e_mail"]);
		$title = empty($_REQUEST["title"])?"无标题":trim($_REQUEST["title"]);
		$content = empty($_REQUEST["content"])?"":trim($_REQUEST["content"]);
		
		$sql = "INSERT INTO " . $ecs->table("feedback") . "(parent_id, user_id, user_name, user_email, msg_title,
			                                                 msg_type, msg_content, msg_time, msg_status, msg_from)" .
			            " VALUES (0, 0, '$recorder', '$e_mail', ".
			            " '$title', 0, '$content', '" . gmtime() . "', '0', 'outer')";
		
		$db->query($sql);
		
		$msg = "信息已经成功提交，感谢您的支持！我们将尽快联系您！";
	}


	$title = "技术支持";
	require_once 'themes/default/header.htm';
?>
  
  <script type="text/javascript">
		function submsg(o){
			if(!$("#e_mail").val()){
				$.messager.alert('Warning',"邮箱不能为空！");
				$("#e_mail").focus();
				return false;
			}
			if(!$("#content").val()){
				$.messager.alert('Warning',"内容不能为空！");
				$("#content").focus();
				return false;
			}
			
			if ($("#subMsg_form").form('validate')) {
				return true;
			}
			return false;
		}
  </script>
  
  
  <div class="content" id="cont" style="overflow:hidden">
    <div class="left" style="overflow:hidden">
    	<div width="100%" width="674px">
			
				<div class="left">
				 
				  <div class="left_box">
				  
				  
						<!--banner-->
						<div id="bannerCareers"><img src="images/support.png" width="620px" height="200px" alt=""></div>
						<!--banner结束-->
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<form class="cmxform" id="subMsg_form" action="support.php"  method="post" enctype="multipart/form-data"  onsubmit="return submsg(this);">
						<div id="containerM1">
							
								<table width="100%" height="252" border="0" align="left" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="2" align="center" class="tab_list1"><h1 style="font-size:16px">留&nbsp;言&nbsp;板</h1></td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">您的称呼</td>
									<td class="tab_list3">
									  <input type="text" name="recorder" id="recorder" />
									</td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">联系电话</td>
									<td class="tab_list3">
									  <input type="text" name="phone" id="phone" maxlength="20"/>
									</td>
								  </tr>
								  <tr>
									<td width="20%" align="right" class="tab_list2">您的邮箱</td>
									<td width="80%" class="tab_list3">
									  <input type="text" name="e_mail" id="e_mail" class="easyui-validatebox" data-options="validType:'email'"/>
									  <span style="color:red;">*</span>
									</td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">留言标题</td>
									<td class="tab_list3">
									  <input type="text" name="title" id="title" size="50" />
									</td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">留言内容</td>
									<td class="tab_list3">
									  <textarea name="content" id="content" cols="45" rows="10"></textarea>
									  <span style="color:red;">*</span>
									</td>
								  </tr>
								  <tr>
									<td align="right">&nbsp;</td>
									<td height="40"><input type="submit" value="提 交" />
										<input type="reset" value="重 置" />
										<input type="hidden" name="act" value="subMsg" />
										</td>
								  </tr>
								</table>
						</div>
						</form>
						
						<div id="containerB"><img src="images/container_b.gif" alt="主要内容部分容器下边框"></div>
						</div>
						<!--主要内容部分容器结束-->


				  </div>
				   
				</div><!--left[end]-->
		
    	</div>
    </div>
	
		<?php 
	require_once 'themes/default/right.htm';
?>
	
  </div>

 <?php 
	require_once 'themes/default/footer.htm';
?>