$(function(){
	$("#search_btn").click(function(e){
		if ($(this).hasClass("l-btn-disabled")) {
	        return;
	    }
		loadGrid(e);
	});
	
	//默认选择最后一个考试名称
	if(select_prj){
		$('#search_prj_id').val(select_prj);
	}else {
		$('#search_prj_id option:last').attr('selected','selected');
	}
	loadGrid();
});

//加载数据列表
function loadGrid() {
	var prj_id = $('#search_prj_id').val();
	if(!prj_id){
		showError("请选择考试名称！");
		return;
	}
	var prj_name = $("#search_prj_id").find("option:selected").text();
	
	$.ajax({
        url: 'score_summary.php?act=ajax_load',
        data: {prj_id:prj_id},
        success: function (r) {
        	var subjects = r.subjects;
        	var students = r.students;
        	
        	var html = '';
        	if(subjects.length<1){
        		html = "<font style='color:red;'>您选择的《"+prj_name+"》还没有进行考试安排！</font>";
        	}else {
        		html = '<div style="width:100%;text-align:center;font-size:20px;">《'+prj_name+'》考试成绩汇总</div>';
        		html += '<table cellspacing="0" cellpadding="0" style="width:96%;"><tbody>';
        		html += '<tr style="font-weight:bold;height:40px;background-color:rgb(143, 209, 209);">';
        		html += '<td style="text-align:center;width:5%;border:1px solid rgb(27, 240, 180)">学号</td>';
        		html += '<td style="text-align:center;width:10%;border:1px solid rgb(27, 240, 180)">姓名</td>';
        		for(var i=0;i<subjects.length;i++){
        			html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+subjects[i].subject+'</td>';
        		}
        		html += '<td style="text-align:center;width:8%;border:1px solid rgb(27, 240, 180)">总分</td>';
        		html += '<td style="text-align:center;width:8%;border:1px solid rgb(27, 240, 180)">年级排名</td>';
        		html += '<td style="text-align:center;width:8%;border:1px solid rgb(27, 240, 180)">年级进退</td>';
        		html += '</tr>';
        		
        		for(student_code in students){
        			var st = students[student_code];
        			
        			html += '<tr>';
        			html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+st.student_code+'</td>';//学号,姓名
        			html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+st.student_name+'</td>';
        			for(var j=0;j<subjects.length;j++){
        				var hasScore = false;
        				var subj = subjects[j].subject;
        				for (sub in st){
        					if(sub==subj){
        						html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+st[sub]+'</td>';
        						hasScore = true;
        						break;
        					}
        				}
        				if(!hasScore){
        					//如果没有对应科目的成绩，返回空数据
        					html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)"></td>';
        				}
        			}
        			html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+st.total+'</td>';
        			if(st.grade_rank){
        				html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+st.grade_rank+'</td>';
        			}else {
        				html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)"></td>';
        			}
        			if(st.up_down){
        				html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)">'+st.up_down+'</td>';
        			}else {
        				html += '<td style="text-align:center;border:1px solid rgb(27, 240, 180)"></td>';
        			}
        			html += '</tr>';
        		}
        		
        		html += "</tbody></table>";
        	}
        	$("#dgData").html(html);
        },
        complete:function(){
        	$("#search_btn").linkbutton('enable');
        }
    });
	$("#search_btn").linkbutton('disable');
}
	

//导出考试汇总
function exportScore(){
	var prj_id = $('#search_prj_id').val();
	if(!prj_id){
		showError("请选择考试名称！");
		return;
	}
	var charset = $("#charset").val();
	if (!charset) 
    { 
        showError('请选择导出所需的编码格式!'); 
        $("#charset").focus();
        return;
    } 
	window.location.href='score_summary.php?act=export&prj_id='+prj_id+'&charset='+charset;
}

//导出考试汇总模板
function templateScore(){
	var prj_id = $('#search_prj_id').val();
	if(!prj_id){
		showError("请选择考试名称！");
		return;
	}
	var charset = $("#charset").val();
	if (!charset) 
    { 
        showError('请选择导出所需的编码格式!'); 
        $("#charset").focus();
        return;
    } 
	window.location.href='score_summary.php?act=template&prj_id='+prj_id+'&charset='+charset;
}

function importScore_import(){
	$('#import_form').submit();
}
    