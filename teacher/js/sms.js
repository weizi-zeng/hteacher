  var me = {
            dgData: null,
            idFiled: 'student_id',
            actionUrl: 'sms.php'
        };
        //页面加载
        $(function () {
            pageInit();
            loadGrid();
        });
        
        //页面初始化
        function pageInit() {
            me.dgData = $('#dgData');
            $('#sms_send').click(function(e){
            	if ($(this).hasClass("l-btn-disabled")) {
                    return;
                }
            	send(e);
            });
        }

        //加载数据列表
        function loadGrid() {
            me.dgData.datagrid({
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
                sortName: me.idFiled,
                idField: me.idFiled,
                columns: [[
				  { field: 'student_id', title: 'ID', checkbox: true },
				  { field: 'code', title: '学号', width: 80, sortable: true, align: 'center' },
                  { field: 'name', title: '姓名', width: 80, sortable: true, align: 'center' },
                  { field: 'address', title: '住址', width: 300, sortable: true, align: 'center' },
                  { field: 'guardian_name', title: '家长', width: 80, sortable: true, align: 'center' },
                  { field: 'guardian_phone', title: '家长电话', width: 120, sortable: true, align: 'center' },
                  { field: 'created', title: '创建日期', width: 220, sortable: true, align: 'center' }
                  ]],
                  
                  toolbar: "#toolbar",
                  onBeforeLoad: function (param) {
                	  param["search_is_active"] = 1;
                  }
            });

    }
        
        
    //获取当前选中的phones
    function getPhones(){
    	var selects = $('#dgData').datagrid('getSelections');
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
    	var content = $('#content').val();
    	if(!content){
    		showError("请输入短信内容");
    		return false;
    	}
    	var phones = getPhones();	
    	if(phones===false){
    		return;
    	}
    	var copy = $('#copy').get(0).checked?1:0;
    	
    	var param = {"phones":phones, "content":content, "copy":copy};
    	$.post(me.actionUrl+"?act=send", param, function(r){
    		clearLoading();
    		if(r.error==0){
    			showInfo("短信发送成功！");
    		}else {
    			showError("短信发送失败！"+r.msg);
    		}
    		
    		$("#sms_send").linkbutton('enable');
    	});
    	showLoading(e,"正在发送短信，请稍等...");
    	$("#sms_send").linkbutton('disable');
    }
    
    //检查短信内容是否超过70个字符
    function check(){
    	var val = $('#content').val();
    	var rem = 70- val.length;
    	$('#tip').text(rem);
    }
    

    
    