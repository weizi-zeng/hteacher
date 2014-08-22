  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'notice_id',
            actionUrl: 'notice.php'
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
                singleSelect:true,
                remoteSort: false,  //列少设为true,列多设为false
                autoRowHeight: false,
                checkOnSelect:true,
                pagination: true,
                pageSize: 15,
                pageList: [2, 4, 5, 15, 30, 45, 60, 100],
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                sortOrder:'desc',
                rowStyler: function (index, row) {
                    if (row && row.urgency==1) {
                        return { style: 'color:#F08080'};
                    }
                },
                columns: [[
				  { field: 'notice_id', title: 'ID', hidden: true },
				  { field: 'title', title: '标题', width: 220, sortable: true, align: 'center',
					  formatter: function (value, rowData, rowIndex) {
                		  return "<a href='notice.php?act=view&notice_id="+rowData.notice_id+"' target='_blank' >"+value+"</a>";
                	  }
                  },
                  //{ field: 'type', title: '类型', width: 120, sortable: true, align: 'center' },
                  { field: 'urgency', title: '重要性', width: 120, sortable: true, align: 'center',
					  formatter: function (value, rowData, rowIndex) {
                		  return value+"级重要";
                	  }
                  },
                  { field: 'author', title: '作者', width: 120, sortable: true, align: 'center' },
                  { field: 'is_active', title: '是否显示', width: 120, sortable: true, align: 'center',
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"是":"否";
                	  },
                  },
                  { field: 'created', title: '创建日期', width: 220, sortable: true, align: 'center' }
                  ]],
                  
                  toolbar: "#toolbar",
                  
                onBeforeLoad: function (param) {
                    me.search_form.find('input').each(function (index) {
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
        	
        	$("#notice_id").val(row.notice_id);
        	$("#code").val(row.code);
        	$("#name").val(row.name);
        	
        	$("#noticedate").datebox("setValue",row.noticedate);
           
        	$("#stime").val(row.stime);
        	$("#etime").val(row.etime);
        	
        	$("#subject").val(row.subject);
        	$("#teacher").val(row.teacher);
        	$("#classroom").val(row.classroom);

        	if(row.closed=="1"){
        		$("input[name=closed][value=0]").removeProp("checked");
        		$("input[name=closed][value=1]").prop("checked","checked");
        		
        	}else {
        		$("input[name=closed][value=1]").removeProp("checked");
        		$("input[name=closed][value=0]").prop("checked","checked");
        	}
        	
        	 $('#btn_edit_ok').show();
             me.edit_window.window('open');
             
        } else {
            showError('请选择一条记录进行操作!');
            return;
        }
    }

    
    function savecheck(o){
    	if ($(o).hasClass("l-btn-disabled")) {
            return;
        }
    	return me.edit_form.form('validate');
    }
    
    function savesubmit(o){
    	$(o).linkbutton('disable');
    	return me.edit_form.submit();
    }
    
    //清空界面数据
    function clear() {
    	
    	$("#notice_id").val("");
    	$("#stime").val("");
    	$("#etime").val("");
    	//$("#subject").val("");
    	
    	$("#teacher").val("");
    	//$("#classroom").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deletenotice() {
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
        var name=rows[0]["title"];
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+ name + '】', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "notice_id": ids },
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
    
    
    function searchnotices(){
    	$('#dgData').datagrid('reload');
    }
    
    
    function checknotices(){
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
        
        window.location.href = "notice.php?act=view&show_back=1&notice_id="+ids;
    }
    
    