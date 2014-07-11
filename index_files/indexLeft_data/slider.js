function getStyle(elem,name){  
    var elem = (!elem) ? alert("ERROR: It is short of getStyle==>elem!") : elem;  
    var name = (!name) ? alert("ERROR: It is short of getStyle==>name!") : name.toString();  
    if((!elem) && (!name)){return false;}  
    if(elem.style[name]){   
        return elem.style[name];  
    }else if(elem.currentStyle){   
        return elem.currentStyle[name];  
    }else if(document.defaultView && document.defaultView.getComputedStyle){   
        name = name.replace(/([A-Z])/g,"-$1");   
        name = name.toLowerCase();  
        var s = document.defaultView.getComputedStyle(elem,"");   
        return s && s.getPropertyValue(name);  
    }else{   
        return null;  
    };  
}  
/* Tween */  
var Tween = {  
    Expo: {  
        easeOut: function(t, b, c, d) {  
            return (t == d) ? b + c: c * ( - Math.pow(2, -10 * t / d) + 1) + b;  
        }  
    }  
}  
/* zFocus */  
var zFocus = function() {  
    function init(elem) {  
        this.elem = document.getElementById(elem.id);  
        this.orien = (!elem.orien) ? 0 : (elem.orien.toString() == "left") ? 0 : (elem.orien.toString() == "top") ? 1 : 0;  
        this.time = (!elem.time || (typeof elem.time != "number")) ? 5 : elem.time;  
        this.click_key = true;  
        this.in_init();  
    };  
    init.prototype = {  
        in_init: function() {  
            var ev_height = this.ev_height = parseInt(getStyle(this.elem, "height")),  
            ev_width = this.ev_width = parseInt(getStyle(this.elem, "width")),  
            banner_ul = this.banner_ul = this.elem.getElementsByTagName("ul")[0],  
            total_num = this.n = banner_ul.getElementsByTagName("li").length,  
            btn_ul = this.btn_ul = this.elem.getElementsByTagName("ul")[1],  
            btn_li = this.btn_li = btn_ul.getElementsByTagName("li"),  
            _this = this;  
            if (this.orien == 0) {  
                banner_ul.style.height = ev_height + "px";  
                banner_ul.style.width = (ev_width * total_num) + "px";  
            } else if (this.orien == 1) {  
                banner_ul.style.height = (ev_height * total_num) + "px";  
                banner_ul.style.width = ev_width + "px";  
            }  
            banner_ul.style.left = 0 + "px";  
            banner_ul.style.top = 0 + "px";  
            this.index = 0;  
            this.creat_btn();  
            this.elem.onmouseover = function() {  
                clearInterval(_this.a)  
            };  
            this.elem.onmouseout = function() {  
                _this.start();  
            };  
        },  
        start: function() {  
            var _this = this;  
            this.a = setInterval(function() {  
                _this.auto()  
            },  
            this.time * 1000);  
        },  
        creat_btn: function() {  
            var _this = this;  
            for (var i = 0; i < this.n-1; i++) {  
                var newLi = document.createElement("li");  
                newLi.innerHTML = "●";  
                newLi.setAttribute("num", i);  
                this.btn_ul.appendChild(newLi);  
                this.btn_li[i].onclick = function() {  
                    if (_this.click_key) {  
                        var x = parseInt(this.getAttribute("num"));  
                        clearInterval(_this.a);  
                        clearInterval(_this.m);  
                        _this.move(x);  
                    }  
                };  
            };  
            this.btn_li[0].className = "current";  
            this.start();  
        },  
        auto: function() {  
            this.index = (this.index == (this.n - 2)) ? 0 : (this.index + 1);  
            this.move(this.index);  
        },  
        move: function(i) {  
            var _this = this;  
            var click_key = this.click_key;  
            for (var x = 0; x < this.n-1; x++) {  
                this.btn_li[x].className = "";  
            };  
            this.btn_li[i].className = "current";  
            if (this.orien == 0) {  
                var t = 0,  
                b = parseInt(getStyle(this.banner_ul, "left")),  
                c = ( - i * this.ev_width) - b,  
                d = 80;  
                var m = this.m = setInterval(function() {  
                    _this.banner_ul.style.left = Math.ceil(Tween.Expo.easeOut(t, b, c, d)) + "px";  
                    if (t < d) {  
                        t++;  
                    } else {  
                        clearInterval(m);  
                    }  
                },  
                10);  
            } else if (this.orien == 1) {  
                var t = 0,  
                b = parseInt(getStyle(this.banner_ul, "top")),  
                c = ( - i * this.ev_height) - b,  
                d = 80;  
                var m = this.m = setInterval(function() {  
                    _this.banner_ul.style.top = Math.ceil(Tween.Expo.easeOut(t, b, c, d)) + "px";  
                    if (t < d) {  
                        t++;  
                    } else {  
                        clearInterval(m);  
                    }  
                },  
                10);  
            };  
            this.click_key = click_key;  
            this.index = i;  
        }  
    };  
    return init;  
} (); 