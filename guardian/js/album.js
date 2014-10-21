  var me = {
            dgData: null,
            edit_form: null,
            edit_window: null,
            search_form: null,
            idFiled: 'album_id',
            actionUrl: 'album.php'
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
            
            $('#add').click(function(e){
            	if ($(this).linkbutton("options").disabled) {
                    return;
                }
            	addalbum(e);
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
                columns: [[
				  { field: 'album_id', title: 'ID', checkbox:true },
				  { field: 'album_type', title: '类型', width: 80, sortable: true, align: 'center'},
				  { field: 'sort', title: '排序', width: 40, sortable: true, align: 'center',
					  sorter:function(a,b){  
						  return parseInt(a)>parseInt(b)?1:-1;
					  }
				  },
                  { field: 'name', title: '名称', width: 120, sortable: true, align: 'center'},
                  { field: 'path', title: '图片', width: 220, sortable: true, align: 'center',
                	  formatter: function(value,row,index){
							var tmp = '<a href="'+value+'" target="_blank"><img src="'+value+'" width="120" height="120" ></a>';
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
    
    function searchalbums(){
    	$('#dgData').datagrid('reload');
    }
    
