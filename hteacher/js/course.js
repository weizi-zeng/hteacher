  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
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
            me.edit_window = $('#edit_window');
            me.edit_form = me.edit_window.find('#edit_form');
            me.search_form = $('#search_form');
            me.dgData = $('#dgData');
        }

        //加载数据列表
        function loadGrid() {
            me.dgData.datagrid({
                url: me.actionUrl + '?act=ajax_list', //me.actionUrl + '?Method=List', 
                method: 'post',
                fitColumns: true, 
                remoteSort: false,  //列少设为true,列多设为false
                autoRowHeight: false,
                pagination: true,
                pageSize: 15,
                pageList: [2, 4, 5, 15, 30, 45, 60],
                singleSelect: true,
                nowwarp: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'course_id', title: 'ID', hidden: true },
                  { field: 'semster', title: '学期', width: 120, sortable: true, align: 'center' },
                  { field: 'weekname', title: '星期', width: 120, sortable: true, align: 'center' },
                  { field: 'stage', title: '课程', width: 120, sortable: true, align: 'center' },
                  { field: 'setime', title: '上课起止时间', width: 150, sortable: true, align: 'center' },
                  //$content = "序号,学期,课程,上课起止时间,科目,科教老师,所在教室,创建日期\n";
                  { field: 'subject', title: '科目', width: 120, sortable: true, align: 'center' },
                  { field: 'teacher', title: '科教老师', width: 120, sortable: true, align: 'center' },
                  { field: 'classroom', title: '所在教室', width: 150, sortable: true, align: 'center' },
                  { field: 'created', title: '创建日期', width: 220, sortable: true, align: 'center' }
                  ]],
                  
                  toolbar: "#toolbar",
                  
                onBeforeLoad: function (param) {
                    me.search_form.find('select').each(function (index) {
                        param[this.name] = $(this).val();
                    });
                }
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
    function save() {
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
                }
            });
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
        var name=rows[0]["name"];
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+ name + '】', function (isClickedOk) {
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
    
    
    