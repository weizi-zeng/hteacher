  var me = {
            dgData: null,
            idFiled: 'course_id',
            actionUrl: 'course.php'
        };
        //页面加载
        $(function () {
            pageInit();
            loadGrid();
        });
        
        //页面初始化
        function pageInit() {
            $('#save').click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	save(e);
            });
            $('#search_btn').click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	loadGrid(e);
            });
        }
        
        function loadGrid(e){
        	var param = {};
        	param.search_semster =$("#search_semster").val();
        	$.ajax({
        		url: me.actionUrl + '?act=ajax_list',
                data: param,
                success: function (r) {
                	if(r){
                		for(var name in r){
                			$("#"+name).val(r[name]);
                		}
                	}else {
                		$("#edit_form").find("input").val("");
                	}
                	$("#semster").text(param.search_semster+"课程表");
                },
                complete:function(){
                	$("#search_btn").linkbutton('enable');
                }
        	});
        	$("#search_btn").linkbutton('disable');
        }

    
    //保存
    function save(e) {
        $.ajax({
            url: me.actionUrl + '?act=ajax_save',
            data: $("#edit_form").serialize(),
            success: function (r) {
                if (r) {
                	if(r.error==0){
                		var id = r.message;
                		$("#course_id").val(id);
                		showInfo(r.content);
                	}else {
                		showError(r.content);
                	}
                }
            },
            complete:function(){
            	clearLoading();
            	$("#save").linkbutton('enable');
            }
        });
        showLoading(e);
    	$("#save").linkbutton('disable');
    }
    
    function exportcourses(){
    	var search_semster = $('#search_semster').val();
    	window.open("course.php?act=export&order=asc&rows=1000&search_semster="+search_semster);
    }
    
    
    