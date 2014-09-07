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
            	if ($(this).linkbutton("options").disabled) {
                    return;
                }
            	save(e);
            });
            
            $('#add').click(function(e){
            	if ($(this).linkbutton("options").disabled) {
                    return;
                }
            	addduty(e);
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
                pageSize: 25,
                pageList: [15, 25, 50, 100, 200],
                nowrap: false,  //折行
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
        $("#add_window").window('open');
    }
    
    function clear(){
    	$("input[name=student_code]").each(function(i,e){
			e.checked = false;
		});
		$("input[name=duty_item]").each(function(i,e){
				e.checked = false;
				var duty_item_id =  $(e).attr("duty_item_id");
				$("#"+duty_item_id+"_date_").datebox('setValue','');
				
				$(e).closest('tr').find('input[name=score]').val('');
				$(e).closest('tr').find('input[name=date_]').val('');
				$(e).closest('tr').find('input[name=desc_]').val('');
		});
    }
    
    //新增
    function addduty(e) {
    	var students = "";
    	$("input[name=student_code]").each(function(i,e){
    		if(e.checked){
    			students += $(e).val()+',';
    		}
    	});
    	if(students.length>0){
    		students = students.substr(0,students.length-1);
    	}else {
    		showError("请勾选学生!");
    		return ;
    	}
//    	console.dir(students);
    	
    	var duty_items = "";
    	$("input[name=duty_item]").each(function(i,e){
    		if(e.checked){
    			duty_items += $(e).val()+'###SPLIT_V2###';
    			duty_items += $(e).closest('tr').find('input[name=score]').val()+'###SPLIT_V2###';
    			duty_items += $(e).closest('tr').find('input[name=date_]').val()+'###SPLIT_V2###';
    			duty_items += $(e).closest('tr').find('input[name=desc_]').val()+'###SPLIT_V1###';
    		}
    	});
    	if(duty_items.length>0){
    		duty_items = duty_items.substr(0,duty_items.length-'###SPLIT_V1###'.length);
    	}else {
    		showError("请选择项目!");
    		return ;
    	}
    	
    	var params = {};
    	params.students = students;
    	params.duty_items = duty_items;
    	
        if ($("#add_form").form('validate')) {
            $.ajax({
                url: me.actionUrl + '?act=ajax_add',
                data: params,
                success: function (r) {
                    if (r) {
                    	if(r.error==0){
                    		showInfo(r.content);
                    		me.dgData.datagrid('reload');
                    		$("#add_window").window('close');
                    		clear();
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