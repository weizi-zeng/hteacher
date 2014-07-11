/***********************************************************************

* 主页界面
* 创建日期：2014/01/27
* modify by weizi  2014/05/22

**********************************************************/


var onlyOpenTitle = "主页"; //不允许关闭的标签的标题
var MENUDATA = '';
var me = {
    win_changepwd: null,
    win_changepwd_form: null
};

$(function () {
	InitLeftMenu();
	
    pageInit();

    tabClose();
    tabCloseEven();
   
});

function pageInit() {
    me.win_changepwd = $('#win_changepwd');
    me.win_changepwd_form = me.win_changepwd.find('#win_changepwd_form');

    $('#editpass').click(function () {
        me.win_changepwd.window('open');
    });

    $('#btnEp').click(function () {
        changePwd();
    });

    $('#btnCancel').click(function () {
        me.win_changepwd.window('close');
    });

}

function logOut(){
	$.messager.confirm('系统提示', '您确定要退出本次登录吗?', function (isOk) {
		 if (isOk) {
			 location.href = '../login.php?act=logout';
		 }
	});
}

//修改密码<a href="ashx/ashLogin.ashx">ashx/ashLogin.ashx</a>
function changePwd() {
    if (me.win_changepwd_form.form('validate')) {
        $.ajax({
            url: '../login/ashx/ashLogin.ashx?Method=ChangePassword',
            data: me.win_changepwd_form.serialize(),
            success: function (returnData) {
                if (returnData) {
                    if (returnData.isOk == 1) {
                        showInfo('密码修改成功！'); //<br>您的新密码为：' + me.win_changepwd_form.find("#NewPassword").val());
                        me.win_changepwd_form.form('clear');
                        me.win_changepwd.window('close');
                    } else {
                        showError(returnData.message);
                    }
                }
            }
        });
    }
}





