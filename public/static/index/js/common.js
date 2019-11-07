(function() {
    if(/Android ([\d\.]+)/.test(navigator.userAgent)) {
        var version = parseFloat(RegExp.$1);
        if(version>2.3)  {
            var width = window.outerWidth == 0 ? window.screen.width : window.outerWidth;
            var phoneScale = parseInt(width)/750;
            document.write('<meta name="viewport" content="width=750, minimum-scale = '+ phoneScale +', maximum-scale = '+ phoneScale +', target-densitydpi=device-dpi">');
        } else {
            document.write('<meta name="viewport" content="width=750, target-densitydpi=device-dpi">');
        }
    } else if(navigator.userAgent.indexOf('iPhone') != -1) {
        var phoneScale = parseInt(window.screen.width)/750;
        document.write('<meta name="viewport" content="width=750,min-height=1125,initial-scale=' + phoneScale +',user-scalable=no" /> ');         //0.75   0.82
    } else {
        document.write('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">');

    }
})();
function __input_data(data) {
    var inputs = document.getElementsByTagName("input");
    for(var i=0;i<inputs.length;i++) {
        var id = inputs[i].id;
        var name = inputs[i].name;
        var type = inputs[i].type;
        if(type == "checkbox" && name) {
            if(inputs[i].checked) {
                if(!data[name]) {
                    data[name] = [];
                }
                data[name].push(inputs[i].value);
            }
        } else if (type == "radio" && name) {
            if(inputs[i].checked) {
                data[name] = inputs[i].value;
            }
        } else if(id && !data[id] && type!="button" && type!="reset" && type!="submit") {
            data[id] = inputs[i].value
        }
    }
    var selects = document.getElementsByTagName("select");
    for(var i=0;i<selects.length;i++) {
        var id = selects[i].id;
        if(id && !data[id]) {
            data[id] = selects[i].value
        }
    }
    var textareas = document.getElementsByTagName("textarea");
    for(var i=0;i<textareas.length;i++) {
        var id = textareas[i].id;
        if(id && !data[id]) {
            data[id] = textareas[i].value
        }
    }
    return data;
}

function __momo_log_delay(log) {
    setTimeout(function(){
        throw new Error('__momolog\n'+log);
    }, 200);
}

function __base64decode(code) {
    return window.decodeURIComponent(window.escape(window.atob(code)));
}

function __format_log(txt) {
    var ret = /\<script\ type\=\'text\/html\'\ id\=\'__momo_log\'\>([a-zA-Z0-9\-\+\=\/]*)\<\/script\>\<script\>console\.log\(decodeURIComponent\(escape\(atob\(__momo_log\.innerText\)\)\)\)\<\/script\>/gm.exec(txt);
    if(ret && ret.length > 0) {
        txt = txt.replace(ret[0], '');
        __momo_log_delay(__base64decode(ret[1]));
    }
    return txt;
}

function __parse_json(res) {
    var data = undefined;
    try{
        data = JSON.parse(res);
    } catch(e) {
        try {
            data = eval('data='+res); //解决单引号无法parse的问题
        } catch(e) {
        }
    }
    return data;
}

function __deal_ajax_err(res) {
    if(res.responseText) {
        try {
            res = __format_log(res.responseText);
            var rej = __parse_json(res);
            if(rej['code'] == 302) {
                if(rej['url'] && rej['url'].indexOf('https://open.weixin.qq.com/connect') == 0) {
                    if(confirm('登录过期，是否刷新登录？')) {
                        window.location.reload();
                    }
                } else if(rej['url']) {
                    location.replace(rej['url']);
                    // window.location.href = rej['url'];
                }
                return;
            }
        } catch(e) {}
    }
    throw new Error('请求错误\n'+res);
}

function __do_click(url, data, target, idx) {
    $.ajax({
        url: url,
        type: "POST",
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        success: function(res, statusText, xhr) {
            console.log(res);
            target;
            res = __format_log(res);
            eval(res);
        },
        error: function(res) {
            if(res.readyState === 0 && res.status === 0 && res.statusText === "error" && idx < 5) {
                console.log(res.statusText);
                // __do_click(url, data, target, idx + 1);
                setTimeout(function() {
                    __do_click(url, data, target, idx + 1);
                }, 200);
            } else {
                __deal_ajax_err(res);
            }
        }
    });
}

function clickCall(url, data) {
    data = __input_data(data);
    var target = null;
    try{
        if(event.currentTarget) {
            target = event.currentTarget;
        } else if(event.target) {
            target = event.target;
        }
    } catch(e) {}
    __do_click(url, data, target, 0);
}
function clickGo(url, data) {
    if(data === undefined) {
        data = {};
    }
    data = __input_data(data);
    for(var k in data) {
        if(url.indexOf("?") == -1) {
            url = url + "?" + encodeURIComponent(k) + "="+encodeURIComponent(data[k]);
        } else {
            url = url + "&" + encodeURIComponent(k) + "="+encodeURIComponent(data[k]);
        }
    }
    location.replace(url);
    //window.location.href = url;
}
__READY_CALL_FUNCS = [];
function __READY_CALL(func) {
    if(!__BEREADY) {
        __READY_CALL_FUNCS.push(func);
    } else {
        func();
    }
}
__BEREADY = false;
function __DO_READY() {
    for(var i=0; i < __READY_CALL_FUNCS.length; i++) {
        __READY_CALL_FUNCS[i]();
    }
    __BEREADY = true;
}
var ua = navigator.userAgent.toLowerCase();


