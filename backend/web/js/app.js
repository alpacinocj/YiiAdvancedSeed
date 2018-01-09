
// 日期扩展
Date.prototype.format = function(format) {
    var date = {
        'M+' : this.getMonth() + 1,
        'd+' : this.getDate(),
        'h+' : this.getHours(),
        'm+' : this.getMinutes(),
        's+' : this.getSeconds(),
        'q+' : Math.floor((this.getMonth() + 3) / 3),
        'S+' : this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp('(' + k + ')').test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? date[k] : ('00' + date[k]).substr(('' + date[k]).length));
        }
    }

    return format;
}

// 数组扩展
Array.prototype.indexOf = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }

    return -1;
};
Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};



/**
 * core javascript file
 * @author: Gene
 * version: 1.0.0
 */
function Core() {
    var self = this;
    this.name = '阿凡达物流管理系统';

    // 获取最顶级的窗口对象
    this.parent = window.top;

    // 常用正则表达式
    this.regular = {
        'phone'    : /^1[3,4,5,7,8][0-9]{9}$/,
        'email'    : /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/,
        'url'      : /^((https|http):\/\/)[^\s]+$/,
        'name'     : /^[a-zA-Z]\w{3,20}$/,// 用户名(只能包含字母、数字和下划线，且必须以字母开头)
        'password' : /^.{6,}/,// 密码(长度必须大于六位以上)
        'space'    : /\s+/g,// 空格
        'wrap'     : /[\r\n]/g// 换行
    }

    this.layer   = null;
    this.laydate = null;
    this.form    = null;

    layui.use(['layer', 'laydate', 'form'], function() {
        self.layer   = layui.layer;
        self.laydate = layui.laydate;
        self.form    = layui.form;
    });

    this.init();
}


// 初始化
Core.prototype.init = function() {
    var self = this;

    $('.open_page').click(function() {
        var url   = $(this).attr('data-url');
        var title = $(this).attr('data-title');

        self.openParentWin({
            url  : url,
            title: title
        });
    });
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
    var loading, self = this;
    data.token = window.token;
    layui.use('layer', function() {
        var layer = layui.layer;
        $.ajax({
            type : 'POST',
            url  : url,
            data : data,
            dataType: 'json',
            success : function(res) {
                layer.close(loading);
                if (res.code == 0) {
                    callback(res.data);
                } else {
                    self.showMsg(res.msg, 'error');
                    callback(false);
                }
            },
            beforeSend : function() {
                loading = layer.load(0, {shade: false});
            },
            error: function() {
                layer.close(loading);
                self.showMsg('网络请求失败!', 'error');
                callback(false);
            }
        });
    });
}


/**
 * 关闭当前窗口,刷新父窗口
 */
Core.prototype.reloadParent = function(millisec) {
    millisec = millisec > 0 ? millisec : 1;
    setTimeout(function() {
        parent.window.location.reload();
    }, millisec);
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


/**
 * 全选操作
 * @type {Core}
 */
Core.prototype.selectAll = function (node) {
    $(node).click(function() {
        var isChecked = $(this).is(':checked');
        var index = $(this).attr('data-index');

        if (isChecked) {
            $(node + '_' + index).prop('checked', true);
        } else {
            $(node + '_' + index).prop('checked', false);
        }
    });
}

/**
 * 上传文件组件
 * @param param
 * @param callback
 */
Core.prototype.uploadImg = function(param, callback) {
    var loading,
        self = this,
        multiple = false,
        data = new Object();
    data._csrf = window._csrf;

    if (typeof param.multiple != 'undefined') {
        multiple = param.multiple;
    }
    if (typeof param.data != 'undefined') {
        data = param.data;
        data._csrf = window._csrf;
    }

    layui.use('layer', function() {
        var layer = layui.layer;
        var uploader = WebUploader.create({
            swf: '/res/plugins/webuploader-0.1.5/Uploader.swf',
            server: "/api/upload/img",
            formData: data,
            pick: {
                id      : param.id,
                label   : param.name,
                multiple: multiple,
            },
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,png',
                mimeTypes: 'image/*'
            },
            auto : true
        });
        // 开始上传
        uploader.on('uploadStart', function(file) {
            loading = layer.load(0, {shade: false});
        });
        // 文件上传成功
        uploader.on('uploadSuccess', function(file, res) {
            if (res.code != 0) {
                self.showMsg(res.msg, 'error');
                callback(false);
            } else {
                callback(res.data);
            }
        });
        // 上传失败
        uploader.on('uploadError', function(file, res) {
            self.showMsg('网络请求错误', 'error');
            callback(false);
        });
        // 文件上传完成,不管成功还是失败都会执行
        uploader.on('uploadComplete', function(file) {
            layer.close(loading);
        });
        // 验证不通过执行
        uploader.on('error', function(type) {

        });
    });
}


Core.prototype.openNewTab = function() {
    var self = this;
    var url = arguments[0];
    var title = arguments[1] ? arguments[1] : '新页面';

    self.parent.app.openNewTab(url, title);
}

/**
 * 退出登录,跳转到登录页面
 */
Core.prototype.logout = function() {
    window.top.location.href = '/login/logout';
}


/**
 * 在父窗口中打开窗口
 * @param param
 */
Core.prototype.openParentWin = function(param) {
    var newParam = new Object();
    if (typeof param != 'object') {
        throw '参数必须是一个对象';
    }

    if (typeof param.title == 'undefined') param.title = '新窗口';
    if (typeof param.url == 'undefined') param.url = '';
    if (typeof param.width == 'undefined') param.width = '80%';
    if (typeof param.height == 'undefined') param.height = '90%';
    if (typeof param.callback != 'undefined') {
        newParam.btn = ['确认', '取消'];
        newParam.yes = function(index, res) {
            param.callback(index, res);
        };
    }
    newParam.type = 2;
    newParam.anim = 0;
    newParam.shade = 0.8;
    newParam.title = param.title;
    newParam.area = [param.width, param.height];
    newParam.content = param.url;

    this.parent.app.openPage(newParam);
}


Core.prototype.showMsg = function() {
    var msg = arguments[0] ? arguments[0] : '操作成功!';
    var type= arguments[1] ? arguments[1] : 'success';
    var position = arguments[2] ? arguments[2] : 'toast-top-center';

    window.top.app.showMsg(msg, type, position);
}

$(function() {
    window.app = new Core();
});
