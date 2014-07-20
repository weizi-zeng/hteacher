  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'exam_id',
            actionUrl: 'exam.php'
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
				  { field: 'exam_id', title: 'ID', checkbox: true },
				  { field: 'code', title: '考试编号', width: 120, sortable: true, align: 'center' },
                  { field: 'name', title: '考试项目', width: 120, sortable: true, align: 'center' },
                  { field: 'subject', title: '考试科目', width: 120, sortable: true, align: 'center' },
                  { field: 'teacher', title: '监考老师', width: 120, sortable: true, align: 'center' },
                  { field: 'examdate', title: '考试日期', width: 120, sortable: true, align: 'center' },
                  { field: 'setime', title: '考试起止时间', width: 150, sortable: true, align: 'center' },
                  { field: 'classroom', title: '所在教室', width: 150, sortable: true, align: 'center' },
                  
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
        	
        	$("#exam_id").val(row.exam_id);
        	$("#code").val(row.code);
        	$("#name").val(row.name);
        	
        	$("#examdate").datebox("setValue",row.examdate);
           
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

    //保存
    function save() {
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
                }
            });
        }
    }
    
    //清空界面数据
    function clear() {
    	
    	$("#exam_id").val("");
    	$("#stime").val("");
    	$("#etime").val("");
    	//$("#subject").val("");
    	
    	$("#teacher").val("");
    	//$("#classroom").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deleteexam() {
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
                    data: { "exam_id": ids },
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
    
    
    function searchexams(){
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
    		ids += selects[i].exam_id;
    		if(i<selects.length-1){
    			ids += ",";
    		}
    	}
    	
    	return ids;
    }
    
    function importexams(){
    	$('#import_window').window('open');
    }
    
    function importExams_import(){
    	$('#import_form').submit();
    }
    
    //下载模板
    function template(){
    	window.location.href='./templates/examTemplate.csv';
    }
    
    
    //发布公告
    function publicexam(){
    	var ids = getIds();
    	if(ids===false){
    		return;
    	}
    	
    	console.debug(ids);
    }
    
    //短信通知
    function sendexam(){
    	var ids = getIds();
    	if(ids===false){
    		return;
    	}
    	
    	console.debug(ids);
    }
    
    
    
    
    