function __fetch_ret(res) {
    res = __format_log(res);
    var data = __parse_json(res);
    if(!data) {
        data = {
            "data": res,
            "js": ""
        }
    }
    if(data["js"]) {
        setTimeout(function(){eval(data["js"])}, 100);  //延迟执行，避免fetch之后还没添加dom就执行函数了
    }
    rej = data["data"];
    try{
        var match = /<head>([\s\S]*?)<\/head>/g.exec(rej)
        if(match) {
            setTimeout(function(){$('head').append(match[1]);}, 50);
            rej = rej.replace(match[0], '');
        }
    }catch(e) {}
    var t = __parse_json(rej);
    if(t) {
        rej = t;
    }
    return rej;
}
function fetch(url, data, async) {
    var target = null;
    if(async === undefined) {
        async = false;
    }
    try{
        if(event.currentTarget) {
            target = event.currentTarget;
        } else if(event.target) {
            target = event.target;
        }
    } catch(e) {}

    ret = $.ajax({
        url: url,
        type: "POST",
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        async: async,
        headers: {
            "Js-Fetch": "FETCH"
        },
        success: function(a, b, ret) {
            if(async) {
                return __fetch_ret(ret.responseText)
            }
        },
        error: function(res) {
            __deal_ajax_err(res);
        }
    });
    if(!async) {
        return __fetch_ret(ret.responseText)
    }
}
var None = null;
String.prototype.percent = function(){
    //将arguments转化为数组（ES5中并非严格的数组）
    var args = Array.prototype.slice.call(arguments);
    var count=0;
    //通过正则替换%s
    return this.replace(/%s/g,function(s,i){
        var arg = args[count++];
        if(typeof(arg) == "object") {
            return JSON.stringify(arg);
        }
        return arg;
    });
}
;(function() {
    var _css = $.fn.css;

    $.fn.extend({
        alias: function(name) {
            return $(this).find("*[alias="+name+"]");
        },
        css: function() {
            if(arguments.length == 2 && arguments[0] == 'background-image' && !arguments[1].startsWith('url(')) {
                return _css.apply(this, [arguments[0], 'url('+arguments[1]+')']);
            }
            if(arguments.length >= 1){
                if(arguments[0] == 'size') {
                    if(arguments.length == 1) {
                        return [_css.apply(this, ['width']), _css.apply(this, ['height'])];
                    } else if(arguments[1] instanceof Array && arguments[1].length == 2) {
                        _css.apply(this, ['width', arguments[1][0]]);
                        return _css.apply(this, ['height', arguments[1][1]]);
                    }
                } else if(arguments[0] == 'pos') {
                    if(arguments.length == 1) {
                        return [_css.apply(this, ['left']), _css.apply(this, ['top'])];
                    } else if(arguments[1] instanceof Array && arguments[1].length == 2) {
                        _css.apply(this, ['position', 'absolute']);
                        var left = arguments[1][0];
                        var top = arguments[1][1];
                        if(left == 'center') {
                            left = (this.parent().width()-this.width())/2;
                        } else if(left == 'right') {
                            left = this.parent().width() - this.width();
                        }
                        _css.apply(this, ['left', left]);
                        return _css.apply(this, ['top', top]);
                    }
                } else if(arguments[0] == 'padding') {
                    if(arguments[1] instanceof Array && arguments[1].length == 4) {
                        _css.apply(this, ['paddingLeft', arguments[1][0]]);
                        _css.apply(this, ['paddingTop', arguments[1][1]]);
                        _css.apply(this, ['paddingRight', arguments[1][2]]);
                        return _css.apply(this, ['paddingBottom', arguments[1][3]]);
                    }
                } else if(arguments[0] == 'margin') {
                    if(arguments[1] instanceof Array && arguments[1].length == 4) {
                        _css.apply(this, ['marginLeft', arguments[1][0]]);
                        _css.apply(this, ['marginTop', arguments[1][1]]);
                        _css.apply(this, ['marginRight', arguments[1][2]]);
                        return _css.apply(this, ['marginBottom', arguments[1][3]]);
                    }
                }
            }
            return _css.apply(this, arguments)
        }
    });
})();
var res = function(id) {
    return "https://momoqn.looosen.cn/"+id;
};
var makeArgsFunc = function() {
    var func = arguments[0];
    var args = (Array.prototype.slice.call(arguments)).slice(1);
    return function() {
        func.apply(this, args);
    };
};

$(document).ready(function(){
    if(navigator.userAgent.indexOf('iPhone') != -1) {
        document.body.addEventListener('focusout', function() {
            window.scrollTo(0, 0);
        });
    }
});