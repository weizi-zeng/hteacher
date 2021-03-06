  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'student_id',
            actionUrl: 'student.php'
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
            $("#birthday").datebox();
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
                remoteSort: true,  //列少设为true,列多设为false
                autoRowHeight: false,
                pagination: true,
                pageSize: 25,
                pageList: [15, 25, 50, 100, 200],
                singleSelect: true,
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'student_id', title: 'ID', hidden: true },
				  { field: 'code', title: '学号', width: 80, sortable: true, align: 'center' },
                  { field: 'name', title: '姓名', width: 80, sortable: true, align: 'center' },
                  { field: 'is_active', title: '是否已注册', width: 100, sortable: true, align: 'center', 
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"是":"否";
                	  },
                	  styler: function(value,row,index){
          				if (value==0){
          					return 'color:red;';
          				}
          			 }
                  },
                  { field: 'dorm', title: '宿舍', width: 80, sortable: true, align: 'center' },
                  { field: 'sexuality', title: '性别', width: 60, sortable: true, align: 'center', 
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"男":"女";
                	  }
                  },
                  { field: 'birthday', title: '出生年月', width: 120, sortable: true, align: 'center' },
                  { field: 'national', title: '民族', width: 60, sortable: true, align: 'center' },
                  { field: 'id_card', title: '身份证', width: 150, sortable: true, align: 'center' },
                  { field: 'phone', title: '电话', width: 120, sortable: true, align: 'center' },
                  { field: 'qq', title: 'QQ', width: 120, sortable: true, align: 'center' },
                  { field: 'email', title: '邮箱', width: 200, sortable: true, align: 'center' },
                  { field: 'address', title: '住址', width: 300, sortable: true, align: 'center' },
                  { field: 'has_left', title: '是否已离校', width: 120, sortable: true, align: 'center',
                	  formatter: function (value, rowData, rowIndex) {
                		  return value==1?"是":"否";
                	  },
                  },
                  { field: 'guardian_name', title: '家长', width: 80, sortable: true, align: 'center' },
                  { field: 'guardian_phone', title: '家长电话', width: 120, sortable: true, align: 'center' },
                  { field: 'guardian_relation', title: '与家长关系', width: 120, sortable: true, align: 'center' },
                  
                  { field: 'created', title: '创建日期', width: 220, sortable: true, align: 'center' }
                  ]],
                  
	              toolbar: "#toolbar",
	              onDblClickRow: function () {
	            	  update();
	              },
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
        clear();
        $('#btn_edit_ok').show();
        me.edit_window.window('open');
        $('#name')[0].focus();
    }
    
    //修改
    function update() {
        var rows = me.dgData.datagrid('getSelections');
        if (rows.length > 0) {
        	var row = rows[0];
        	
        	$("#student_id").val(row.student_id);
        	$("#code").val(row.code);
        	$("#name").val(row.name);
        	$("#dorm").val(row.dorm);
        	if(row.sexuality=="1"){
        		$('[name="sexuality"]:radio').each(function() {   
                    if (this.value == '1'){   
                       this.checked = true;   
                    }else {
                       this.checked = false;
                    }
                 });
        	}else {
        		$('[name="sexuality"]:radio').each(function() {   
                    if (this.value == '0'){   
                       this.checked = true;   
                    }else {
                       this.checked = false;
                    }
                 });
        	}
        	
        	$('#birthday').datebox("setValue",row.birthday); 
        	$("#national").val(row.national);
        	$("#id_card").val(row.id_card);
           
        	$("#phone").val(row.phone);
        	$("#qq").val(row.qq);
        	$("#email").val(row.email);
        	$("#address").val(row.address);
        	
        	$("#guardian_name").val(row.guardian_name);
        	$("#guardian_relation").val(row.guardian_relation);
        	$("#guardian_phone").val(row.guardian_phone);
        	
        	if(row.has_left=="1"){
        		$('[name="has_left"]:radio').each(function() {   
                    if (this.value == '1'){   
                       this.checked = true;   
                    }else {
                       this.checked = false;
                    }
                 });
        		
        	}else {
        		$('[name="has_left"]:radio').each(function() {   
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
    	
    	$("#student_id").val("");
    	$("#code").val("");
    	$("#name").val("");
    	
		$('#birthday').datebox("setValue",""); 
    	$("#national").val("");
    	$("#id_card").val("");
    	
    	$("#phone").val("");
    	$("#email").val("");
    	$("#address").val("");
    	
    	$("#guardian_name").val("");
//    	$("#guardian_relation").val("");
    	$("#guardian_phone").val("");
        
        $('form').form('validate');
    }

     //数据删除
    function deleteStudent() {
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
        
        var tip = '确认要删除选择项？【'+rows[0]["code"]+ ','+ name + '】';
        var is_active=rows[0]["is_active"];
        if(is_active==1){
        	tip += '，<font style="color:red;">“'+name+'”已经注册，删除后注册信息将自动失效，请慎重！</font>';
        }
        $.messager.confirm('提示信息', tip, function (isClickedOk) {
            if (isClickedOk) {
                $.ajax({
                    url: me.actionUrl+"?act=ajax_delete",
                    data: { "student_id": ids },
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
    
    
    function searchStudents(){
    	$('#dgData').datagrid('reload');
    }
    
    function importStudents(){
    	$('#import_window').window('open');
    }
    
    function importStudents_import(){
    	$('#import_form').submit();
    }
    
    function exportStudents(){
    	window.open("student.php?act=export&order=asc&rows=1000");
    }
    
    //下载模板
    function template(){
    	window.location.href='./templates/studentTemplate.csv';
    }
    
    //为家长直接重置登陆密码
    function changePwd(){
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
        
        var is_active=rows[0]["is_active"];
        if(is_active==0){
        	showError("您选择的用户还未注册，不能够为其重置密码！");
        	return;
        }
        
        $.messager.prompt('重置密码，注册学生：'+name, '请输入新密码:', function(pwd){
        	if (pwd){
        		$.ajax({
                    url: me.actionUrl+"?act=ajax_changePwd",
                    data: { "student_id": ids, "new_password":pwd },
                    success: function (r) {
                    	 if (r) {
                         	if(r.error==0){
                         		showInfo(r.content);
                         	}else {
                         		showError(r.message);
                         	}
                         }
                    }
                });
        		
        	}else {
//        		showError('您输入的新密码无效，密码不能为空!'); 
        	}
        });
    }
    
    