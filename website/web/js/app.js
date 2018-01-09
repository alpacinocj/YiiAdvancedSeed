/**
 * core javascript file
 * @author: Gene
 * version: 1.0.0
 */
function Core() {
    var self  = this;

    // 常用正则表达式
    self.regular = {
        'phone'    : /^1[3,4,5,7,8][0-9]{9}$/,
        'email'    : /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/,
        'url'      : /^((https|http):\/\/)[^\s]+$/,
        // 用户名(只能包含字母、数字和下划线，且必须以字母开头)
        'name'     : /^[a-zA-Z]\w{3,20}$/,
        'password' : /^.{6,}/,// 密码(长度必须大于六位以上)
        'space'    : /\s+/g,// 空格
        'wrap'     : /[\r\n]/g// 换行
    }

    self.init();
}

// 初始化
Core.prototype.init = function() {
    var self = this;
}

/**
 * 时间戳格式化
 * @author: Gene
 */
Core.prototype.showFormatDate = function() {
    var time  = arguments[0] || 0;
    var format= arguments[1] || 'yyyy-MM-dd h:m:s';

    var date = new Date(time*1000);

    return date.format(format);
}


/**
 * post请求封装
 * @auatho: Gene
 * @param url
 * @param data
 * @param callback
 */
Core.prototype.post = function(url, data, callback) {
    var self   = this;
    data.token = window.token;
    $.ajax({
        type : 'POST',
        url  : url,
        data : data,
        dataType: 'json',
        success : function(res) {
            if (res.code == 0) {
                callback(res.data);
            } else {
                callback(false);
            }
        },
        beforeSend : function() {
            // loading = layer.load(0, {shade: false});
        },
        error: function() {
            callback(false);
        }
    });
}


/**
 * 无刷新请求下载文件
 * @author: Gene
 * @param: url 请求地址
 * @param: params Object 参数
 * @type {Core}
 */
Core.prototype.download = function() {
    var url    = arguments[0] || false;
    var params = arguments[1] || false;
    var self   = this, newParams = '';

    try {
        if (typeof params == 'object') {
            console.log(333)
            for(var i in params) {
                newParams += '&' + i + '=' + params[i];
            }

            url += '?' + newParams.substr(1);
        }
    } catch(err) {}

    if (typeof self.download.iframe == 'undefined') {
        var iframe = document.createElement('iframe');
        self.download.iframe = iframe;
        document.body.appendChild(self.download.iframe);
    }

    self.download.iframe.src = url;
    self.download.iframe.style.display = 'none';
}

$(function() {
    window.app = new Core();
});