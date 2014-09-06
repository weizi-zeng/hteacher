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
				  { field: 'exam_id', title: 'ID', hidden: true },
				  { field: 'code', title: '考试编号', width: 120, sortable: true, align: 'center' },
                  { field: 'prj_code', title: '考试名称', width: 120, sortable: true, align: 'center' },
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
        	
        	$("#exam_id").val(row.exam_id);
        	$("#code").val(row.code);
        	$("#prj_code").val(row.prj_code);
        	
        	$("#examdate").datebox("setValue",row.examdate);
           
        	$("#stime").val(row.stime);
        	$("#etime").val(row.etime);
        	
        	$("#subject").val(row.subject);
        	$("#teacher").val(row.teacher);
        	$("#classroom").val(row.classroom);

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
        var name=rows[0]["prj_code"];
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
    	var exam_name = $('#search_prj').val();
    	if(!exam_name){
    		showError("请选择考试名称！");
    		return;
    	}
    	window.location.href='exam.php?act=publish&prj_code='+exam_name;
    }
    
    //短信通知
    function sendexam(){
    	var exam_name = $('#search_prj').val();
    	if(!exam_name){
    		showError("请选择考试名称！");
    		return;
    	}
    	
    	$('#sms_send').click(function(e){
        	if ($(this).hasClass("l-btn-disabled")) {
                return;
            }
        	send(e);
        });
    	
    	loadStudentGrid();
    	setSmsContent(exam_name);
    	
    	$('#send_sms_window').window('open');
    }
    
    //设置短信内容
    function setSmsContent(exam_name){
    	$.post(me.actionUrl+"?act=getSmsContent", {"prj_code":exam_name}, function(r){
    		if(r.error==0){
    			$('#sms_content').val(r.msg);
    		}else {
    			showError("生成短信异常！"+r.msg);
    		}
    	});
    }
    

    //加载数据列表
    function loadStudentGrid() {
        $('#student_dgData').datagrid({
            url: 'student.php?act=ajax_list', //me.actionUrl + '?Method=List', 
            method: 'get',
            fitColumns: true, 
            remoteSort: false,  //列少设为true,列多设为false
            autoRowHeight: false,
            pagination: true,
            pageSize: 25,
            pageList: [15, 25, 50, 100, 200],
            singleSelect: false,
            nowrap: false,  //折行
            border: false,
            sortName: "code",
            idField: "code",
            columns: [[
			  { field: 'student_id', title: 'ID', checkbox: true },
			  { field: 'code', title: '学号', width: 80, sortable: true, align: 'center' },
              { field: 'name', title: '姓名', width: 80, sortable: true, align: 'center' },
              { field: 'address', title: '住址', width: 300, sortable: true, align: 'center' },
              { field: 'guardian_name', title: '家长', width: 80, sortable: true, align: 'center' },
              { field: 'guardian_phone', title: '家长电话', width: 120, sortable: true, align: 'center' },
              ]],
              toolbar: "#student_toolbar",
        });

}
    
    
//获取当前选中的phones
function getPhones(){
	var selects = $('#student_dgData').datagrid('getSelections');
	if(selects.length==0){
		showError("请选择需要发送的学生家长");
		return false;
	}
	var phones = '';
	for(var i=0;i<selects.length;i++){
		phones += selects[i].guardian_phone;//
		if(i<selects.length-1){
			phones += ",";
		}
	}
	
	return phones;
}

//短信通知
function send(e){
	var content = $('#sms_content').val();
	if(!content){
		showError("请输入短信内容");
		return false;
	}
	var phones = getPhones();	
	if(phones===false){
		return;
	}
	var copy = $('#sms_copy').get(0).checked?1:0;
	
	var param = {"phones":phones, "content":content, "copy":copy};
	$.post("sms.php?act=send", param, function(r){
		clearLoading();
		if(r.error==0){
			showInfo("短信发送成功！");
			$('#send_sms_window').window('close');
		}else {
			showError("短信发送失败！"+r.msg);
		}
		
		$("#sms_send").linkbutton('enable');
	});
	showLoading(e,"正在发送短信，请稍等...");
	$("#sms_send").linkbutton('disable');
}

//检查短信内容是否超过512个字符
function check(){
	var val = $('#sms_content').val();
	var rem = 512- val.length;
	$('#tip').text(rem);
}


    