<?php 
	require_once 'themes/default/header.htm';
?>
  
  <div class="nav">
   <div class="nav_1">
      <a id="first_menu1" href="index.php">首页</a>
      <a id="first_menu2" href="introduction.php">系统介绍</a>
      <a id="first_menu3" href="contact.php">联系我们</a>
      <a id="first_menu4" href="support.php" style="color:black;">技术支持</a>
    </div>
  </div>
  
  <div class="content" id="cont" style="overflow:hidden">
    <div class="left" style="overflow:hidden">
    	<div width="100%" width="674px">
			
			<form id="form1">
				<div class="left">
				 
				  <div class="left_box">
				  
				  
						<!--banner-->
						<div id="bannerCareers"><img src="images/support.png" width="620px" height="200px" alt=""></div>
						<!--banner结束-->
								
								
						<!--主要内容部分容器开始-->
						<div id="container">
						<div id="containerT"><img src="images/container_t.gif" alt="主要内容部分容器上边框"></div>
			
						<div id="containerM1">
														
														
									<form  class="cmxform" id="messageform" name="form1" method="post" action="msgaction.jsp">

								<table width="100%" height="252" border="0" align="left" cellpadding="0" cellspacing="0">
								  <tr>
									<td colspan="2" align="center" class="tab_list1"><h1 style="font-size:16px">留&nbsp;言&nbsp;板</h1></td>
								  </tr>
								  <tr>
									<td width="20%" align="right" class="tab_list2">您的邮箱</td>
									<td width="80%" class="tab_list3"><label>
									  <input type="text" name="E_mail" id="e_mail" title="必填" class="required email"/>
									  *</label></td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">您的称呼</td>
									<td class="tab_list3"><label>
									  <input type="text" name="Recorder" id="textfield2" title="必填" class="required" />
									  *</label></td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">联系电话</td>
									<td class="tab_list3"><label>
									  <input type="text" name="Phone" id="phone"  />
									</label></td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">留言标题</td>
									<td class="tab_list3"><label>
									  <input type="text" name="Title" id="title"  title="必填" class="required"size="50" />
									  *</label></td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">留言内容</td>
									<td class="tab_list3"><label>
									  <textarea name="Content" id="Content" cols="45" rows="10"  title="必填" class="required"></textarea>
									  *</label>
									</td>
								  </tr>
								  <tr>
									<td align="right" class="tab_list2">验证码</td>
									<td class="tab_list3"><input name="rand" type="text" size="6" title="必填" class="required"/><img border=0 src="image.jsp" id="randimage">* </td>
								  </tr>
								  <tr>
									<td align="right">&nbsp;</td>
									<td height="40"><input type="submit" name="Submit2" value="提 交" />
										<input type="reset" name="Submit" value="重 置" /></td>
								  </tr>
								</table>
								<div id="result" ></div>
										  <input type="hidden" name="mark" value="中文"/>
									</form>
									<!--<button id="reset">清空</button>-->
									
						
						
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