// JavaScript Document

/**
 * 基础JS类，封装常用的文档和AJAX操作
 *
 * @author Daniel
 */
function Base(){};

var WEB_CONTEXT="";

Base.prototype = {


	/**
	 * 必须选择或镇充的控件的ID，多个时以","分隔
	 */
	requestParam : null,
	/**
	 * 检查必选项，如必选项没选择或镇充则进行提示
	 * requestParam 必须选择或镇充的控件的ID，多个时以","分隔
	 * 使用说明：必选项需写在requestParam中，并在页面初始化时对其进行赋值，另外必选项中添加title作为提示内容，需以中文命名
	 *  @author gwl
	 */
	checkRequest : function(){
		if (this.requestParams){
			var request=this.requestParams.split(',');
			for(i=0;i<request.length;i++){
				tmp = $('#'+request[i]).attr('value');
				if(!tmp || -1==tmp || 0 == tmp){
					base.alert('请选择'+$('#'+request[i]).attr('title'),'温馨提示');
					return false;
				}
			}
		}
		return true;
	},
	/**
	 * 获取当前日期
	 * format 为输出日期格式，默认格式为yyyy-MM-dd
	 */
	 getCurrDate : function (format){
	 	if (!format){
	 		format = 'yyyy-MM-dd';
	 	}
	 	var curDate = new Date();
	 	var year = ''+curDate.getFullYear();
	 	var month = ''+(curDate.getMonth()+1);
	 	var day = ''+curDate.getDate();
	 	var len = 0;
	 	if (month<10)
	 		month = '0'+month;
	 	if (day<10)
	 		day = '0'+day;
	 	var tmp = '';
	 	var result = format;
	 	for (i=0;i<format.length;i++){
	 		chr = format.substr(i,1);
			 if (tmp.indexOf(chr)>-1){
	 			tmp +=   format.substr(i,1);
	 		}
	 		if (('yMd'.indexOf(chr)>-1 && (tmp.indexOf(chr)==-1||i==format.length-1))){
	 			if (tmp.indexOf('y')>=0){
	 				if (tmp.length>4) len = 4; else len = tmp.length;
	 				result = result.replace(tmp,year.substr(4-len));
	 			} else if (tmp.indexOf('M')>=0){
	 				if (tmp.length>2) len = 2; else len = tmp.length;
	 				result = result.replace(tmp,month.substr(2-len));
	 			} else if (tmp.indexOf('d')>=0){
	 				if (tmp.length>2) len = 2; else len = tmp.length;
	 				result = result.replace(tmp,day.substr(2-len));
	 			}
	 			tmp = chr;
	 		}
	 	}
	 	return result;
	 },

	/**
	 * 弹出提示信息
	 * content 提示内容
	 * title 提示框标题，默认“系统信息”
	 */
	alert : function(/*string*/content, /*string*/title){
		alert(content);
	},

	/**
	 * 弹出确认框提示信息
	 * content 提示内容
	 * title 提示框标题，默认“系统信息”
	 */
	confirm : function(/*string*/content, /*string*/title){
		return confirm(content);
	},

	/**
	 * 分页查询结果html刷新到showId元素中
	 * url 请求URL，不包含分页参数
	 * page 当前请求的是第几页
	 * onsuccess 回调函数
	 */
	pageLoad : function(/*string*/url, /*string*/showId,/*string*/page,/*string*/pageName,/*function*/onsuccess){
		$('#' + showId).html("<div align=\"center\"><img src=\""+WEB_CONTEXT+"/teacher/images/37-0.gif\"/></div>");
		var allurl;
		if(url.indexOf("&"+pageName+"=")==-1){//加上分页参数
			if(url.indexOf("?")==-1){
				allurl=url+"?"+"pageInputName="+pageName+"&showdivId="+showId+"&"+pageName+"="+page;
			}else{
				//allurl=url+"&"+"pageInputName="+pageName+"&showdivId="+showId+"&"+pageName+"="+page;
				allurl=url;
				if(allurl.indexOf("&"+pageName+"=")==-1){
					allurl=allurl+"&"+pageName+"="+page;
				}
				if(allurl.indexOf("&pageInputName=")==-1){
					allurl=allurl+"&pageInputName="+pageName;
				}
				if(allurl.indexOf("&showdivId=")==-1){
					allurl=allurl+"&showdivId="+showId;
				}
			}
		}else{//已经存在page参数，需要把page参数清空
			allurl=url;
		}
		allurl = encodeURI(allurl);
		$('#' + showId).load(allurl, function(result){
			if(onsuccess){
				onsuccess(result);
			}
			$('#'+pageName).keydown(function(e){//添加回车事件
				if(isNaN(this.value)){
					alert('请输入数字');
					return;
				}
				if(e.keyCode==13){
					base.pageLoad(url,showId,this.value,pageName,'');
				}
			});
		});
	},

	/**
	 * 分页查询结果html刷新到showId元素中
	 * url 请求URL，不包含分页参数
	 * page 当前请求的是第几页
	 * onsuccess 回调函数
	 */
	load : function(/*string*/url, /*string*/showId,/*string*/page,/*string*/pageName,clickId,/*function*/onsuccess){
		$('#' + showId).html("<div align=\"center\"><img src=\""+WEB_CONTEXT+"/teacher/images/37-0.gif\"/></div>");
		if(pageName==''){
			pageName='page';
		}
		var allurl;
		if(url.indexOf("&"+pageName+"=")==-1){//加上分页参数
			if(url.indexOf("?")==-1){
				allurl=url+"?"+pageName+"="+page;
			}else{
				allurl=url+"&"+pageName+"="+page;
			}
		}else{//已经存在page参数，需要把page参数清空
			allurl=url;
		}

		if(url.indexOf("showdivId=")==-1){//没有添加
			allurl=allurl+"&showdivId="+showId+"&pageInputName="+pageName;
		}
		allurl=allurl?(allurl+"&_ajax=true"):"?_ajax=true";

		$(clickId).attr('disabled',true);

		var reqTime = "&reqTemp="+new Date().getTime();
		allurl = allurl+reqTime;
		//base.Debug("base.load()->URL: "+allurl);


		allurl = encodeURI(allurl);
		$('#' + showId).load(allurl, function(result){
			if(onsuccess){
				onsuccess(result);
			}
			$(clickId).attr('disabled',false);
			$('#'+pageName).keydown(function(e){//添加回车事件
				if(isNaN(this.value)){
					alert('请输入数字');
					return;
				}
				if(e.keyCode==13){
					base.pageLoad(url,showId,this.value,pageName,'');
				}
			});
		});
	},


	/**
	 * 用于查询结果html刷新到showId元素中
	 * url 请求URL，不包含分页参数
	 * onsuccess 回调函数
	 */
	searchLoadPage : function(/*string*/url, /*string*/showId,clickId,formId,/*function*/onsuccess,/*int*/page){
		$('#page').val(page);
		var allurl=url+"&"+base._getFormValues(formId);
		base.load(allurl+"",showId,page,'page',clickId,onsuccess);
	},

	/**
	 * 用于查询结果html刷新到showId元素中
	 * url 请求URL，不包含分页参数
	 * onsuccess 回调函数
	 */
	searchLoad : function(/*string*/url, /*string*/showId,clickId,formId,/*function*/onsuccess){
		$('#page').val(1);//初始化当前页=1
		var allurl=url+"&"+base._getFormValues(formId);
		base.load(allurl+"",showId,'1','page',clickId,onsuccess);
	},


	/**
	 * 发送AJAX请求动作，用于删除、保存设置等，若返回JSON对象会自动提示JSON的信息
	 * url 请求地址
	 * params 请求参数 如： '&userId=1'
	 */
	post : function(/*string*/url, /*string*/params, onsuccess, type){
		this.req(/*string*/url, /*string*/params, function(result){
			//如果返回脚本，则执行并返回
			if(base.evalScripts(result)){
				return;
			}
			var suc = base.json(result);
			if(suc){
				base.alert(suc.message);
			}
			onsuccess(result);
		}, type);
	},

	/**
	 * 发送AJAX请求动作
	 * url 请求地址
	 * params 请求参数 如： '&userId=1'
	 */
	req : function(/*string*/url, /*string*/params, onsuccess, type){
		url = encodeURI(url);
		if(url.indexOf('&_temp=')==-1){
			url += ('&_temp=' + Math.random());
		}

		//params=params?(params+"&_ajax=true"):"_ajax=true";
	    if(url.indexOf('?')>0 && url.indexOf('=')>0){
	        url+="&_ajax=true";
	      }else if(url.indexOf('?')<0 && url.indexOf('=')<0){
	        url+="?_ajax=true";
	      }else if(url.indexOf('?')>0 && url.indexOf('=')<0){
	        url+="_ajax=true";
	      }

		url+=("&reqTemp="+new Date().getTime());
		$.ajax({
			type: "POST",
			url: url,
			data: params,
			success: function(result){
			        //加入用户会话过期提示
			        base.checkUserLoginState(result);
					if(onsuccess){
						onsuccess(result);
					}
				},
			error: function(result){
					base.alert('服务器维护中，请稍后再使用！');
				},
				dataType: type==undefined?"text":type
		});
	},

	/**
	 * 将服务器端返回的JSON对象转换成js中的对象
	 */
	json : function(/*string*/jsonString) {
		try {
			return eval('(' + jsonString + ')');
		} catch (ex1) {
			try{//火狐等可能返回以<pre></pre>包含的字符串
				var string = jsonString.trim().replace(/<pre(.*?>)/gi,"").replace(/<\/pre>/gi,"");
				return eval('(' + string + ')');
			} catch (ex2) {
				try{//火狐等可能返回xmlDocument
					var string = (new XMLSerializer()).serializeToString(jsonString);
					return eval('(' + string + ')');
				} catch (ex3) {
					return null;
				}
			}
		}
	},

	/**
	 * 使指定的checkbox框全部被选中或不选中
	 */
	checkAll : function(/*string*/ checkboxName, /*boolean*/ check) {
		$("input[name='"+checkboxName+"']:enabled").attr('checked',check);
	},

	/**
	 * 按多选框名称取得已勾选的多选框对象数组
	 */
	getChecked : function(/*string*/ checkboxName) {
		return $("input[name='"+checkboxName+"']:checked");
	},

	/**
	 * 按多选框名称取得已勾选的值并组装成参数
	 * checkboxName 多选框名称
	 * paramName 要组装成的参数名，为空则默认 = checkboxName
 	 */
	getCheckedValuesParam : function(/*string*/ checkboxName, /*String*/ paramName){
		var ckbs = $("input[name='"+checkboxName+"']:checked");
		if(!ckbs || ckbs.length<1){
			return '';
		}
		var params = '';
		var pn = (paramName || checkboxName);
		ckbs.each(function(){params += '&' + pn + '=' + this.value;});
		return params;
	},


	/**
	 * 按多选框名称取得已勾选的值并组装成参数
	 * checkboxName 多选框名称
	 * paramName 要组装成的参数名，为空则默认 = checkboxName
 	 */
	getCheckedValuesParamSplitString : function(/*string*/ checkboxName, /*String*/ paramName){
		var ckbs = $("input[name='"+checkboxName+"']:checked");
		if(!ckbs || ckbs.length<1){
			return '';
		}
		var pn = (paramName || checkboxName);
		var params = '';
		ckbs.each(function(){params += ',' + this.value;});
		if(params.length>1)
		   params = params.substring(1, params.length);
		return params;
	},


	/**
	 *  自动生成URL中的参数
	 *  paramStr 参数条件ID，多个以','分隔
	 *  filterLen 在生成URL参数变量时截掉前几位字符
	 *  @author gwl
	 */
	 getUrlParams : function(/*string*/ paramStr,filterLen ){
	 	 var param = '';
	 	 if (!filterLen)
	 	 	filterLen = 0;
	 	if (paramStr){
			var params=paramStr.split(',');
			for(i=0;i<params.length;i++){
				if ('checkbox'==$('#'+params[i]).attr('type'))
					param += this.getCheckedValuesParam($('#'+params[i]).attr('name'),params[i]);
				else
					param += '&'+params[i].substr(filterLen)+'='+$('#'+params[i]).attr('value');
			}
		}
		return param;
	 },

	/**
	 * 初始化文件上传操作，依赖于 /js/base/ajaxupload.js ，使用此方法前必须引入ajaxupload.js
	 * moduleFlag（必填） 模块标识，用于指定上传到服务端所在的文件目录，
	 *		对应于后台cn.qtone.xxt.base.upload.domain.UploadModuleConfig类配置的模块标识
	 *
	 * buttonId 页面上的上传按钮ID
	 * fileType 允许上传的文件类型，即文件后缀名，多个用逗号隔开，默认为 'xls,xlsx', '*'代表允许上传所有格式的文件
	 * maxCount 一个页面上允许上传的文件数，默认1，上传文件在这个数的时候，上传按钮会自动隐藏.
	 * fileEltName 生成的随机文件名表单元素名 默认ufilename，用于生成上传成功后的表单字段，用于服务端返回的随机文件名
	 * ofileEltName 生成的原始文件名表单元素名 默认uofilename，用于生成上传成功后的表单字段，用于服务端返回的原始文件名
	 *
	 * 使用实例：页面上需要添加上传的地方加个 <input id="button1" type="button" class="but_03" value="点击选择文件上传"  /> 元素
	 * 		    在页面加载完成以后JS调用 base.initUpload('admin_edu_school','button1')方法即可实现 参数自定;
	 * 		   可参考学籍管理 - 学校管理 - 班级管理 - 兴趣生导入功能.
	 */
	initUpload : function(/*string*/moduleFlag, /*string*/buttonId, /*string*/fileType, /*int*/maxCount, /*string*/fileEltName, /*string*/ofileEltName){
		 if(!maxCount || maxCount<1) maxCount = 1;
		var ufilename = (fileEltName || 'ufilename');//新的随机文件名hidden name
		var uofilename = (ofileEltName || 'uofilename');//原始文件名hidden name
		var ft = (fileType || 'xls,xlsx,jpg,jpeg').replace(new RegExp(',','gm'),'|');
		var button = $('#' + buttonId), interval;
		var buttonval = button.val();
		button.after("<input id='filecount' value='0' type='hidden' />");

		var san=-1;
		if($('#san').length>0){
			san=$('#san').val();
		}
		new AjaxUpload(buttonId, {
			action: WEB_CONTEXT+'/school/base/upload.do?action=upload&moduleFlag=' + moduleFlag + "&san="+san,
			name: 'file',
			onSubmit : function(file, ext){
				if (ft!='*' && (!ext || !(eval('/^('+ft+')$/i')).test(ext))){
					base.alert('只能上传格式为['+ft+']的文件！');
					return false;
				}

				try{
					button.attr('value','正在上传文件');
				}catch(e){
				}
				this.disable();
				interval = window.setInterval(function(){
					var text = button.attr('value');
					if (text.length < 15){
						button.attr('value', text + '.');
					} else {
						button.attr('value', buttonval);
					}
				}, 200);
			},
			onComplete: function(file, response){
				var suc = base.json(response);
				if(!suc.success){
					if(suc.isNeedSpecialAlert)
						alert(suc.returnMsg);
					else
						alert('上传的文件太大（不能超过 1.5MB）,操作失败!');
					window.clearInterval(interval);
					button.attr('value', '选择文件');
					this.enable();
				}else{
					var newFileName = suc.rfname;
					var savUrlPath = suc.savUrlPath; //文件对应的URl地址
					button.attr('value', buttonval);
					window.clearInterval(interval);
					var c = parseInt($('#filecount').attr('value')) + 1;
					$('#filecount').attr('value',c);
					if(c >= maxCount){
						$('#' + buttonId).hide();
					}
					this.enable();
					file=file.replace(/\,/g,"，").replace(/\_/g,"＿");
					$('#' + buttonId).after('<p><input id=\''+ufilename+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
						+ '<input  id=\''+uofilename+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+file+'\'/>'
						+ file + '  <a href=\'javascript:void(0)\' onclick=\"base._delfile(this,\''+moduleFlag+'\',\''+newFileName +'\','
						+ maxCount +',\''+buttonId+'\')\" class=\"cblue\">删除</a></p>');



				}
			}
		});
	},
	
	/**uploadfiy
	 * （门户专用）初始化文件上传操作，依赖于 /js/base/ajaxupload.js ，使用此方法前必须引入ajaxupload.js
	 * moduleFlag（必填） 模块标识，用于指定上传到服务端所在的文件目录，
	 * buttonId 页面上的上传按钮ID
	 * fileType 允许上传的文件类型，即文件后缀名，多个用逗号隔开，默认为 'xls,xlsx', '*'代表允许上传所有格式的文件
	 * maxCount 一个页面上允许上传的文件数，默认1，上传文件在这个数的时候，上传按钮会自动隐藏.
	 * showDiv 上传成功的文件名显示在哪个div里
	 */
	initMhUploadNew : function(/*string*/moduleFlag, /*string*/buttonId, /*string*/fileType, /*int*/maxCount,/*string*/showDiv,size){
		 if(!maxCount || maxCount<1) maxCount = 1;
		var ufilename = 'fileName';//新的随机文件名hidden name
		var uofilename = 'ofileName';//原始文件名hidden name
		var ft = (fileType || '*.xls,*.xlsx,*.jpg,*.jpeg').replace(new RegExp(',','gm'),';');
		$('#' + buttonId).after("<input id='filecount' value='0' type='hidden' />");
		if(size==undefined )
			size=1.5;
		$("#" + buttonId).uploadify({
			'swf'       	 : WEB_CONTEXT+'/teacher/uploadify/uploadify.swf',
			'uploader'      : WEB_CONTEXT+'/unite/base/upload.do?action=uploadNew',
			'formData'		 :  {'moduleFlag':'mh_resource','size':'1.5'}, 
			'cancelImg'      : WEB_CONTEXT+'/teacher/uploadify/images/uploadify-cancel.png',
	//		'folder'         : 'upfile',
			'queueID'        : showDiv,
			'auto'           : true,
			'multi'          : false,
		//	'buttonClass'    : 'local_btn',
		//	'removeTimeout'  : 1,
			'fileSizeLimit' 	 : size * 1024,  //限制文件的大小，默认单位是KB
			'queueSizeLimit' : 1,
		//	'uploadLimit'	 : maxCount,
			'fileTypeExts' 		 : ft,
			'fileTypeDesc' 		 : '请选择' + ft + '文件',
			'height'         : 27,  
			'width'          : 71,  
		//	'buttonImage'      : WEB_CONTEXT+'/mh/images/fj/fileChose.jpg',  
			'buttonText'	 : '选择文件',//,
			'onUploadSuccess'  : function(file, data, response) {
				var suc = base.json(data);
				if(!suc.success){
					if(suc.isNeedSpecialAlert)
						alert(suc.returnMsg);
					else
						alert('上传的文件太大（不能超过' + size + 'MB）,操作失败!');
				}else{
					var newFileName = suc.rfname;
					var savUrlPath = suc.savUrlPath; //文件对应的URl地址
					var docSize = suc.docSize;  //文件大小，k为单位
					var c = parseInt($('#filecount').attr('value')) + 1;
					$('#filecount').attr('value',c);
					if(c >= maxCount){
						$('#' + buttonId).uploadify('disable',true);
					}
					filename=file.name.replace(/\,/g,"，").replace(/\_/g,"＿");
					$('#' + showDiv).append('<p><input id=\''+ufilename+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
						+ '<input  id=\''+uofilename+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+filename+'\'/>'
						+ '<input  id=\'docSize\' name=\'docSize\' type=\'hidden\' value=\''+docSize+'\'/>'
						+ filename + '  <a href=\'javascript:void(0)\' onclick=\"base._delfileNew(this,\''+moduleFlag+'\',\''+newFileName +'\','
						+ maxCount +',\''+buttonId+'\')\" class=\"cblue\">删除</a></p>');
				}
			},
			'onSelectError'        : function(file, errorCode, errorMsg)  {  
				if(errorCode == 'FILE_EXCEEDS_SIZE_LIMIT' )
			      alert('上传的文件太大（不能超过' + size + 'MB）,操作失败');  
			    }   

		});
			
	},
	
	/**
	 * （门户专用）初始化文件上传操作，依赖于 /js/base/ajaxupload.js ，使用此方法前必须引入ajaxupload.js
	 * moduleFlag（必填） 模块标识，用于指定上传到服务端所在的文件目录，
	 * buttonId 页面上的上传按钮ID
	 * fileType 允许上传的文件类型，即文件后缀名，多个用逗号隔开，默认为 'xls,xlsx', '*'代表允许上传所有格式的文件
	 * maxCount 一个页面上允许上传的文件数，默认1，上传文件在这个数的时候，上传按钮会自动隐藏.
	 * showDiv 上传成功的文件名显示在哪个div里
	 */
	initMhUpload : function(/*string*/moduleFlag, /*string*/buttonId, /*string*/fileType, /*int*/maxCount,/*string*/showDiv,size){
		 if(!maxCount || maxCount<1) maxCount = 1;
		var ufilename = 'fileName';//新的随机文件名hidden name
		var uofilename = 'ofileName';//原始文件名hidden name
		var ft = (fileType || 'xls,xlsx,jpg,jpeg').replace(new RegExp(',','gm'),'|');
		var button = $('#' + buttonId), interval;
		var buttonval = button.val();
		button.after("<input id='filecount' value='0' type='hidden' />");
		if(size==undefined )
			size=1.5;

		new AjaxUpload(buttonId, {
			action: WEB_CONTEXT+'/unite/base/upload.do?action=upload&moduleFlag=' + moduleFlag + '&size=' + size ,
			name: 'file',
			onSubmit : function(file, ext){
				if (ft!='*' && (!ext || !(eval('/^('+ft+')$/i')).test(ext))){
					base.alert('只能上传格式为['+ft+']的文件！');
					return false;
				}

				try{
					button.attr('value','正在上传文件');
				}catch(e){
				}
				this.disable();
				interval = window.setInterval(function(){
					var text = button.attr('value');
					if (text.length < 15){
						button.attr('value', text + '.');
					} else {
						button.attr('value', buttonval);
					}
				}, 200);
			},
			onComplete: function(file, response){
				var suc = base.json(response);
				if(!suc.success){
					if(suc.isNeedSpecialAlert)
						alert(suc.returnMsg);
					else
						alert('上传的文件太大（不能超过' + size + 'MB）,操作失败!');
					window.clearInterval(interval);
					button.attr('value', '选择文件');
					this.enable();
				}else{
					var newFileName = suc.rfname;
					var savUrlPath = suc.savUrlPath; //文件对应的URl地址
					var docSize = suc.docSize;  //文件大小，k为单位
					button.attr('value', buttonval);
					window.clearInterval(interval);
					var c = parseInt($('#filecount').attr('value')) + 1;
					$('#filecount').attr('value',c);
					if(c >= maxCount){
						$('#' + buttonId).hide();
					}
					this.enable();
					file=file.replace(/\,/g,"，").replace(/\_/g,"＿");
					$('#' + showDiv).append('<p><input id=\''+ufilename+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
						+ '<input  id=\''+uofilename+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+file+'\'/>'
						+ '<input  id=\'docSize\' name=\'docSize\' type=\'hidden\' value=\''+docSize+'\'/>'
						+ file + '  <a href=\'javascript:void(0)\' onclick=\"base._delfile(this,\''+moduleFlag+'\',\''+newFileName +'\','
						+ maxCount +',\''+buttonId+'\')\" class=\"cblue\">删除</a></p>');



				}
			}
		});
	},


	/**
	 * 初始化文件上传操作，依赖于 /js/base/ajaxupload.js ，使用此方法前必须引入ajaxupload.js
	 * moduleFlag（必填） 模块标识，用于指定上传到服务端所在的文件目录，
	 *		对应于后台cn.qtone.xxt.base.upload.domain.UploadModuleConfig类配置的模块标识
	 *
	 * buttonId 页面上的上传按钮ID
	 * fileType 允许上传的文件类型，即文件后缀名，多个用逗号隔开，默认为 'xls,xlsx', '*'代表允许上传所有格式的文件
	 * maxCount 一个页面上允许上传的文件数，默认1，上传文件在这个数的时候，上传按钮会自动隐藏.
	 * fileEltName 生成的随机文件名表单元素名 默认ufilename，用于生成上传成功后的表单字段，用于服务端返回的随机文件名
	 * ofileEltName 生成的原始文件名表单元素名 默认uofilename，用于生成上传成功后的表单字段，用于服务端返回的原始文件名
	 *
	 * 使用实例：页面上需要添加上传的地方加个 <input id="button1" type="button" class="but_03" value="点击选择文件上传"  /> 元素
	 * 		    在页面加载完成以后JS调用 base.initUpload('admin_edu_school','button1')方法即可实现 参数自定;
	 * 		   可参考学籍管理 - 学校管理 - 班级管理 - 兴趣生导入功能.
	 */
	initPicUpload : function(/*string*/moduleFlag, /*string*/buttonId, /*string*/fileType, /*int*/maxCount, /*string*/fileEltName, /*string*/ofileEltName,/*function*/onsuccess){
		 if(!maxCount || maxCount<1) maxCount = 1;
		var ufilename = (fileEltName || 'ufilename');//新的随机文件名hidden name
		var uofilename = (ofileEltName || 'uofilename');//原始文件名hidden name
		var ft = (fileType || 'xls,xlsx,jpg,jpeg').replace(new RegExp(',','gm'),'|');
		var button = $('#' + buttonId), interval;
		var buttonval = button.val();
		button.after("<input id='filecount' value='0' type='hidden' />");

		var san=-1;
		if($('#san').length>0){
			san=$('#san').val();
		}
		
		var specialPicUpload ="";
		var prefix = "url:";
		var isSpecialUpload = false;
		if(moduleFlag.substr(0,prefix.length)==prefix){
			specialPicUpload = moduleFlag.substring(4);
			isSpecialUpload= true;
		}
		
		new AjaxUpload(buttonId, {
			action: (!isSpecialUpload?WEB_CONTEXT+'/school/base/upload.do?action=upload&moduleFlag=' + moduleFlag + "&san="+san:WEB_CONTEXT+specialPicUpload),
			name: 'file',
			onSubmit : function(file, ext){
				if (ft!='*' && (!ext || !(eval('/^('+ft+')$/i')).test(ext))){
					base.alert('只能上传格式为['+ft+']的文件！');
					return false;
				}

				try{
					button.attr('value','正在上传文件');
				}catch(e){
				}
				this.disable();
				interval = window.setInterval(function(){
					var text = button.attr('value');
					if (text.length < 15){
						button.attr('value', text + '.');
					} else {
						button.attr('value', buttonval);
					}
				}, 200);
			},
			onComplete: function(file, response){
				var suc = base.json(response);
				if(!suc.success){
					alert('上传的文件太大（不能超过 1.5MB）,操作失败!');
					window.clearInterval(interval);
					button.attr('value', '选择文件');
					this.enable();
				}else{
					var newFileName = suc.rfname;
					var savUrlPath = suc.savUrlPath; //文件对应的URl地址
					button.attr('value', buttonval);
					window.clearInterval(interval);
					var c = parseInt($('#filecount').attr('value')) + 1;
					$('#filecount').attr('value',c);

				    $('#' + buttonId).click(function(event){
                              alert('tt');

					});

					file=file.replace(/\,/g,"，").replace(/\_/g,"＿");
					if(!isSpecialUpload){
						$('#' + buttonId).after('<input id=\''+ufilename+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
						    + '<input  id=\''+uofilename+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+file+'\'/>');
					}else{
						var tid = "_"+buttonId.replace("uploadBtn_","");
						$('#' + buttonId).after('<input id=\''+ufilename+tid+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
							    + '<input  id=\''+uofilename+tid+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+file+'\'/>');
					}

					this.enable();
				    if(onsuccess){
				    	//班级相册，预览图为缩略图，不是实际保存的路径，因此需要加多一个参数filePath来记录真正的保存路径
				    	if(!isSpecialUpload)
				    		onsuccess(savUrlPath,file,newFileName);
				    	else
				    		onsuccess(savUrlPath,file,newFileName,suc.filePath);
				    }

				}
			}
		});
	},

	/**
	 * 初始化文件上传操作，依赖于 /js/base/ajaxupload.js ，使用此方法前必须引入ajaxupload.js
	 * moduleFlag（必填） 模块标识，用于指定上传到服务端所在的文件目录，
	 *		对应于后台cn.qtone.xxt.base.upload.domain.UploadModuleConfig类配置的模块标识
	 *
	 * buttonId 页面上的上传按钮ID
	 * fileType 允许上传的文件类型，即文件后缀名，多个用逗号隔开，默认为 'xls,xlsx', '*'代表允许上传所有格式的文件
	 * maxCount 一个页面上允许上传的文件数，默认1，上传文件在这个数的时候，上传按钮会自动隐藏.
	 * fileEltName 生成的随机文件名表单元素名 默认ufilename，用于生成上传成功后的表单字段，用于服务端返回的随机文件名
	 * ofileEltName 生成的原始文件名表单元素名 默认uofilename，用于生成上传成功后的表单字段，用于服务端返回的原始文件名
	 *
	 * 使用实例：页面上需要添加上传的地方加个 <input id="button1" type="button" class="but_03" value="点击选择文件上传"  /> 元素
	 * 		    在页面加载完成以后JS调用 base.initUpload('admin_edu_school','button1')方法即可实现 参数自定;
	 * 		   可参考学籍管理 - 学校管理 - 班级管理 - 兴趣生导入功能.
	 */
	initMhPicUpload : function(/*string*/moduleFlag, /*string*/buttonId, /*string*/fileType, /*int*/maxCount, /*string*/fileEltName, /*string*/ofileEltName,/*function*/onsuccess){
		 if(!maxCount || maxCount<1) maxCount = 1;
		var ufilename = (fileEltName || 'ufilename');//新的随机文件名hidden name
		var uofilename = (ofileEltName || 'uofilename');//原始文件名hidden name
		var ft = (fileType || 'xls,xlsx,jpg,jpeg').replace(new RegExp(',','gm'),'|');
		var button = $('#' + buttonId), interval;
		var buttonval = button.val();
		button.after("<input id='filecount' value='0' type='hidden' />");
		new AjaxUpload(buttonId, {
			action: WEB_CONTEXT+'/unite/base/upload.do?action=upload&isPic=1&moduleFlag=' + moduleFlag ,
			name: 'file',
			onSubmit : function(file, ext){
				if (ft!='*' && (!ext || !(eval('/^('+ft+')$/i')).test(ext))){
					base.alert('只能上传格式为['+ft+']的文件！');
					return false;
				}
				try{
					button.attr('value','正在上传文件');
				}catch(e){
				}
				this.disable();
				interval = window.setInterval(function(){
					var text = button.attr('value');
					if (text.length < 15){
						button.attr('value', text + '.');
					} else {
						button.attr('value', buttonval);
					}
				}, 200);
			},
			onComplete: function(file, response){
				var suc = base.json(response);
				if(!suc.success){
					alert('上传的文件太大（不能超过 1.5MB）,操作失败!');
					window.clearInterval(interval);
					button.attr('value', '选择文件');
					this.enable();
				}else{
					var newFileName = suc.rfname;
					var savUrlPath = suc.savUrlPath; //文件对应的URl地址
					button.attr('value', buttonval);
					window.clearInterval(interval);
					var c = parseInt($('#filecount').attr('value')) + 1;
					$('#filecount').attr('value',c);

				    $('#' + buttonId).click(function(event){
					});

					file=file.replace(/\,/g,"，").replace(/\_/g,"＿");
					$('#' + buttonId).after('<input id=\''+ufilename+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
						    + '<input  id=\''+uofilename+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+file+'\'/>'
						    );
					var tid = "";
					if(buttonId.replace("uploadBtn_","").length != buttonId.length){
						tid = "_"+buttonId.replace("uploadBtn_","");
						$('#' + buttonId).after('<input id=\''+ufilename+tid+'\' name=\''+ufilename+'\' type=\'hidden\' value=\''+newFileName+'\'/>'
						    + '<input  id=\''+uofilename+tid+'\' name=\''+uofilename+'\' type=\'hidden\' value=\''+file+'\'/>');
					}

					this.enable();
				    if(onsuccess){
				    	//回调函数（一般用来显示预览图片）
				    	onsuccess(suc.preUrlPath,file,newFileName,savUrlPath);
				    }

				}
			}
		});
	},

	/**
	 * 删除已经上传的临时文件，为内部调用，对应于initUpload 自动生成的删除链接
	 * e 文件“删除”链接元素对象
	 * moduleFlag 模块标识
	 * fileName 生成的文件名
	 * maxCount 页面上最多允许上传的文件数
	 * buttonId 页面上传按钮元素ID
	 */
	_delfile : function(/*element*/e, /*string*/moduleFlag, /*string*/ fileName, /*int*/maxCount, /*string*/ buttonId){
		$.ajax({
			type: "POST",
			url: WEB_CONTEXT+'/school/base/upload.do?action=removeTempFile',
			data: '&fileName=' + fileName + '&moduleFlag=' + moduleFlag,
			success: function(result){
					var c = parseInt($('#filecount').attr('value')) -1;
					$(e).parent().remove();
					$('#filecount').attr('value',c);
					if(c < (maxCount || 1)){
						$('#' + buttonId).show();
					}
				}
		});
	},
	/**
	 * 删除已经上传的临时文件，为内部调用，对应于initUpload 自动生成的删除链接
	 * e 文件“删除”链接元素对象
	 * moduleFlag 模块标识
	 * fileName 生成的文件名
	 * maxCount 页面上最多允许上传的文件数
	 * buttonId 页面上传按钮元素ID
	 */
	_delfileNew : function(/*element*/e, /*string*/moduleFlag, /*string*/ fileName, /*int*/maxCount, /*string*/ buttonId){
		$.ajax({
			type: "POST",
			url: WEB_CONTEXT+'/school/base/upload.do?action=removeTempFile',
			data: '&fileName=' + fileName + '&moduleFlag=' + moduleFlag,
			success: function(result){
					var c = parseInt($('#filecount').attr('value')) -1;
					$(e).parent().remove();
					$('#filecount').attr('value',c);
					if(c < (maxCount || 1)){
						$('#' + buttonId).uploadify('disable',false);
					}
				}
		});
	},
	/**
	 * 仅删除页面上的附件，例如在OA邮件中，修改草稿邮件的本身已经带有的附件文件
	 * e 文件“删除”链接元素对象
	 */
	_delfileFileFromPage : function(/* element */e) {
		var c = parseInt($('#filecount').attr('value')) - 1;
		if(c < 0) c=0;
		$(e).parent().remove();
		$('#filecount').attr('value', c);
	},


	/**
	 * 表单可提交元素过滤器.
	 */
	_formFilter : function(/*object*/node) {
		var type = (node.type || "").toLowerCase();
		return !node.disabled && node.name && !($.inArray(type, ["file", "submit", "image", "reset", "button"]) > -1);
	},


	/**
	 * 将指定form节点的所有可提交元素组合成字符串返回
	 */
	_getFormValues : function(/*string*/formId) {
		var formNode = $('#' + formId)[0];
		if ((!formNode) || (!formNode.tagName) || (!formNode.tagName.toLowerCase() == "form")) {
			base.alert('请指定一个正确的form节点！');
			return null;
		}
		var values = [];
		for (var i = 0; i < formNode.elements.length; i++) {
			var elm = formNode.elements[i];
			if (!elm || elm.tagName.toLowerCase() == "fieldset" || !this._formFilter(elm)) {
				continue;
			}
			var name = elm.name;
			var type = elm.type.toLowerCase();
			if (type == "select-multiple") {
			//	base.Debug("base._getFormValues[ select-multiple 控件 ]:options.length:"+elm.options.length);
				for (var j = 0; j < elm.options.length; j++) {
					if (elm.options[j].selected) {
						values.push(name + "=" + elm.options[j].value);
					}
				}
			} else if(type == "textarea"){
				values.push(name + "=" + $('#'+elm.id).val());
			} else {
			//	base.Debug("base._getFormValues[ radio, checkbox 控件 ]");
				if ($.inArray(type, ["radio", "checkbox"]) > -1) {
					if (elm.checked) {
						values.push(name + "=" + elm.value);
					}
				} else {
					values.push(name + "=" + elm.value);
				}
			}

		}
		var inputs = $("input");
		for (var i = 0; i < inputs.length; i++) {
			var input = inputs[i];
			if (input.type.toLowerCase() == "image" && input.form == formNode && this._formFilter(input)) {
				var name = input.name;
				values.push(name + "=" + input.value);
				values.push(name + ".x=0");
				values.push(name + ".y=0");
			}
		}

		return values.join("&");
	},

	/*firefox 中可以打印 */
	Debug:function(message){
	   if($.browser.mozilla)
	      console.log("[DEBUG]:"+message);
	},


	/*下拉列表中的选项  左右 移动*/
    selectOptionsMove:function(srcSelector /*源下拉列表*/,dstSelector /*目标下拉列表*/,isDeleteSrc/*是否要移除源下拉框中的值*/){
    	$("#"+srcSelector+" option:selected").each(function(){

    		  if(!base.selectOptionIsRepeated(dstSelector,$(this).val()))
   		         $("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>").appendTo($("#"+dstSelector));

   			  if(isDeleteSrc||isDeleteSrc=='true'||isDeleteSrc=='TRUE')
   		           $(this).remove();
       });
    },


    /*判断下拉列表中的元素是否重复*/
    selectOptionIsRepeated:function(selectorId,val){
    	var isRepeated = false;
    	$("#"+selectorId+" option").each(function(){
    		if($(this).val()==val){
    			isRepeated = true;
    		}
    	});
    	return isRepeated;
    },


    /*下拉列表全选*/
    allSelected:function(selectId,isSelected){
    	$("#"+selectId+" option").each(function(){
    		$(this).attr("selected",isSelected);
    	});
    },


    //自动绑定页面上已经添加的提示标识，与精灵控件有关联
    //定义在对应的标签上支持解释  mytips 属性
    autoMacthTipHandle:function(){
    	if(!window.parent.smart)
    	    return;
	    
    	$("a[mytips]").each(function(index){
			   var type = $(this).attr("mytips");
			   var obj = $(this)[0];
			   $(this).mouseover(function(event){
				 //   base.Debug("autoMacthTipHandle_<a>："+type);
				    window.parent.smart.setUp(obj);
			   });

		});

	    $("li[mytips]").each(function(index){
	    	   var type = $(this).attr("mytips");
			   var obj = $(this)[0];
			   $(this).mouseover(function(event){
				  //  base.Debug("autoMacthTipHandle_<li>："+type);
				    window.parent.smart.setUp(obj);
			   });
		});

	    $("input[mytips]").each(function(index){
	    	 var type = $(this).attr("mytips");
			 var obj = $(this)[0];
			 $(this).mouseover(function(event){
				 //   base.Debug("autoMacthTipHandle_<input>："+type);
				    window.parent.smart.setUp(obj);
			 });
		});

	    $("div[mytips]").each(function(index){
	    	 var type = $(this).attr("mytips");
			 var obj = $(this)[0];
			 $(this).mouseover(function(event){
				   // base.Debug("autoMacthTipHandle_<div>："+type);
				    window.parent.smart.setUp(obj);
			 });
		});
	},


	//检测用户的浏览器
	detectBrowser:function (){
		var browser=navigator.appName;
		var b_version=navigator.appVersion;
		var version=parseFloat(b_version);
		this.Debug("用户当前的浏览器版信息:"+browser+"  "+b_version);
		///alert("用户当前的浏览器版信息:"+browser+"  "+b_version);
		if ((browser=="Netscape"||browser=="Microsoft Internet Explorer")&&(version>=4)){
			this.Debug("Your browser is good enough!")
		}else{
			alert("It's time to upgrade your browser!")
		}
     },

     /*文件下载*/
     downLoadFile:function(srcFilePath/*服务器的文件全路径*/,fileName/*文件重命名*/){
    	 srcFilePath = encodeURI(srcFilePath);
    	 fileName = encodeURI(fileName);
    	 window.location=WEB_CONTEXT+"/school/base/download.do?action=load&filePath="+srcFilePath+"&orFileName="+fileName;
     },

     /*打包zip文件下载*/
     downLoadFileZip:function(srcFilePath/*服务器的文件全路径（逗号分隔）*/,fileName/*文件重命名*/,fileNameStr/*文件列表的名称【用逗号分隔】*/){
    	 srcFilePath = encodeURI(srcFilePath);
    	 fileName = encodeURI(fileName);
    	 window.location=WEB_CONTEXT+"/school/base/download.do?action=loadZip&filePathStr="+srcFilePath+"&orFileName="+fileName+"&fileNameStr="+fileNameStr;
     },
     

     /*检查用户session过期问题*/
     checkUserLoginState:function(response){
    	 try{
    	   if(response && !response.user_permission_check)
    	       json = base.json(response);
    	   else
    		   json = response;
    	   if(json && json.user_permission_check=='on'){
    		   alert(json.user_login_msg);
    		   if(parent.window)
    			   parent.window.location.href = "/index.htm";
    		   else
    		       window.location.href = "/index.htm";
    		   return false;
    	   }else{
    		   return true;
    	   }
    	 }catch(e){

    	 }
    	 return true;
     },
     
     /**
      * 刷新图片，慎用
      * @return
      */
     refreshImg:function (){
    		$("img").each(function(){
    			if($(this).attr("src")!='' && $(this).attr("src").indexOf("?")<0 )
    				$(this).attr("src",$(this).attr("src")+"?"+new Date().getTime());
    		});
    }

}

//生成base类实例
var base = null;
$(document).ready(function(){
	base = new Base();
	//绑定页面上的提示信息
	base.autoMacthTipHandle();
});