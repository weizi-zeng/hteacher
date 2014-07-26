  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'duty_id',
            actionUrl: 'duty.php'
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
                method: 'post',
                fitColumns: true, 
                singleSelect:true,
                remoteSort: false,  //列少设为true,列多设为false
                autoRowHeight: false,
                checkOnSelect:true,
                pagination: true,
                pageSize: 15,
                pageList: [2, 4, 5, 15, 30, 45, 60],
                nowwarp: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'duty_id', title: 'ID', hidden:true },
				  { field: 'student_code', title: '学生学号', width: 120, sortable: true, align: 'center' },
                  { field: 'student_name', title: '学生姓名', width: 120, sortable: true, align: 'center' },
                  { field: 'duty_item', title: '量化项目', width: 120, sortable: true, align: 'center' },
                  { field: 'score', title: '记录分数', width: 100, sortable: true, align: 'center' },
                  { field: 'date_', title: '事发日期', width: 120, sortable: true, align: 'center' },
                  { field: 'desc_', title: '备注描述', width: 220, sortable: true, align: 'center' },
                  { field: 'created', title: '创建时间', width: 120, sortable: true, align: 'center' }
                  ]],
                  
                  toolbar: "#toolbar",
                  
                onBeforeLoad: function (param) {
                    me.search_form.find('input').each(function (index) {
                        param[this.name] = $(this).val();
                    });
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
        	if(rows.length >1){
        		showError('请选择一条记录进行操作!'); 
        		return;
        	}
        	var row = rows[0];
        	
        	$("#duty_id").val(row.duty_id);
        	
        	$("#student_code").val(row.student_code);
        	$("#duty_item").val(row.duty_item);
        	$('#score').numberbox('setValue', row.score);
        	
        	$("#desc_").val(row.desc_);
        	
        	$("#date_").datebox("setValue",row.date_);

        	
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
                	clearLoading();
                	$("#save").linkbutton('enable');
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
            showLoading(e);
        	$("#save").linkbutton('disable');
        }
    }
    
    //清空界面数据
    function clear() {
    	
    	$("#duty_id").val("");
    	
    	$("#duty_item").val("");
    	
    	$('#score').numberbox('setValue', 0);
    	
    	$("#desc_").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deleteduty() {
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
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+rows[0]["student_code"] +','+rows[0]["duty_item"]+'】', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "duty_id": ids },
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
    
    
    function searchdutys(){
    	$('#dgData').datagrid('reload');
    }
    
    
    function exportdutys(){
    	var student_code = $("#search_student_code").val();
    	var duty_item = $("#search_name").val();
    	var sdate = $("#search_sdate").datebox('getValue');
    	var edate = $("#search_edate").datebox('getValue');
    	
    	window.open("duty.php?act=exportdutys&order=asc&rows=2000&search_student_code="+student_code+"&search_name="+duty_item+"&search_sdate="+sdate+"&search_edate="+edate);
    }
    
    function exportRank(){
    	var sdate = $("#search_sdate").datebox('getValue');
    	if (!sdate) 
        { 
            showError('请选择起始日期!'); 
            $("#search_sdate").datebox('showPanel');
            return;
        } 
    	var edate = $("#search_edate").datebox('getValue');
    	if (!edate) 
        { 
    		showError('请选择截止日期!'); 
    		$("#search_edate").datebox('showPanel');
            return;
        } 
    	window.open("duty.php?act=exportRank&order=asc&rows=2000&search_sdate="+sdate+"&search_edate="+edate);
    }