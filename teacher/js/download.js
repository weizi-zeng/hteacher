  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'download_id',
            actionUrl: 'download.php'
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
            
        }

        //加载数据列表
        function loadGrid() {
            me.dgData.datagrid({
                url: me.actionUrl + '?act=ajax_list', //me.actionUrl + '?Method=List', 
                method: 'post',
                fitColumns: true, 
                singleSelect:false,
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
                onDblClickRow: function () {
	            	  update();
	              },
                columns: [[
				  { field: 'download_id', title: 'ID', checkbox:true },
				  { field: 'download_type', title: '类型', width: 80, sortable: true, align: 'center'},
				  { field: 'sort', title: '排序', width: 40, sortable: true, align: 'center',
					  sorter:function(a,b){  
						  return parseInt(a)>parseInt(b)?1:-1;
					  }
				  },
                  { field: 'name', title: '名称', width: 120, sortable: true, align: 'center'},
                  { field: 'path', title: '下载', width: 220, sortable: true, align: 'center',
                	  formatter: function(value,row,index){
							var tmp = '<a href="download.php?act=download&path='+value+'" target="_blank">'+value+'</a>';
							return tmp;
					  }  
                  },
                  { field: 'created', title: '创建时间', width: 80, sortable: true, align: 'center' }
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
//    	clear();
        $("#add_window").window('open');
    }
    
    function clear(){
//    	$('#file_upload').uploadifyClearQueue();
//    	$('#file_upload').uplaodify('cancel','*');
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
        	
        	$("#download_id").val(row.download_id);
        	$("#path").val(row.path);
        	$("#name").val(row.name);
        	$('#sort').numberbox('setValue', row.sort);

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
    function deletedownload() {
        var ids = "";
        var rows = me.dgData.datagrid('getSelections'); 
        if (rows.length == 0) { 
            showError('请选择一条记录进行操作!'); 
            return;
        } 
        
        var ids = '';
        for(var i=0;i<rows.length;i++){
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
                    data: { "download_id": ids },
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
    
    function searchdownloads(){
    	$('#dgData').datagrid('reload');
    }
    
    function cancel(){
    	$('#file_upload').uploadify('cancel');
    	$('#add_window').window('close');
    }
