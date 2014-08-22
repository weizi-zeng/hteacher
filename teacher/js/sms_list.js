  var me = {
            dgData: null,
            search_form: null,
            idFiled: 'sms_id',
            actionUrl: 'sms.php'
        };
        //页面加载
        $(function () {
            pageInit();
            loadGrid();
        });
        
        //页面初始化
        function pageInit() {
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
                pageSize: 20,
                pageList: [20,40,60,80,100],
                nowrap: false,  //折行
                border: false,
                singleSelect:true,
                //sortName: me.idFiled,
                //idField: me.idFiled,
                columns: [[
				  { field: 'sms_id', title: 'ID', hidden: true },
				  { field: 'content', title: '短信内容', width: 420, sortable: true, align: 'left' },
                  { field: 'phones', title: '发送电话', width: 220, sortable: true, align: 'center' },
                  { field: 'status', title: '短信状态', width: 80, sortable: true, align: 'center', 
                	  formatter: function (value, rowData, rowIndex) {
                          switch (Number(value)) {
                              case 0:
                                  return '还未发送';
                              case 1:
                                  return '发送成功';
                              case 2:
                                  return '发送失败';
                              default:
                                  return value;
                          }
                      }
                  },
                  { field: 'num', title: '发送数量', width: 80, sortable: true, align: 'center' },
                  { field: 'sended', title: '发送时间', width: 120, sortable: true, align: 'center' },
                  { field: 'creator', title: '发送人', width: 80, sortable: true, align: 'center' },
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
    
    function searchsmss(){
    	$('#dgData').datagrid('reload');
    }
    
    
    
    