


//初始化左侧
function InitLeftMenu() {

    $('.navlist li div').click(function () {
        var aTag = $(this).find("a");
        var tabTitle = aTag.text();
        var url = aTag.attr("rel");
        var menuid = aTag.attr("ref");
        addTab(tabTitle, url, "");
        $('.navlist li div').removeClass("selected");
        $(this).addClass("selected");
    }).hover(function () {
        $(this).addClass("menuListChildHover");
    }, function () {
        $(this).removeClass("menuListChildHover");
    });

    navStyle();
}


function navStyle() {
    //调整导航菜单的样式
    var navs = $('.panel-header'); //panel-title

    var nav = '.panel-header:contains("导航菜单")';

    $(nav).css('background-color', "#A6E1FF");
    $(nav).css('background-image', "linear-gradient(to bottom, #2DAAE5, #91DBFE)");
    $(nav).css('background-repeat', "repeat-x");
    $(nav).css('border-color', "rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25)");
    $(nav).css('text-shadow', "0 -1px 0 rgba(0, 0, 0, 0.25)");

    $(nav).css('height', "20px");
    $(nav).css('line-height', "20px");

    var daohang = $($(nav).get(0).childNodes[0]);
    daohang.css('font-size', "16px");
    daohang.css('font-weight', "bolder");
    daohang.css('padding', "2px");


    $('#nav .panel-header').bind('mouseover', function () {//panel-title
        $(this).css('margin-left', '5px');
    });

    $('#nav .panel-header').bind('mouseout', function () {
        $(this).css('margin-left', '0');
    });

}


//获取菜单数据
function GetMenu() {
    $.ajax({
        url: '../ashx/ashSYSGroupMenu.ashx',  // './data/menulist.json', 
        type: 'get',
        data: "Method=GetGroupMenu&GroupDR=1&MenuLevel=1&Fields='MenuDR,MenuName,URL,Icon'&",
        success: function (returnData) {
            MENUDATA = eval(returnData);
        }
    });
}

function GetMenuList(GroupDR, MenusID, LeveNo) {
    var menuList = '';
    $.ajax({
        url: '../sys/ashx/ashSYSGroupMenu.ashx',  // './data/menulist.json', /// <reference path="../ashx/ashSYSGroupMenu.ashx" />
        type: 'get',
        data: "Method=GetGroupMenu&GroupDR=" + GroupDR + "&LevelNo=" + LeveNo + "&ParentID=" + MenusID + "&Fields='MenuDR,MenuName,URL,Icon,LevelNo'&",
        async: false,
        success: function (returnData) {
            menuList = eval(returnData);
        }
    });
    return menuList;
}

//获取左侧导航的图标
function getIcon(menuid) {
    var icon = 'icon ';
    $.each(MENUDATA.menus, function (i, n) {
        $.each(n.menus, function (j, o) {
            if (o.menuid == menuid) {
                icon += o.icon;
            }
        })
    })

    return icon;
}

function find(menuid) {
    var obj = null;
    $.each(MENUDATA.menus, function (i, n) {
        $.each(n.menus, function (j, o) {
            if (o.MenuDR == menuid) {
                obj = o;
            }
        });
    });

    return obj;
}

function addTab(subtitle, url, icon) {
    if (!$('#tabs').tabs('exists', subtitle)) {
        $('#tabs').tabs('add', {
            title: subtitle,
            content: createFrame(url),
            closable: true,
            icon: icon
        });
    } else {
        $('#tabs').tabs('select', subtitle);
        $('#mm-tabupdate').click();
    }
    tabClose();
}

function createFrame(url) {
    var s = '<iframe scrolling="auto" frameborder="0"  src="' + url + '" style="width:100%;height:100%;"></iframe>';
    return s;
}

function tabClose() {
    /*双击关闭TAB选项卡*/
    $(".tabs-inner").dblclick(function () {
        var subtitle = $(this).children(".tabs-closable").text();
        $('#tabs').tabs('close', subtitle);
    })
    /*为选项卡绑定右键*/
    $(".tabs-inner").bind('contextmenu', function (e) {
        $('#mm').menu('show', {
            left: e.pageX,
            top: e.pageY
        });

        var subtitle = $(this).children(".tabs-closable").text();

        $('#mm').data("currtab", subtitle);
        $('#tabs').tabs('select', subtitle);
        return false;
    });
}


//绑定右键菜单事件
function tabCloseEven() {

    $('#mm').menu({
        onClick: function (item) {
            closeTab(item.id);
        }
    });

    return false;
}


function closeTab(action) {
    var alltabs = $('#tabs').tabs('tabs');
    var currentTab = $('#tabs').tabs('getSelected');
    var allTabtitle = [];
    $.each(alltabs, function (i, n) {
        allTabtitle.push($(n).panel('options').title);
    })


    switch (action) {
        case "refresh":
            var iframe = $(currentTab.panel('options').content);
            var src = iframe.attr('src');
            $('#tabs').tabs('update', {
                tab: currentTab,
                options: {
                    content: createFrame(src)
                }
            })
            break;
        case "close":
            var currtab_title = currentTab.panel('options').title;
            $('#tabs').tabs('close', currtab_title);
            break;
        case "closeall":
            $.each(allTabtitle, function (i, n) {
                if (n != onlyOpenTitle) {
                    $('#tabs').tabs('close', n);
                }
            });
            break;
        case "closeother":
            currtab_title = currentTab.panel('options').title;
            $.each(allTabtitle, function (i, n) {
                if (n != currtab_title && n != onlyOpenTitle) {
                    $('#tabs').tabs('close', n);
                }
            });
            break;
        case "closeright":
            var tabIndex = $('#tabs').tabs('getTabIndex', currentTab);

            if (tabIndex == alltabs.length - 1) {
                alert('亲，后边没有啦 ^@^!!');
                return false;
            }
            $.each(allTabtitle, function (i, n) {
                if (i > tabIndex) {
                    if (n != onlyOpenTitle) {
                        $('#tabs').tabs('close', n);
                    }
                }
            });

            break;
        case "closeleft":
            var tabIndex = $('#tabs').tabs('getTabIndex', currentTab);
            if (tabIndex == 1) {
                alert('亲，前边那个上头有人，咱惹不起哦。 ^@^!!');
                return false;
            }
            $.each(allTabtitle, function (i, n) {
                if (i < tabIndex) {
                    if (n != onlyOpenTitle) {
                        $('#tabs').tabs('close', n);
                    }
                }
            });

            break;
        case "exit":
            $('#closeMenu').menu('hide');
            break;
    }
}


