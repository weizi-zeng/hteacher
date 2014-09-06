  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'prj_id',
            actionUrl: 'exam_prj.php'
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
                remoteSort: false,  //列少设为true,列多设为false
                autoRowHeight: false,
                singleSelect:true,
                pagination: true,
                pageSize: 25,
                pageList: [15, 25, 50, 100, 200],
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'prj_id', title: 'ID', hidden: true },
				  { field: 'code', title: '项目编号', width: 120, sortable: true, align: 'center' },
                  { field: 'name', title: '考试名称', width: 120, sortable: true, align: 'center' },
                  { field: 'sdate', title: '考试开始日期', width: 120, sortable: true, align: 'center' },
                  { field: 'edate', title: '考试结束日期', width: 120, sortable: true, align: 'center' },
                  { field: 'closed', title: '是否归档', width: 120, sortable: true, align: 'center',
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
        	
        	$("#prj_id").val(row.prj_id);
        	$("#code").val(row.code);
        	$("#name").val(row.name);
        	
        	$("#sdate").datebox("setValue",row.sdate);
        	$("#edate").datebox("setValue",row.edate);
        	
        	if(row.closed=="1"){
        		$('[name="closed"]:radio').each(function() {   
                    if (this.value == '1'){   
                       this.checked = true;
                    }else {
                       this.checked = false;
                    }
                 });
        	}else {
        		$('[name="closed"]:radio').each(function() {   
                    if (this.value == '0'){   
                       this.checked = true;
                    }else {
                       this.checked = false;
                    }
                 });
        	}
        	
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
    	
    	$("#prj_id").val("");
    	$("#sdate").val("");
    	$("#sdate").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deleteexam_prj() {
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
                    data: { "prj_id": ids },
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
    
    
    function searchexam_prjs(){
    	$('#dgData').datagrid('reload');
    }
    
    //获取当前选中的ids
    function getIds(){
    	var selects = $('#dgData').datagrid('getSelections');
    	if(selects.length==0){
    		showError("请选择记录进行操作");
    		return false;
    	}
    	var ids = '';
    	for(var i=0;i<selects.length;i++){
    		ids += selects[i].prj_id;
    		if(i<selects.length-1){
    			ids += ",";
    		}
    	}
    	
    	return ids;
    }
