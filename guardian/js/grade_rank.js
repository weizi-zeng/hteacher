  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'grank_id',
            actionUrl: 'grade_rank.php'
        };
        //页面加载
        $(function () {
            pageInit();
            loadGrid();
        });
        
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
				  { field: 'grank_id', title: 'ID', hidden: true },
				  { field: 'prj_id', title: '考试名称ID', hidden: true },
				  { field: 'prj_name', title: '考试名称', width: 120, sortable: true, align: 'center' },
                  { field: 'student_code', title: '学号', width: 60, sortable: true, align: 'center',
                	  sorter:function(a,b){  
	              		  return parseFloat(a)>parseFloat(b)?1:-1;
	              	  }  
                  },
                  { field: 'student_name', title: '学生', width: 60, sortable: true, align: 'center' },
	          	  { field: 'grade_rank', title: '年级排名', width: 60, sortable: true, align: 'center',
	              	  sorter:function(a,b){  
	              		  return parseFloat(a)>parseFloat(b)?1:-1;
	              	  }
	                },
	                { field: 'up_down', title: '年级进退', width: 60, sortable: true, align: 'center',
		              	  sorter:function(a,b){  
		              		  return parseFloat(a)>parseFloat(b)?1:-1;
		              	  }
		                },
                  { field: 'created', title: '创建日期', width: 120, sortable: true, align: 'center' }
                  ]],
                  toolbar: "#toolbar",
                onBeforeLoad: function (param) {
                    me.search_form.find('select').each(function (index) {
                        param[this.name] = $(this).val();
                    });
                }
            });
        }
        
        
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
            
            $("#add").click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	addScore(e);
            });
        }

    //操作函数
    //新增
    function add() {
        clear();
        $("#add_window").window('open');
    }
    
    //修改
    function update() {
        var rows = me.dgData.datagrid('getSelections');
        if (rows.length == 0) {
        	showError('请选择一条记录进行操作!');
            return;
        }
        if (rows.length > 1) {
        	showError('只能对一条记录进行操作!');
            return;
        }
        
        var row = rows[0];
    	
    	$("#grank_id").val(row.grank_id);
    	$("#exam_prj").val(row.prj_id);
    	$("#student_code").val(row.student_code);
    	$("#grade_rank").numberbox("setValue",row.grade_rank);
    	$("#up_down").numberbox("setValue",row.up_down);
         me.edit_window.window('open');
    }

    //批量添加成绩
    function addScore(e){
    	if ($("#add_form").form('validate')) {
            $.ajax({
                url: me.actionUrl + '?act=ajax_add',
                data: $("#add_form").serialize(),
                success: function (r) {
                    if (r) {
                    	if(r.error==0){
                    		showInfo(r.content);
                    		me.dgData.datagrid('reload');
                    		$("#add_window").window('close');
                    	}else {
                    		showError(r.message);
                    	}
                    }
                },
                complete:function(){
                	clearLoading();
                	$("#add").linkbutton('enable');
                }
            });
            showLoading(e);
        	$("#add").linkbutton('disable');
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
    	$("#add_form").find("input").each(function(i,e){
    		$(e).val('');
    	});
    }

     //数据删除
    function deletegrade_rank() {
        var rows = me.dgData.datagrid('getSelections'); 
        if (rows.length == 0) { 
            showError('请选择记录进行操作!'); 
            return;
        } 
        
        var ids = "";
        for(var i=0;i<rows.length;i++) 
        { 
        	ids += rows[i][me.idFiled];
        	if(i<rows.length-1){
        		ids += ',';
        	}
        } 
        
        if (ids=="")
        {
            showError('选择的记录ID为空!');
            return;
        }
        $.messager.confirm('提示信息', '确认要删除选择项？', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "grank_id": ids },
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
    
    function refreshgrade_ranks(){
    	//清空查询条件
    	$("#search_prj_id").val('');
    	$("#search_student_code").val('');
    	searchgrade_ranks();
    }
    
    function searchgrade_ranks(){
    	$('#dgData').datagrid('reload');
    }
    
    function importgrade_ranks(){
    	$('#import_window').window('open');
    }
    
    function importgrade_ranks_import(){
    	$('#import_form').submit();
    }
    
    //下载模板
    function template(){
    	window.location.href='./templates/grade_rankTemplate.csv';
    }
    
    //按查询条件导出
    function exportRank(){
    	var student_code = $("#search_student_code").val();
    	var prj_id = $("#search_prj_id").val();
    	var charset = $("#charset").val();
    	if (!charset) 
        { 
            showError('请选择导出所需的编码格式!'); 
            $("#charset").focus();
            return;
        } 
    	window.open("grade_rank.php?act=export&student_code="+student_code+"&prj_id="+prj_id+"&charset="+charset);
    }