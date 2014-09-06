  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'score_id',
            actionUrl: 'score.php'
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
            $('#save').click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	save(e);
            });
        }

        //加载数据列表
        function loadGrid() {
            me.dgData.datagrid({
                url: me.actionUrl + '?act=ajax_list', //me.actionUrl + '?Method=List', 
                method: 'get',
                fitColumns: true, 
                remoteSort: false,  //列少设为true,列多设为false
                autoRowHeight: false,
                rownumbers:true,
                pagination: true,
                pageSize: 25,
                pageList: [15, 25, 50, 100, 200],
                singleSelect: true,
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'score_id', title: 'ID', hidden: true },
				  { field: 'exam_code', title: '考试编号', width: 150, sortable: true, align: 'center' },
				  { field: 'prj_code', title: '考试名称', width: 150, sortable: true, align: 'center' },
                  { field: 'exam_subject', title: '考试科目', width: 150, sortable: true, align: 'center' },
                  { field: 'student_code', title: '学号', width: 120, sortable: true, align: 'center' },
                  { field: 'student_name', title: '学生', width: 120, sortable: true, align: 'center' },
                  { field: 'score', title: '分数', width: 60, sortable: true, align: 'center',
                	 
                	  sorter:function(a,b){  
                		  return parseFloat(a)>parseFloat(b)?1:-1;
                	  }
                  
                  },
                  { field: 'add_score', title: '附加分数', width: 150, sortable: true, align: 'center' },
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
        	
        	$("#score_id").val(row.score_id);
        	$("#exam_code").val(row.exam_code);
        	$("#student_code").val(row.student_code);
        	
        	$("#score").numberbox("setValue",row.score);
        	$("#add_score").numberbox("setValue",row.add_score);
        	
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
    	
    	$("#score_id").val("");
    	$("#score").val("");
    	
        $('form').form('validate');
    }

     //数据删除
    function deletescore() {
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
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+'】', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "score_id": ids },
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
    
    
    function searchscores(){
    	$('#dgData').datagrid('reload');
    }
    
    function importscores(){
    	$('#import_window').window('open');
    }
    
    function importscores_import(){
    	$('#import_form').submit();
    }
    
    //下载模板
    function template(){
    	window.location.href='./templates/scoreTemplate.csv';
    }
    
    //根据考试名称进行导出
    function exportscoresbyexamname(){
    	var prj_code = $("#search_prj_code").val();
    	if(!prj_code){
    		showError('请选择考试名称!'); 
            return;
    	}
    	window.open("score.php?act=exportbyexamname&order=asc&rows=2000&search_prj_code="+prj_code);
    }
    
    function exportscoresbyexamcode(){
    	var exam_code = $("#search_exam_code").val();
    	if(!exam_code){
    		showError('请选择考试编码!'); 
            return;
    	}
    	window.open("score.php?act=exportbyexamcode&order=asc&rows=2000&search_exam_code="+exam_code);
    }
    
    function exportscoresbystudentcode(){
    	var student_code = $("#search_student_code").val();
    	if(!student_code){
    		showError('请选择学生学号!'); 
            return;
    	}
    	var prj_code = $("#search_prj_code").val();
    	window.open("score.php?act=exportbystudentcode&order=asc&rows=2000&search_student_code="+student_code+"&search_prj_code="+prj_code);
    }