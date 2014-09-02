  var me = {
            dgData: null,
            search_form: null,
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
            me.search_form = $('#search_form');
            me.dgData = $('#dgData');
            $('#save').click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	save(e);
            });
        }

    //操作函数
    //新增
    function add() {
        clear();
        $('#btn_edit_ok').show();
        me.edit_window.window('open');
    }
    
    //修改
    function update() {
        var rows = me.dgData.datagrid('getSelections');
        if (rows.length > 0) {
        	var row = rows[0];
        	
        	$("#course_id").val(row.course_id);
        	$("#semster").val(row.semster);
        	$("#weekday").val(row.weekday);
        	$("#stage").val(row.stage);
           
        	$("#stime").val(row.stime);
        	$("#etime").val(row.etime);
        	
        	$("#subject").val(row.subject);
        	$("#teacher").val(row.teacher);
        	$("#classroom").val(row.classroom);
        	
        	 $('#btn_edit_ok').show();
             me.edit_window.window('open');
             
        } else {
            showError('请选择一条记录进行操作!');
            return;
        }
    }

    //保存
    function save(e) {
        if (me.edit_form.form('validate')) {
            $.ajax({
                url: me.actionUrl + '?act=ajax_save',
                data: me.edit_form.serialize(),
                success: function (r) {
                    if (r) {
                    	if(r.error==0){
                    		showInfo(r.content);
                    		me.dgData.datagrid('reload');
                    		me.edit_window.window('close');
                    	}else {
                    		showError(r.message);
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
    }
    
    //清空界面数据
    function clear() {
    	
    	$("#course_id").val("");
    	$("#stime").val("");
    	$("#etime").val("");
    	$("#subject").val("");
    	
    	$("#teacher").val("");
    	$("#classroom").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deletecourse() {
        var ids = "";
        var rows = me.dgData.datagrid('getSelections'); 
        if (rows.length == 0) { 
            showError('请选择一条记录进行操作!'); 
            return;
        } 
        
        if (rows.length>1) 
        { 
            showError('请选择一条记录进行操作!'); 
            return;
        } 
        
        ids=rows[0][me.idFiled];
        if (ids=="")
        {
            showError('选择的记录ID为空!');
            return;
        }
        var stage=rows[0]["stage"];
//        var subject=rows[0]["subject"];
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+ stage + '】', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "course_id": ids },
                    success: function (r) {
                    	 if (r) {
                         	if(r.error==0){
                         		showInfo(r.content);
                         		me.dgData.datagrid('reload');
                         	}else {
                         		showError(r.message);
                         	}
                         }
                    }
                });
            }
        })
    }
    
    
    function searchcourses(){
    	$('#dgData').datagrid('reload');
    }
    
    function exportcourses(){
    	var search_semster = $('#search_semster').val();
    	window.open("course.php?act=export&order=asc&rows=1000&search_semster="+search_semster);
    }
    
    
    