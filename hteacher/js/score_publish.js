
//发布公告
function publish(){
	var prj_code = $('#search_prj_code').val();
	if(!prj_code){
		showError("请选择考试项目！");
		return;
	}
	window.location.href='score_publish.php?act=publish&prj_code='+prj_code;
}

//短信通知
function sendSMS(){
	var prj_code = $('#search_prj_code').val();
	if(!prj_code){
		showError("请选择考试项目！");
		return;
	}
	window.location.href='score_publish.php?act=sendSMS&prj_code='+prj_code;
}
    