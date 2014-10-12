  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'forum_id',
            actionUrl: 'forum.php'
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
                checkOnSelect:true,
                pagination: true,
                pageSize: 25,
                pageList: [15, 25, 50, 100, 200],
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'forum_id', title: 'ID', checkbox: true },
				  { field: 'title', title: '主题', width: 120, sortable: true, align: 'center',
					  formatter: function (value, rowData, rowIndex) {
                		  return "<a href='forum.php?act=view&forum_id="+rowData.forum_id+"' target='_blank' >"+value+"</a>";
                	  }
				  },
                  { field: 'creator', title: '创建人', width: 120, sortable: true, align: 'center' },
                  { field: 'user_id', title: '创建人', hidden: true },
                  { field: 'is_active', title: '是否显示', width: 120, sortable: true, align: 'center',
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"是":"否";
                	  },
                  },
                  { field: 'created', title: '创建时间', width: 220, sortable: true, align: 'center' }
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
    	
    	$("#forum_id").val("");
    	$("#title").val("");
    	$("#content").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deleteforum() {
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
        
        console.dir(rows[0]);
        var title=rows[0]["title"];
        //班主任可以直接删除所有的帖子
//        var user_id=rows[0]["user_id"];
//        if(user_id!=$("#user_id").val()){
//        	showError("只能删除自己创建的主题！");
//        	return;
//        }
        $.messager.confirm('提示信息', '确认要删除选择项？【'+ids+ ','+ title + '】', function (isClickedOk) {
           if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "forum_id": ids },
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
    
    
    function searchforums(){
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
    		ids += selects[i].forum_id;
    		if(i<selects.length-1){
    			ids += ",";
    		}
    	}
    	
    	return ids;
    }
    
    //发布公告
    function publicforum(){
    	var ids = getIds();
    	if(ids===false){
    		return;
    	}
    	
    	console.debug(ids);
    }
    
    
    
    
    
    