  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            search_window: null,
            idFiled: 'person_id',
            actionUrl: 'person.php'
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
            me.search_window = $('#search_window');
            me.search_form = me.search_window.find('#search_form');
            me.dgData = $('#dgData');
            $('#btn_search_ok').click(function () { me.dgData.datagrid({ pageNumber: 1 }); });
            $("#bthday").datebox();
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
                fitColumns: false, 
                remoteSort: false,  //列少设为true,列多设为false
                pagination: true,
                pageSize: 15,
                pageList: [15, 30, 45, 60],
                singleSelect: true,
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'person_id', title: 'ID', hidden: true },
                  { field: 'name', title: '姓名', width: 80, sortable: true, align: 'center' },
                  { field: 'sex', title: '性别', width: 60, sortable: true, align: 'center', 
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"男":"女";
                	  }
                  },
                  { field: 'iden', title: '身份', width: 60, sortable: true, align: 'center' },
                  { field: 'nation', title: '名族', width: 60, sortable: true, align: 'center' },
                  { field: 'id_card', title: '身份证', width: 150, sortable: true, align: 'center' },
                  { field: 'bthday', title: '出生年月', width: 120, sortable: true, align: 'center' },
                  { field: 'tel', title: '电话', width: 120, sortable: true, align: 'center' },
                  { field: 'shorttel', title: '短号', width: 80, sortable: true, align: 'center' },
                  { field: 'email', title: '邮箱', width: 200, sortable: true, align: 'center' },
                  { field: 'address', title: '住址', width: 300, sortable: true, align: 'center' },
                  { field: 'unit', title: '单位', width: 150, sortable: true, align: 'center' },
                  { field: 'has_left', title: '是否已离校', width: 120, sortable: true, align: 'center',
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"是":"否";
                	  },  
                  },
                  { field: 'created', title: '创建日期', width: 220, sortable: true, align: 'center' }
                  ]],
                  
                toolbar: ['-'
                /* <%if(CheckFun("Insert")){ %>*/
                , { text: '新增', iconCls: 'icon-add', handler: function () { Add(); } }, '-'
                /*<%} %>*/
                /*<%if(CheckFun("Update")){ %>*/
                , { text: '修改', iconCls: 'icon-edit', handler: function () { Update(); } }, '-'
                /*<%} %>*/
                /*<%if(CheckFun("Delete")){ %>{*/
                , { text: '删除', iconCls: 'icon-remove', handler: function () { Delete(); } }, '-'
                /*<%} %>*/
                ],
                onBeforeLoad: function (param) {
                    me.search_form.find('input').each(function (index) {
                        param[this.name] = $(this).val();
                    });
                }
            });

    }
    //操作函数
    //新增
    function Add() {
        Clear();
        $('#btn_edit_ok').show();
        me.edit_window.window('open');
        $('#name')[0].focus();
    }
    
    //修改
    function Update() {
        var rows = me.dgData.datagrid('getSelections');
        if (rows.length > 0) {
        	var row = rows[0];
        	
        	$("#person_id").val(row.person_id);
        	$("#name").val(row.name);
        	
        	if(row.sex=="1"){
        		$("input[name=sex][value=0]").removeProp("checked");
        		$("input[name=sex][value=1]").prop("checked","checked");
        	}else {
        		$("input[name=sex][value=1]").removeProp("checked");
        		$("input[name=sex][value=0]").prop("checked","checked");
        	}
        	
        	$("#iden").val(row.iden);
        	$("#nation").val(row.nation);
        	
            $('#bthday').datebox("setValue",row.bthday); 
            
        	$("#tel").val(row.tel);
        	$("#shorttel").val(row.shorttel);
        	$("#id_card").val(row.id_card);
        	$("#email").val(row.email);
        	$("#address").val(row.address);
        	$("#unit").val(row.unit);
        	
        	if(row.has_left=="1"){
        		$("input[name=has_left][value=0]").removeProp("checked");
        		$("input[name=has_left][value=1]").prop("checked","checked");
        		
        	}else {
        		$("input[name=has_left][value=1]").removeProp("checked");
        		$("input[name=has_left][value=0]").prop("checked","checked");
        		
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
    function Clear() {
    	
    	$("#person_id").val("");
    	$("#name").val("");
    	
    	$("input[name=sex][value=1]").removeProp("checked");
		$("input[name=sex][value=0]").prop("checked","checked");
    	
    	$("#iden").val("");
    	$("#nation").val("");
    	
        $('#bthday').datebox("setValue",""); 
        
    	$("#tel").val("");
    	$("#shorttel").val("");
    	$("#id_card").val("");
    	$("#email").val("");
    	$("#address").val("");
    	$("#unit").val("");
    	
    	$("input[name=has_left][value=0]").removeProp("checked");
		$("input[name=has_left][value=1]").prop("checked","checked");
        
        $('form').form('validate');
    }

     //数据删除
    function Delete() {
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
                    data: { "person_id": ids },
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
    
    
    function search(){
    	
    	
    	
    }