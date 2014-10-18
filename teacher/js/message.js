  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'message_id',
            actionUrl: 'message.php'
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
            $('#btn_add').click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	addMessage(e);
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
                rowStyler: function (index, row) {
                    if (row && row.replys>0) {
                        return { style: 'color:#13C70B'};
                    }
                },
                columns: [[
				  { field: 'message_id', title: 'ID', hidden: true },
                  { field: 'message', title: '内容', width: 320, sortable: true, align: 'center',
					  formatter: function (value, rowData, rowIndex) {
                		  return '<a href="message.php?act=view&message_id='+rowData['message_id']+'" target="_blank">'+value+'</a>';
                	  },  
                  },
                  { field: 'replys', title: '回复数量', width: 80, sortable: true, align: 'center' },
                  { field: 'to_user', title: '收件人', width: 80, sortable: true, align: 'center' },
                  { field: 'from_user', title: '发送人', width: 80, sortable: true, align: 'center' },
                  { field: 'created', title: '创建时间', width: 120, sortable: true, align: 'center' }
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
        $('#add_window').window('open');
    }

    //回复
    function reply(){
        var rows = me.dgData.datagrid('getSelections'); 
        if (rows.length == 0) { 
            showError('请选择一条记录进行回复!'); 
            return;
        } 
        if (rows.length>1) 
        { 
            showError('请选择一条记录进行回复!'); 
            return;
        } 
        var row = rows[0];
        window.open("message.php?act=view&message_id="+row['message_id']);
    }
    
    
    //添加信息消息
    function addMessage(e){
    	if ($('#add_form').form('validate')) {
            $.ajax({
                url: me.actionUrl + '?act=ajax_add',
                data: $('#add_form').serialize(),
                success: function (r) {
                    if (r) {
                    	if(r.error==0){
                    		showInfo(r.content);
                    		me.dgData.datagrid('reload');
                    		$('#add_window').window('close');
                    	}else {
                    		showError(r.message);
                    	}
                    }
                },
                complete:function(){
                	clearLoading();
                	$("#btn_add").linkbutton('enable');
                }
            });
            showLoading(e);
        	$("#btn_add").linkbutton('disable');
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
    	$("#add_message").val("");
    }

     //数据删除
    function deletemessage() {
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
        var name=rows[0]["message"];
        if(name.length>40){
        	name = name.substring(0,40)+"...";
        }
        
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+ name + '】', function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "message_id": ids },
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
    
    
    function searchmessages(){
    	$('#dgData').datagrid('reload');
    }
    
    function allMessage(){
    	$('#search_keywords').val('');
    	$('#dgData').datagrid('load', {});
    }
    
    //收件箱
    function getedMessage(){
    	$('#dgData').datagrid('load', {
    		search_to_type: 'admin',
    		search_to_: admin_id
    	});
    }
 
    function sendedMessage(){
    	$('#dgData').datagrid('load', {
    		search_from_type: 'admin',
    		search_from_: admin_id
    	});
    }