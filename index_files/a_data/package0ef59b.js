$(function() {
	
	
	
	$(".mbr_de_pic").hover(function() {
            $(".pic_edit").show();
        },
        function() {
            $(".pic_edit").hide();
        });
	
	
	
	
	$("a[ck='sellistNum']").live("click",function(){
		var listNum = $("#listNum").val();
		location.href=window.location+"&listNum=" + listNum;
	}); 
	
	
	
	
	  $("#_pANelmAsk_").live("click",function(){
		 	 var a = $(this);
			 if(a.find("div").hasClass('DG')){
				 
			 }else{
				 
			 a.html('');
			 
			 }
		   }); 
		   
	$("a[ck='cancel']").live("click",function(){

			$("#_pANelmAsk_").html('');
			
		   }); 
		   
	$("a[dlg='close']").live("click",function(){

			$("#_pANelmAsk_").html('');
			
		   }); 
		   
	$("a[ck='submit']").live("click",function(){
		
		if(this.getAttribute("ty")=='cb'){
			
			bid_0 =$("#structList").find("a").eq(0).attr("bid");
			
			bname_0 =$("#structList").find("a").eq(0).attr("bname");
			
			
			
			//alert(bid);
			
			$A_0 = '<div title="" class="addr_base addr_normal "> <b addr="" bid="'+bid_0+'">'+bname_0+'</b> <a href="javascript:;" class="addr_del" ck="delSelectedItem" bid="'+bid_0+'"></a> <input type="hidden" name="bid" value="'+bid_0+'"></div>';
			
			
			if($("#structList").find("a").eq(1).attr("bid")){
				bid_1 =$("#structList").find("a").eq(1).attr("bid");
				bname_1 =$("#structList").find("a").eq(1).attr("bname");
				$A_1 = '<div title="" class="addr_base addr_normal "> <b addr="" bid="'+bid_1+'">'+ bname_1 +'</b> <a href="javascript:;" class="addr_del" ck="delSelectedItem" bid="'+bid_1+'"></a> <input type="hidden" name="bid" value="'+bid_1+'"></div>';
				
				$("#selectedDeptFill").html('');
				$("#selectedDeptFill").append($A_0+$A_1);
				
			}else{
				
				$("#selectedDeptFill").html('');
				if(bid_0)$("#selectedDeptFill").append($A_0);
			}
			
			$("#_pANelmAsk_").html('');
			
			
		}
		
		
		 document.form2.submit();
		
	 }); 
	 
	 $("a[ck='csubmit']").live("click",function(){
		
		if(this.getAttribute("ty")=='cb'){
			
			Km = KmCc();
			
			var idarr=(typeof Km=="string")?Km:Km.join(",");
		//showMsg(hi);
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'add_subject',
					  
					  idarr:idarr
					  
				   },
				   success: function(data){
					   var $json = data;
					   switch($json.status){
					   case(1):
							showMsg($json.msg);
					   break;
					   case(0):
							showErr($json.msg);
					   break;
					   }
				   }
		});
			
			
			
			
		
			
			$("#_pANelmAsk_").html('');
			
			
		}
		
		
		 document.form2.submit();
		
	 }); 
	 
	 $("span[ck='del']").live("click",function(){
		
		  var a = $(this).parent("a");
		  a.remove();
		
	 }); 
	 
	 $("#iscpwdlogin").live("click",function(){
		
		   var value = $(this).attr("value");
		   if(value==1)  $(this).attr("value",0);
		   if(value==0)  $(this).attr("value",1);
		
	 }); 
	 
	  $("a[ck='delSelectedItem']").live("click",function(){
		
		  var a = $(this).parent("div");
		  var bid = $(this).attr("bid");
		  var b = $("#a_"+bid);
		  
		  a.remove();
		  
		  b.remove();
		
	 }); 
	 
	  $("a[un='item']").live({
		  mouseenter:
		   function()
		   {
			$(this).addClass("selected2");
			$(this).find("span").show();
		   },
		   mouseleave:
		   function()
		   {
			$(this).removeClass("selected2");
			$(this).find("span").hide();
		   }
   });
		 
	$("input[ck='select']").live("click",function(){
			  var a=$(this);
			  var bid = this.getAttribute("bid");
			  var bname = this.getAttribute("bname");
			  
			  if(this.checked){
				  
				  $isa = $("#a_"+bid).html();
				  
				 	 $("#structContent").find("input").each(function(){
					   $(this).attr('disabled');
					  });

			  		if($isa){
						
					}else{
					
					
					$A='<a href="javascript:;" icon="party" bname="'+bname+'" bid="'+bid+'" id="a_'+bid+'">'+ bname +'<span class="addr_del" ck="del"></span></a>';
					
					$("#structList").append($A);
						
						
					}
			  
			  
			  }else{
				  
				  $("#a_"+bid).remove();
				  
			  }
			  
			  
	 
	 	 }); 
		 
	
	$("input[ck='cselect']").live("click",function(){
			  var a=$(this);
			  var selectid = this.getAttribute("selectid");
			  var selectname = this.getAttribute("selectname");
			  
			  if(this.checked){
				  
				  $isa = $("#a_"+selectid).html();
				  
				 	 $("#structContent").find("input").each(function(){
					   $(this).attr('disabled');
					  });

			  		if($isa){
						
					}else{
					
					
					$A='<a href="javascript:;" icon="party" selectname="'+selectname+'" selectid="'+selectid+'" id="a_'+selectid+'">'+ selectname +'<span class="addr_del" ck="del"></span></a>';
					
					$("#structList").append($A);
						
						
					}
			  
			  
			  }else{
				  
				  $("#a_"+selectid).remove();
				  
			  }
			  
			  
	 
	 	 }); 	 
	
	
		 
	 $("li[opt='del']").live("click",function(){
		 
		var Bid = this.getAttribute("bid");
		$("#_pANelmAsk_").html('');
		if(confirm('您确定要删除此部门吗？')){
			if(Bid>0){
			 
			Branck(Bid,'del_branch');
		
			}
		}
		 
	 }); 
	 
	 $("a[ck='sendmark']").live("click",function(){
		 
		var send_data = this.getAttribute("send_data");
		var smscontent = $("#smscontent").val();
		var send_type = this.getAttribute("send_type");
		var authcode ='';
		
		if(send_type>0){
			
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'send_mark',
					  
					  send_data:send_data,
					  
					  send_type:send_type,
					  
					  smscontent:smscontent,
					  
					  authcode:authcode
				   },
				   beforeSend:function(){
					   
					    $("a[ck='sendmark']").html("正在发送...");
					   
				   },
				   success: function(data){
					   var $json = data;
					   
					   switch($json.status){
					   case(1):
							showMsg($json.msg);
					   break;
					   case(0):
							showErr($json.msg,'1500');
					   break;
					   }
				   }
				});
			
		}else if(send_type==0){
			
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'send_mark',
					  
					  send_data:send_data,
					  
					  send_type:send_type,
					 
					  smscontent:smscontent,

					  authcode:authcode
				   },
				   success: function(data){
					   var $json = data;
					  
					   switch($json.status){
					   case(1):
							showMsg($json.msg);
					   break;
					   case(0):
							showErr($json.msg,'1500');	
					   break;
					   }
				   }
				});
		}else{
			
		showErr("请录入成绩后发送！",'1500');	
		}
		 
	 }); 
	 
	 
	 
	  $("li[opt='rename']").live("click",function(){
		  
		var bid = this.getAttribute("bid");
		var bname = trim(this.getAttribute("bname"));
		$("#_pANelmAsk_").html('');

			if(bid>0){
			 
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_branch"><input type="hidden" name="btype" value="system"><input type="hidden" name="bid" value="'+ bid +'"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">编辑部门</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="labelText left">部门名称：</div><input name="bname" type="text" class="txt" style="width:240px;" value="'+ bname +'" maxlength="16"></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);

		}
		 
	 }); 
		   
		   
	$("li[opt='new']").live("click",function(){
		
		$("#_pANelmAsk_").html('');
		var pid = this.getAttribute("pid");
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_branch"><input type="hidden" name="pid" value="'+ pid +'"><input type="hidden" name="btype" value="system"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">新建部门</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="labelText left">部门名称：</div><input name="bname" type="text" class="txt" style="width:240px;" value="" maxlength="16"></div><div class="mainColumn" style="padding:0 0 30px 0;"><div class="labelText left">部门分类：</div><select class="txt" name="btype" style="height:26px;width:242px; line-height:26px;"><option value="jiaoyuju">------------教育局------------</option><option value="school">------------学校（School）------------</option><option value="grade">------------年级（Grade）------------</option><option value="class">------------班级（Class）------------</option><option value="group">------------小组（Group）------------</option><option value="other">------------其他（Other）------------</option></select></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		 
		 
		 $("a[cmdarg='rename']").live("click",function(){
			 
		var name = this.getAttribute("dirname");
		var fid = this.getAttribute("fid");
		var authcode = this.getAttribute("authcode");
		$("#_pANelmAsk_").html('');
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_folder"><input type="hidden" name="fid" value="'+fid+'"><input type="hidden" name="authcode" value="'+authcode+'"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">修改文件夹</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入文件夹名称</label><input type="text" id="txt" name="name" class="dialog_input" value="'+name+'"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		 
		  $("a[cmdarg='create']").live("click",function(){
		
		$("#_pANelmAsk_").html('');
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_folder"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">新建文件夹</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入文件夹名称</label><input type="text" id="txt" name="name" class="dialog_input" value=""></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		 
		  $("a[cmdarg='create_exam']").live("click",function(){
		
		$("#_pANelmAsk_").html('');
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_exam"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">添加考试</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="labelText left">考试名称：</div><input name="examname" type="text" class="txt" style="width:240px;" value="" maxlength="16"></div><div class="mainColumn" style="padding:0 0 30px 0;"><div class="labelText left">考试类型：</div><select class="txt" name="examtypeid" style="height:26px;width:242px; line-height:26px;"><option value="0">------------ 请选择  ------------</option>' + examtype + '</select></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		//author：huchengjun 2012-11-14 start
		$("a[cmdarg='create_work']").live("click",function(){
		
		$("#_pANelmAsk_").html('');
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_work"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">添加作业</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="labelText left">作业名称：</div><input name="homeworkname" type="text" class="txt" style="width:240px;" value="" maxlength="16"></div><div class="mainColumn" style="padding:0 0 30px 0;"><div class="labelText left">作业类型：</div>' + selecthtml + '</div><div class="mainColumn" style="padding:0 0 30px 0;"><div class="labelText left">作业内容：</div><textarea style="width:240px; height:100px;" name="content"></textarea></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		//end 
		
		//author: huchengjun 2012-11-15 STRAT
		$("a[cmdarg='del_homework']").live("click",function(){
		var homeworkid = this.getAttribute("homeworkid");
		var authcode = this.getAttribute("authcode");
		if(confirm("您确定要删除吗，如不确定请点取消")){
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'del_homework',
					  
					  homeworkid:homeworkid,
					  
					  authcode:authcode
				   },
				   success: function(data){
					   var $json = data;
					   
					   switch($json.status){
					   case(1):
							showMsg($json.msg);
					   break;
					   case(0):
							showErr($json.msg);
					   break;
					   }
				   }
				});
			}
		});
		 
		//END
		//author: huchengjun 2012-11-15 STRAT
		$("a[cmdarg='rename_homework']").live("click", function(){
			var homeworkid = this.getAttribute("homeworkid");
			var homeworkname = this.getAttribute("homeworkname");
			var authcode = this.getAttribute("authcode");
			$("#_pANelmAsk_").html('');
			$html = '<div class="DG"><form name="form2" action="' + TJ.path + 'index.php?mod=' + TJ.mod + '&do=do" method="POST"><input type="hidden" name="action" value="rename_homework"><input type="hidden" name="homeworkid" value="' + homeworkid + '"><input type="hidden" name="authcode" value="' + authcode + '"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">重命名作业</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入作业名称</label><input type="text" id="txt" name="homeworkname" class="dialog_input" value="' + homeworkname + '"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div></form></div>';
			DG($html);
		});
		//END
		 
		 $("a[opercmd='rename']").live("click",function(){
			 
		var filename = this.getAttribute("filename");
		var fileid = this.getAttribute("fileid");
		var authcode = this.getAttribute("authcode");
		var fid = this.getAttribute("fid");
		$("#_pANelmAsk_").html('');
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="update_file"><input type="hidden" name="fid" value="'+fid+'"><input type="hidden" name="fileid" value="'+fileid+'"><input type="hidden" name="authcode" value="'+authcode+'"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">修改文件名</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入新的文件名</label><input type="text" id="txt" name="filename" class="dialog_input" value="'+filename+'"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		 
		 $("a[cmdarg='delfolder']").live("click",function(){
			 
		var fid = this.getAttribute("fid");
		var authcode = this.getAttribute("authcode");
		if(confirm("您确定要删除吗，如不确定请点取消")){
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'del_folder',
					  
					  fid:fid,
					  
					  authcode:authcode
				   },
				   success: function(data){
					   var $json = data;
					   
					   switch($json.status){
					   case(1):
							showMsg($json.msg);
					   break;
					   case(0):
							showErr($json.msg);
					   break;
					   }
				   }
				})
			}
		
		 });
		
		//删除考试
		$("a[cmdarg='delexam']").live("click",function(){
			var examid = this.getAttribute("examid");
			var authcode = this.getAttribute("authcode");
			if(confirm("您确定要删除吗？如不确定请点击取消")){
				$.ajax({
					url: TJ.path + "index.php?mod=" + TJ.mod + "&do=do",
					type: "post",
					dataType: "json",
					data:{
						action: 'del_exam',
						examid: examid,
						authcode: authcode
					},
					success: function(data){
						var $json = data;
						switch($json.status){
							case(1):
								showMsg($json.msg);
								break;
							case(0):
								showErr($json.msg);
								break;
						}
					}
				})
			}
		});
		
		//zhang
		$("a[cmdarg='rename_exam']").live("click", function(){
			var name = this.getAttribute("examname");
			var examid = this.getAttribute("examid");
			var authcode = this.getAttribute("authcode");
			$("#_pANelmAsk_").html('');
			$html = '<div class="DG"><form name="form2" action="' + TJ.path + 'index.php?mod=' + TJ.mod + '&do=do" method="POST"><input type="hidden" name="action" value="add_exam"><input type="hidden" name="examid" value="' + examid + '"><input type="hidden" name="authcode" value="' + authcode + '"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">修改文件夹</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入考试名称</label><input type="text" id="txt" name="examname" class="dialog_input" value="' + name + '"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div></form></div>';
			DG($html);
		});
		//zhang
		
		$("a[cmdarg='rename']").live("click",function(){
			 
		var name = this.getAttribute("dirname");
		var fid = this.getAttribute("fid");
		var authcode = this.getAttribute("authcode");
		$("#_pANelmAsk_").html('');
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_folder"><input type="hidden" name="fid" value="'+fid+'"><input type="hidden" name="authcode" value="'+authcode+'"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">修改文件夹</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入文件夹名称</label><input type="text" id="txt" name="name" class="dialog_input" value="'+name+'"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div></form></div>';
		DG($html);
		
		 });
		 
		 
		 $("a[opt='helpcat']").live("click",function(){
		
		$("#_pANelmAsk_").html('');
		var cid = this.getAttribute("cid");
		var title = this.getAttribute("title");
		c = cid>0?'updatehelpcat':'addhelpcat';
		text = cid>0?'修改分类':'添加分类';
		input_catid = cid>0?'<input type="hidden" name="catid" value="'+cid+'">':'';
		$html = '<div class="DG"> <form name="form2" action="'+ TJ.path +'index.php?mod=system&do=help&c='+c+'" method="POST">'+input_catid+'<div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">'+text+'</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="labelText left">分类名称：</div><input name="name" type="text" class="txt" style="width:240px;" value="'+title+'" maxlength="16"></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
		DG($html);
		
		 });
		 
		 $("li[opt='export']").live("click", function(){
			 window.open(TJ.path + "index.php?mod="+TJ.mod+"&do=member&d=export_excel");
		 });
		 
		 $("input[opt='export_excel']").live("click", function(){
			 var examid = this.getAttribute("examid");
			 window.open(TJ.path + "index.php?mod="+TJ.mod+"&do=exam&c=export_excel&exam_id=" + examid);
		 });		 
		 
		 $("a[ck='addDeptDlg']").live("click",function(){
		
			$("#_pANelmAsk_").html('');
			
			if($("#selectedDeptFill").find("b").eq(0).html()){
			
			bname_0 =$("#selectedDeptFill").find("b").eq(0).html();
			
			bid_0 =$("#selectedDeptFill").find("input").eq(0).attr("value");
			
			$A_0 ='<a href="javascript:;" icon="party" bname="'+trim(bname_0)+'" bid="'+bid_0+'" id="a_'+bid_0+'">'+ trim(bname_0) +'<span class="addr_del" ck="del"></span></a>';
			
			if($("#selectedDeptFill").find("b").eq(1).html()){
			
				bname_1 =$("#selectedDeptFill").find("b").eq(1).html();
				
				bid_1 =$("#selectedDeptFill").find("input").eq(1).attr("value");
				
				$A_1 ='<a href="javascript:;" icon="party" bname="'+trim(bname_1)+'" bid="'+bid_1+'" id="a_'+bid_1+'">'+ trim(bname_1) +'<span class="addr_del" ck="del"></span></a>';
				
				//alert($A_1);
				
				$("#structList").html($A_0+$A_1);
			
			}else{
				
				$("#structList").html($A_0);	
			}
			
			}
			
			$html =  trim($("#Cbranch").html());
			DG($html);
		
		 });
		 
		 
		 //Edit from zhaobin 10:31 2012/11/14 START
		 	/*
		 	$("a[ck='addSubject']").live("click",function(){
		 		$("#_pANelmAsk_").html('');
		 		if($("#selectedDeptFill").find("s").eq(0).html()){
		 			sname_0 =$("#selectedDeptFill").find("s").eq(0).html();
		 			sid_0 =$("#selectedDeptFill").find("input").eq(0).attr("value");
		 			$A_0 ='<a href="javascript:;" icon="party" sname="'+trim(sname_0)+'" sid="'+sid_0+'" id="a_'+sid_0+'">'+ trim(sname_0) +'<span class="addr_del" ck="del"></span></a>';
		 			if($("#selectedDeptFill").find("s").eq(1).html()){
		 				sname_1 =$("#selectedDeptFill").find("s").eq(1).html();
		 				sid_1 =$("#selectedDeptFill").find("input").eq(1).attr("value");
		 				$A_1 ='<a href="javascript:;" icon="party" sname="'+trim(sname_1)+'" sid="'+sid_1+'" id="a_'+sid_1+'">'+ trim(sname_1) +'<span class="addr_del" ck="del"></span></a>';
		 				$("#structList").html($A_0+$A_1);
		 			}else{
		 				$("#structList").html($A_0);	
		 			}
		 		}
			
		 		$html =  trim($("#CSubject").html());
		 		DG($html);
		 	});
		 	*/
		 
		 	$("a[ck='addSubject']").live("click",function(){
		 		$("#_pANelmAsk_").html('');
				$html = '<div class="DG"><form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_subject_item"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">新增科目</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入科目名称</label><input type="text" id="txt" name="subjectname" class="dialog_input" value=""></div><div class="f_size"><label for="txt">请您输入科目排序(0-99,数字越大排序越前)</label><input type="text" id="txt" name="sort" class="dialog_input" value="0"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
				DG($html);
		 	});
		//Edit from zhaobin 10:31 2012/11/14 END
		
		// Edit from zhaobin 14:14 2012/11/14 START
		 	$("a[ac='editsubjectitem']").live("click",function(){
		 		var subjectname = $(this).attr('subjectname');
		 		var sid = $(this).attr('sid');
		 		var sort = $(this).attr('sort');
		 		$("#_pANelmAsk_").html('');
				$html = '<div class="DG"><form name="form2" action="'+ TJ.path +'index.php?mod='+ TJ.mod +'&do=do" method="POST"><input type="hidden" name="action" value="add_subject_item"><div dlg="mask" class="detail_mask" style="z-index: 1007; height: 1171px; "></div><div dlg="panel" class="opp_obj" style="width: 400px; z-index: 1008; "><div un="dlg" class="o_body rounded5"><strong dlg="title" class="o_title" style="">新增科目</strong><a dlg="close" href="javascript:;" class="ico_close"></a><div dlg="content" class="o_con"><div><div un="dlg"><div class="mainColumn"><div class="f_size"><label for="txt">请您输入科目名称</label><input type="hidden" value="' + sid + '" name="sid" /><input type="text" id="txt" name="subjectname" class="dialog_input" value="' + subjectname + '"></div><div class="f_size"><label for="txt">请您输入科目排序(0-99,数字越大排序越前)</label><input type="text" id="txt" name="sort" class="dialog_input" value="' + sort + '"></div></div><div class="submitColumn btnColumn"><a ck="cancel" class="btn btnNormal btnCancel right">取消</a><a ck="submit" class="btn btnComfirm right">确认</a></div></div></div></div><div class="t_mask"></div></div></div>  </form></div>';
				DG($html);
		 	});
		 	
		 	$("a[ac='delsubjectitem']").live("click",function(){
		 		var sid = $(this).attr('sid');
		 		if(confirm("您确定要删除吗，如不确定请点取消")){
					$.ajax({
						url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
						type : "post",
						dataType: "json",
						data: {
							action : 'del_subject_item',
							sid: sid
						}
					});
					location.reload();
		 		}
			});
		// Edit from zhaobin 14:14 2012/11/14 END
	
	
	
	 $("#structNode").live("click",function(){
				var a = $(this);
				var bid = this.getAttribute("bid");
				var self_bid = this.getAttribute("self_bid");
				var bname = this.getAttribute("bname");
				if(bid==1||bid==self_bid){
					var del ='';
				}else{
					
					var del ='<li opt="del" bid="'+ bid +'"><a>删除</a></li>';
				}
				var html= '<div class="m_list"><ul><li opt="rename"  bid="'+ bid +'" bname="'+ bname +'"><a>重命名</a></li>'+ del +'<p class="line"></p><li opt="new" pid="'+ bid +'"><a>新建部门</a></li><li opt="export" pid="'+ bid +'"><a>下载人员数据</a></li></ul><div></div></div>';
				PA(a,html);
				
				
				
	});
	 $("span[ck='showUpDownMenu']").live("click",function(e){
		 var a = $(this);
		 stopDefault(e); 
		a.stopPropagation();
	
	});
	
	
	 $("a[ck='selCondition']").live("click",function(){
		 
				var a = $(this);
				var Ms=$("#M_show").html();
				PA(a,Ms);
				
				
				
	});
	
	
	
	function PA(a,html){
		
		$h = a.height();

		$top = a.offset().top + $h;
		
		$left = a.offset().left;
		if($("#_pANelmAsk_").length){}else{$("body").prepend('<div id="_pANelmAsk_"></div>');}
		
		$offsetHtml = '<div><div class="detail_mask_w" style="z-index: 1003; "></div><div class="menu_obj" style="z-index: 1004; position: absolute; left: '+$left+'px; top: '+$top+'px; "><div class="m_body rounded3" style="overflow-x: hidden; overflow-y: auto; "><div>'+ html +'</div></div></div></div></div></div>';
		
		$("#_pANelmAsk_").html($offsetHtml);
		
	
	}
	
	
	function DG(html){
		if($("#_pANelmAsk_").length){}else{$("body").prepend('<div id="_pANelmAsk_"></div>');}
		$offsetHtml = '';
		
		$("#_pANelmAsk_").html(html);
		
	
	}

	 $("#memberdetail").live("click",function(){
				var a = $(this);
				$("#moredetail").show();
				a.hide();
				
	});
	
	 $("#checkAll").live("click",function(){
		 var a = $(this);
		 var ischecked = a.attr("checked");
		
		if(ischecked == "checked"){
			 $("table").find("thead").find("a[sh='dis']").removeClass("btn_disabled");
			 $("tr").find("input[un='mbrCheck']").attr("checked","checked");
			
		 }else{
			 $("table").find("thead").find("a[sh='dis']").addClass("btn_disabled");
			 $("tr").find("input[un='mbrCheck']").attr("checked",false);
		 }
	

	});
	
	//菜单停用
	$("input[un='isclosed']").live("click",function(){
		 var a = $(this);
		 var isclosed = a.attr("value");
		 var menuid = a.attr("menuid");
		 
				$.ajax(
 					{
 						url: TJ.path + "index.php?mod=system&do=do",
 						type: "post",
 						dataType: "json",
 						data:
 							{
 								action: 'isclosed_menu',
								menuid: menuid,
 								isclosed: isclosed
 							},
 						success: function(data){
 							var $json = data;
 							switch($json.status){
 								case(1):
 									showMsg($json.msg);
 									break;
 								case(0):
 									showErr($json.msg);
 									break;
 							}
 						}
 					}
 				)
			
			 //$("table").find("thead").find("a[sh='dis']").removeClass("btn_disabled");
			// $("tr").find("input[un='mbrCheck']").attr("checked","checked");

	});
	
	$("#sql_checkAll").live("click", function(){
		var a = $(this);
		var ischecked = a.attr("checked");
		if(ischecked == 'checked'){
			$("tr").find("input[id='sql_jxt']").attr("checked", "checked");
		}else{
			$("tr").find("input[id='sql_jxt']").attr("checked", false);
		}
	});
	$("#sql_checkAll2").live("click", function(){
		$("tr").find("input[id='sql_jxt']").attr("checked", "checked");
	});
	$("#sql_resetAll2").live("click", function(){
		$("tr").find("input[id='sql_jxt']").attr("checked", false);
	});
	
	$("#del_sqlfile").live("click", function(){
		var arr = new Array();
 		var i = 0;
 		$('input[name="filenames[]"]').each(function(){
 			if(($(this)).attr('checked')=='checked'){
 				arr[i] = $(this).val();
 				i++;
 			}
 		});
 		if(i == '0'){
 			showMsg('请选择要删除的文件');
 		}
 		if(confirm('确定要删除选中的备份文件吗？')){
 			var string = arr.join();
 	 		$.ajax(
 					{
 						url: TJ.path + "index.php?mod=" + TJ.mod + "&do=do",
 						type: "post",
 						dataType: "json",
 						data:
 							{
 								action: 'del_sqlfile',
 								files: string
 							},
 						success: function(data){
 							var $json = data;
 							switch($json.status){
 								case(1):
 									location.replace(location.href);
 									break;
 								case(0):
 									showErr($json.msg);
 									break;
 							}
 						}
 					}
 				)
 		}
	});
	
	 
	$("#cbSelectAll").live("click",function(){
		 var a = $(this);
		 var ischecked = a.attr("checked");
		
		if(ischecked == "checked"){
			 $("tr").find("input[un='mbrCheck']").attr("checked","checked");
			 $("tr").css("background-color","#F4F7FA");
		 }else{
			 $("tr").find("input[un='mbrCheck']").attr("checked",false);
			 $("tr").css("background-color","");
		 }
	

	});
	
	//下载次数
	$("a[cmdarg='down_count']").live("click",function(){
		 var fileid = this.getAttribute("fileid");
		 $.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'down_count',
					  
					  fileid:fileid
				   },
				   success: function(data){
					   

				   }
		});
		
		$("#down_count").html(parseInt($("#down_count").html())+1);
		
		
	

	});
	
	//删除下载文件
	$("a[opercmd='delfile']").live("click",function(){
			 
			 var a = $(this);
			 var fileid = this.getAttribute("fileid");
			 var authcode = this.getAttribute("authcode");
			 if(fileid>0){
				 if(confirm("您确定要删除这个文件吗？")) bUB(fileid,authcode,'del_file');
				
			 }else{
			 	iS=bBH();
				CODE = getauthcode();
				conLan = "您确定要删除这 "+iS.length+" 个文件吗？";

				if(!iS.length)
					{
					return showErr("您没有选择任何文件");
					}
					else if(confirm(conLan))
					{
					bUB(iS,CODE,'del_file');
					}
			 }
								 
		});
	
	/* Edit by zhaobin 2012/11/19 14:40
	 $("input[un='mbrCheck']").live("click",function(){
		 
		 var a = $(this);
		 var ischecked = a.attr("checked");
		 
		if(ischecked == "checked"){
			 $("table").find("thead").find("a[sh='dis']").removeClass("btn_disabled");
		 }else{
			 $("table").find("thead").find("a[sh='dis']").addClass("btn_disabled");
		 }
		 });
	*/
	$("input[un='mbrCheck']").live("click", function(){
		var is='0';
		 $('input[name="users[]"]').each(function(){
		 	if(($(this)).attr('checked')=='checked'){
		 		is='1';
		 		return;
		 	}
		 });
		 if(is=='1'){
			 $("table").find("thead").find("a[sh='dis']").removeClass("btn_disabled");
			  iS=bBH();
			 $("#t_checked").html("已选 "+iS.length+"个");
		 }else{
			 $("table").find("thead").find("a[sh='dis']").addClass("btn_disabled");
		 }
	});
	
	$("a[ac='save_import_data']").live("click", function(){
		var save_import_data = this.getAttribute("import_data");
		var bid = this.getAttribute("bid");
		if(confirm("您确定要将数据存入数据库吗？")){
			
			$.ajax(
				{
					url: TJ.path + "index.php?mod=" + TJ.mod + "&do=do",
					type: "post",
					dataType: "json",
					data:
						{
							action: 'save_import_data',
							import_data: save_import_data,
							bid: bid
						},
					success: function(data){
						var $json = data;
						switch($json.status){
							case(1):
								location.href = TJ.path + "index.php?mod=" + TJ.mod + "&do=member";
								break;
							case(0):
								showErr($json.msg);
								break;
						}
					}
				}
			)
		}
	});
	
	$("a[ck='database_backup']").live("click", function(){
		$.ajax(
				{
					url: TJ.path + "index.php?mod=" + TJ.mod + "&do=do",
					type: "post",
					dataType: "json",
					data:
						{
							action: 'database_backup'
						},
					success: function(data){
						var $json = data;
						switch($json.status){
							case(1):
								showMsg('成功更新数据库备份');
								break;
							case(0):
								showErr($json.msg);
								break;
						}
					}
				}
			)
	});
	
	$("a[ck='database_restore']").live("click", function(){
		if(confirm("还原之前请做好相关数据备份")){
			showMsg('正在还原中，请稍候...', '10000000');
			var file = $(this).attr('file');
			$.ajax(
					{
						url: TJ.path + "index.php?mod=" + TJ.mod + "&do=do",
						type: "post",
						dataType: "json",
						data:
							{
								action: 'database_restore',
								file: file
							},
						success: function(data){
							var $json = data;
							switch($json.status){
								case(1):
									showMsg($json.msg);
									break;
								case(0):
									showErr($json.msg);
									break;
							}
						}
					}
				)
		}
	});
	
	$("a[ac='save_import_data_mark']").live("click", function(){
		var save_import_data = this.getAttribute("import_data");
		var bid = this.getAttribute("bid");
		var examid = this.getAttribute('examid');
		if(confirm("您确定要将数据存入数据库吗？")){
			$.ajax(
				{
					url: TJ.path + "index.php?mod=" + TJ.mod + "&do=do",
					type: "post",
					dataType: "json",
					data:
						{
							action: 'save_import_data_mark',
							import_data: save_import_data,
							examid: examid,
							bid: bid
						},
					success: function(data){
						var $json = data;
						switch($json.status){
							case(1):
								location.href = TJ.path + "index.php?mod=" + TJ.mod + "&do=exam&c=index";
								break;
							case(0):
								showErr($json.msg);
								break;
						}
					}
				}
			)
		}
	});
		 
	 $("a[ck='mbrDel']").live("click",function(){
			 
			 var a = $(this);
			 if(a.hasClass("btn_disabled"))
				{
				return;
				}
			 	 if(this.getAttribute("uid"))
				{
					
				iS=this.getAttribute("uid");
				CODE=this.getAttribute("authcode");
				conLan = "您确定要删除此用户吗？";
				var url = TJ.path+'index.php?mod='+ TJ.mod +'&do=member';
				}else{
				
			 	iS=bBH();
				CODE = getauthcode();
				conLan = "您确定要删除这 "+iS.length+" 个成员吗？\n删除成员将会清空帐号下的所有数据。";
				var url = '';
				}
				
				if(!iS.length)
					{
					return al.showErr("您没有选择任何成员");
					}
					else if(confirm(conLan))
					{
					bUB(iS,CODE,'del_member','',url);
					}
								 
			  });

	 	$("a[ck='sendmsg']").live("click", function(){
	 		var arr = new Array();
	 		var i = 0;
	 		$('input[name="users[]"]').each(function(){
	 			if(($(this)).attr('checked')=='checked'){
	 				arr[i] = $(this).val();
	 				i++;
	 			}
	 		});
	 		var string = arr.join();
	 		location.href=TJ.path + "?mod="+TJ.mod+"&do=setinfo&c=sendmsg&recvuid=" + string;
	 	});
		
		
		$("a[ck='sendsms']").live("click", function(){
	 		var arr = new Array();
	 		var i = 0;
	 		$('input[name="users[]"]').each(function(){
	 			if(($(this)).attr('checked')=='checked'){
	 				arr[i] = $(this).val();
	 				i++;
	 			}
	 		});
	 		var string = arr.join();
	 		location.href=TJ.path + "?mod="+TJ.mod+"&do=setinfo&c=sendsms&recvuid=" + string;
	 	});
		
		
			  
		$("a[ck='isbaned']").live("click",function(){
			 
			 var a = $(this);
			 var isbaned = this.getAttribute("isbaned");
			 if(isbaned==1)
			 	var Li ="启用";
			else
				var Li ="禁用";
			 if(a.hasClass("btn_disabled"))
				{
				return;
				}
			 if(this.getAttribute("uid"))
				{

				iS=this.getAttribute("uid");
				CODE = this.getAttribute("authcode");
				conLan = "您确定要"+ Li +"此用户吗？";
				}else{
				
			 	iS=bBH();
				CODE = getauthcode();
				conLan = "您确定要"+ Li +"这 "+iS.length+" 个成员吗？";
				}
				if(!iS.length)
					{
					return al.showErr("您没有选择任何成员");
					}
					else if(confirm(conLan))
					{
					bUB(iS,CODE,'isbaned_member',isbaned);
					}
								 
			  });
			  
			  
			  $("ins[class='icon']").live("click",function(){
				 
				   var a = $(this).parent().parent("li[un='node']");
				   
				   var Ulnull = a.find('ul').html();
				   
				   var $bid = this.getAttribute("bid");
				
				   var ty = this.getAttribute("ty");
				   
				  //alert(ty);
				  $.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : 'get_branchhtml',

					  bid:$bid,
					  
					  ty:ty,
					  
					  c:'sch'
				   },
				   success: function(data){
					   var $json = data;
					   switch($json.status){
					   case(1):
					   
					   At = trim($json.html);
					   
					    if(a.hasClass('open')){
					  
					if(Ulnull==null){
						
						
						//showMsg(At);
						a.append(At);
					}
					  
					if(a.hasClass("last")){
						a.removeClass("open");
						a.removeClass("last");
						a.addClass("closed last");
					}else{
					
						a.removeClass("open");
						a.addClass("closed");	
						
					}
				  }
				  else{
					  
					  if(Ulnull==null){
						
						//showMsg(At);
						a.append(At);
					}
						if(a.hasClass("last")){
				 	a.removeClass("closed");
					a.removeClass("last");
					a.addClass("open");
					a.addClass("open last");
						}else{
							
					a.removeClass("closed");
					a.addClass("open");
							
							
						}
				  }
					   
					   //alert($json.html);
							
					   break;
					   case(0):
							showErr($json.msg);
					   break;
					   }
				   }
		});
					

			  });

		$("input[un='query']").focus(function()
		{
			if(this.value==="搜索成员")
			{
			this.style.color="#000";
			this.value="";
			}
		});
		
		$("input[un='query']").blur(function()
		{
		
			if(this.value==="")
			{
			this.style.color="";
			this.value="搜索成员";
			}
		});
		
		
		$("input[ck='isduoke']").live("click",function(){
		
			if(this.checked){
				
				$("#isduoke").html("");
				
				$isduokeHtml = $("#isduokehtml").html();
		
				$("#isduoke").html($isduokeHtml);
			}
		});
		
		
		$("input[ck='isdanke']").live("click",function(){
		
			if(this.checked){
				
				$("#isduoke").html("");
				
				$isdankeHtml = $("#isdankehtml").html();
		
				$("#isduoke").html($isdankeHtml);
		
				
			}
		});

	$("input[ck='isfolder']").live("click",function(){
		
			if(this.checked && this.value==1){
				
				$("#isfolder").html("");
				
			}else if (this.checked && this.value==0){
				
				$("#isfolder").html("");
				
				$isduokeHtml = $("#isfolderhtml").html();
		
				$("#isfolder").html($isduokeHtml);
				
				
			}
		});













