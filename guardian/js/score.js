  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'score_id',
            actionUrl: 'score.php'
        };
        //页面加载
        $(function () {
            pageInit();
            loadGrid();
        });
        
      //加载数据列表
        function loadGrid() {
            me.dgData.datagrid({
                url: me.actionUrl + '?act=ajax_list', //me.actionUrl + '?Method=List', 
                method: 'get',
                fitColumns: true, 
                remoteSort: false,  //列少设为true,列多设为false
                autoRowHeight: false,
                rownumbers:true,
                pagination: true,
                pageSize: 25,
                pageList: [15, 25, 50, 100, 200],
                singleSelect: true,
                nowrap: false,  //折行
                border: false,
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'score_id', title: 'ID', hidden: true },
				  { field: 'prj_id', title: '考试名称ID', hidden: true },
				  { field: 'prj_name', title: '考试名称', width: 150, sortable: true, align: 'center' },
                  { field: 'subject', title: '考试科目', width: 150, sortable: true, align: 'center' },
                  { field: 'student_code', title: '学号', width: 120, sortable: true, align: 'center',
                	  sorter:function(a,b){  
	              		  return parseFloat(a)>parseFloat(b)?1:-1;
	              	  }  
                  },
                  { field: 'student_name', title: '学生', width: 120, sortable: true, align: 'center' },
	          	  { field: 'score', title: '考试分数', width: 60, sortable: true, align: 'center',
	              	  sorter:function(a,b){  
	              		  return parseFloat(a)>parseFloat(b)?1:-1;
	              	  }
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
            
            $("#add").click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	addScore(e);
            });
            get_subjects();
        }

    //操作函数
    //新增
    function add() {
        clear();
        $("#add_window").window('open');
    }
    
    //修改
    function update() {
        var rows = me.dgData.datagrid('getSelections');
        if (rows.length == 0) {
        	showError('请选择一条记录进行操作!');
            return;
        }
        if (rows.length > 1) {
        	showError('只能对一条记录进行操作!');
            return;
        }
        
        var row = rows[0];
    	
    	$("#score_id").val(row.score_id);
    	$("#exam_prj").val(row.prj_id);
    	$("#exam_subject").val(row.subject);
    	$("#student_code").val(row.student_code);
    	$("#score").numberbox("setValue",row.score);
         me.edit_window.window('open');
    }

    //批量添加成绩
    function addScore(e){
    	if ($("#add_form").form('validate')) {
            $.ajax({
                url: me.actionUrl + '?act=ajax_add',
                data: $("#add_form").serialize(),
                success: function (r) {
                    if (r) {
                    	if(r.error==0){
                    		showInfo(r.content);
                    		me.dgData.datagrid('reload');
                    		$("#add_window").window('close');
                    	}else {
                    		showError(r.message);
                    	}
                    }
                },
                complete:function(){
                	clearLoading();
                	$("#add").linkbutton('enable');
                }
            });
            showLoading(e);
        	$("#add").linkbutton('disable');
        }
    }
    
    //根据考试名称获取对应的考试科目
    function get_subjects(){
    	var param = {};
    	param.prj_id = $("#add_prj_id").val();
    	
    	 $.ajax({
             url: me.actionUrl + '?act=ajax_get_subject',
             data: param,
             success: function (r) {
                 if (r) {
                	 var html = '';
                	 if(r.length<1){
                		 html = '<div style="color:red;text-align:center;">您选择的考试名称上面没有绑定考试科目，请确认您已经对您选择的考试名称做了考试安排</div>';
                		 
                	 }else {
                		 html += '<table width="100%" cellspacing="1" cellpadding="0" border="0" class="form_table" style="padding: 4px; ">';
                    	 html += '<tr style="font-weight:bolder;">';
                    	 html += '<td>考试科目:</td>';
                    	 for(var i=0;i<r.length;i++){
                    		 html += '<td>'+r[i].subject+'</td>';
                    	 }
                    	 html += '</tr>';
                    	 
                    	 html += '<tr>';
                    	 html += '<td>考试分数:</td>';
                    	 for(var i=0;i<r.length;i++){
                    		 html += '<td><input name="add_score_'+r[i].subject+'" class="easyui-numberbox" data-options="min:0,precision:1" maxlength="4" style="width: 30px" /></td>';
                    	 }
                    	 html += '</tr>';
                    	 html += '</table>';
                	 }
                	 $("#add_subject_score_panel").html(html);
                 }
                 
             },
             complete:function(){
             	$("#add").linkbutton('enable');
             }
         });
    	 $("#add").linkbutton('disable');
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
    	$("#add_form").find("input").each(function(i,e){
    		$(e).val('');
    	});
    }

     //数据删除
    function deletescore() {
        var rows = me.dgData.datagrid('getSelections'); 
        if (rows.length == 0) { 
            showError('请选择记录进行操作!'); 
            return;
        } 
        
        var ids = "";
        for(var i=0;i<rows.length;i++) 
        { 
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
                    data: { "score_id": ids },
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
    
    
    function searchscores(){
    	$('#dgData').datagrid('reload');
    }
    
    function importscores(){
    	$('#import_window').window('open');
    }
    
    function importscores_import(){
    	$('#import_form').submit();
    }
    
    //下载模板
    function template(){
    	window.location.href='./templates/scoreTemplate.csv';
    }
    
    //根据考试名称进行导出
    function exportbyprj(){
    	var prj_id = $("#search_prj_id").val();
    	if(!prj_id){
    		showError('请选择考试名称!'); 
            return;
    	}
    	var charset = $("#charset").val();
    	if (!charset) 
        { 
            showError('请选择导出所需的编码格式!'); 
            $("#charset").focus();
            return;
        } 
    	window.open("score.php?act=exportbyprj&search_prj_id="+prj_id+"&charset="+charset);
    }
    
    //按查询条件导出
    function exportbyquery(){
    	var student_code = $("#search_student_code").val();
    	var prj_id = $("#search_prj_id").val();
    	var subject = $("#search_exam_subject").val();
    	var charset = $("#charset").val();
    	if (!charset) 
        { 
            showError('请选择导出所需的编码格式!'); 
            $("#charset").focus();
            return;
        } 
    	window.open("score.php?act=exportbyquery&search_student_code="+student_code+"&search_prj_id="+prj_id+"&search_exam_subject="+subject+"&charset="+charset);
    }