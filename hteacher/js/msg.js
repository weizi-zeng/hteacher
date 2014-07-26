  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'msg_id',
            actionUrl: 'user_msg.php'
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
                pageList: [2, 4, 5, 15, 30, 45, 60, 100],
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                rowStyler: function (index, row) {
                    if (row && row.msg_status==1) {
                        return { style: 'color:#13C70B'};
                    }
                },
                columns: [[
				  { field: 'msg_id', title: 'ID', hidden: true },
				  { field: 'msg_title', title: '标题', width: 220, sortable: true, align: 'center' },
                  { field: 'msg_content', title: '内容', width: 320, sortable: true, align: 'center' },
                  { field: 'msg_status', title: '是否回复', width: 80, sortable: true, align: 'center',
                	  formatter: function (value, rowData, rowIndex) {
                		  return value>0?"是":"否";
                	  },
                  },
                  { field: 'msg_reply', title: '回复内容', width: 320, sortable: true, align: 'center' },
                  { field: 'msg_time', title: '创建日期', width: 120, sortable: true, align: 'center' }
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
    	
    	$("#msg_title").val("");
    	$("#msg_content").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deletemsg() {
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
        var name=rows[0]["msg_title"];
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+ name + '】', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "msg_id": ids },
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
    
    
    function searchmsgs(){
    	$('#dgData').datagrid('reload');
    }
    
    
    function checkmsgs(){
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
        
        window.location.href = "msg.php?act=view&msg_id="+ids;
    }
    
    