function pageTipsCfg(){
	return{
		tipsErrContainer:$("#tipsError"),
		tipsMsgContainer:$("#tipsMsg"),
		tipsProcessContainer:$("#tipsProcess")
	};
	}
	
	function showErr(cf,xb)
	{
	var al=$(this),
	yM=xb||1500;
	pageTipsCfg().tipsErrContainer.show().html(cf);
	setTimeout(
	function()
	{
	hideMsg();
	location.replace(location.href);
	},
	yM
	);
	return al;
	};
	
	function msgErr(cf,xb)
	{
	var al=$(this),
	yM=xb||1500;
	pageTipsCfg().tipsErrContainer.show().html(cf);
	setTimeout(
	function()
	{
	hideMsg();
	//location.replace(location.href);
	},
	yM
	);
	return al;
	};
	
	function showMsg(cf,xb,pH)
	{
	var al=$(this),
	yM=xb||700;
	pageTipsCfg().tipsMsgContainer.show().html(cf);
	setTimeout(
	function()
	{
	hideMsg();
	location.replace(location.href);
	//call(pH);
	},
	yM
	);
	return al;
};

	
	function hideMsg()
	{
	pageTipsCfg().tipsErrContainer.hide();
	pageTipsCfg().tipsMsgContainer.hide();
	};
	
	
	function bUB(MV,CODE,bdu,isbaned,url)
	{
		
		var idarr=(typeof MV=="string")?MV:MV.join(",");
		var authcode=(typeof MV=="string")?CODE:CODE.join(",");
		//showMsg(hi);
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : bdu,
					 
					  isbaned:isbaned,
					  
					  idarr:idarr,
					  
					  authcode:authcode
				   },
				   success: function(data){
					   var $json = data;
					   switch($json.status){
					   case(1):
							showMsg($json.msg);
					   break;
					   case(0):
							showErr($json.msg);
					   break;
					   }
				   }
		});
		
		
		
	};
	
	
	
	function Branck(Bid,AC)
	{
		var HT='';
		
		//showMsg(hi);
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=do",
				   type : "post",
				   dataType: "json",
				   data: {
					  
					  action : AC,

					  bid:Bid
				   },
				   success: function(data){
					   var $json = data;
					   switch($json.status){
					   case(1):
						
						showMsg($json.msg);
							
					   break;
					   case(0):
							showErr($json.msg);
					   break;
					   }
				   }
		});
		
		 return HT;
		
	};
	
	
	
	function MSG(msgID,$json_msg)
	{
		
		 switch(msgID){
			case("showMsg"):
			
				showMsg($json_msg);
			
			break;
			
			case("showErr"):
			
				showErr($json_msg);
			
			break;
			
			case(3):
			
			break;
		 }
		
	};
	
	function bBH()
	{
		var iS=[],
		aY = $("tr").find("input[un='mbrCheck']");
		aY.each(function()
		{
			if(this.checked){
				iS.push(this.getAttribute("alias"))
			}
		});
		return iS;
	};
	
	
	function KmCc()
	{
		var Km=[],
		aY = $("#structList").find("a[icon='party']");
		aY.each(function()
		{
			Km.push(this.getAttribute("selectid"))
		});
		return Km;
	};
	
	
	
	function getauthcode()
	{
		var CODE=[],
		aY = $("tr").find("input[un='mbrCheck']");
		aY.each(function()
		{
			if(this.checked){
				CODE.push(this.getAttribute("authcode"))
			}
		});
		return CODE;
	};
	
	function getUrlParam(name)
			{
			var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
			var r = window.location.search.substr(1).match(reg);  //匹配目标参数
			if (r!=null) return unescape(r[2]); return null; //返回参数值
			} 


	function trim(str){ //删除左右两端的空格
　	　return str.replace(/(^\s*)|(\s*$)/g, "");
　　		}


	$("a[ck='pswShow']").live("click",function(){
		var a = $(this);
		var jv=$("#pswcontainer");
		if(trim(jv.html())==""){
			jv.html($("#pswhide").html());
			a.html('取消修改');
			}else{
				jv.html("");
				a.html('修改');
			};
	});
	
	$("a[ck='send_verify']").live("click",function(){
		 	var a = $(this);
			var mobile =$("input[name='mobile']").val();
			$.ajax({
				   url: TJ.path+"index.php?mod="+ TJ.mod +"&do=forgetpwd&c=send_verify",
				   type : "post",
				   dataType: "json",
				   data: {
					  mobile:mobile
				   },
				   beforeSend: function(){
					a.html('&nbsp;&nbsp;&nbsp;&nbsp;正在发送...');
				   },
				   success: function(data){
					   var $json = data;
					   switch($json.status){
					   case(1):
						a.html('&nbsp;&nbsp;&nbsp;&nbsp;'+$json.msg);
					   break;
					   case(0):
							a.html('&nbsp;&nbsp;&nbsp;&nbsp;'+$json.msg);
					   break;
					   }
				   }
		});
			
		   });
		   
	$("input[ck='dtime_btn']").live("click",function(){
		var a = $("input[op='isdtime']");
		var sourcehtml = $("#source").html();
		if(a.val()=='0'){
			$("#dtime").html(sourcehtml);
			a.val("1");
			}else{
			$("#dtime").html("");
			a.val("0");
		};
		}); 
		
	
	$("a[ck='mbrSearch']").live("click",function(){
		 	var a = $("input[ku='mbrSearchKU']");
			var c = this.getAttribute("c");
			var bid = this.getAttribute("bid");
			var keyword = a.val();
			if(keyword=='搜索成员'||keyword==''){ msgErr('请输入搜索关键词！');}else{
			location.href=TJ.path + "?mod="+TJ.mod+"&do=member&c="+ c +"&bid="+ bid +"&keyword=" + keyword;
			}
		   }); 
		   
	$("input[ku='mbrSearchKU']").live("keydown",function(e){
		if(e.keyCode==13){
  			var a = $("input[ku='mbrSearchKU']");
			var c = this.getAttribute("c");
			var bid = this.getAttribute("bid");
			var keyword = a.val();
			if(keyword=='搜索成员'||keyword==''){ msgErr('请输入搜索关键词！');}else{
			location.href=TJ.path + "?mod="+TJ.mod+"&do=member&c="+ c +"&bid="+ bid +"&keyword=" + keyword;
			}
		}
		   }); 
		
	
	
			  
	$("input.sms_text").live("blur", function(){
		var num = $(this).val();
		total1 = $(".my_sms_balance").html();
		usable = $(".my_sms_balance").attr('usable');
		total = 0;
		$('input[op="sms_tiaoshu[]"]').each(function(){
			if($(this).val()==''){
				a = 0;
			}else{
				a = parseInt($(this).val());
			}
			total = total + a;
 		});
		if(total>usable){
			msgErr('短信条数超出可用配额,当前可用配额还剩 '+total1+' 条', '6000');
			$(this).val(0);
		}else if(num == ''){
			$(this).val(0);
		}else{
			$(".my_sms_balance").html(usable-total);
			$("input[name='my_balance']").val(usable-total);
		}
	});
	
	$("a[action-type='replyMessage']").live("click",function(){
		
		document.form1.content.focus();
		
	});
	
})