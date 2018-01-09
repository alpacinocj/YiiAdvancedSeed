<?php
/**
 * 节点编辑
 * @author: Gene
 */

use yii\helpers\Url;
$this->title = '菜单管理';
?>
<link href="/js/plugins/jsTree/themes/default/style.min.css" rel="stylesheet">
<style>
html, body, .container-fluid, .row{height:100%}
.node_edit{
    height: 100%;
    overflow: hidden;
    overflow-y: auto;
    border-left: 1px solid #e2e2e2;
}
.nodes h3, .node_edit h3{
    background: #607D8B;
    margin-top: 20px;
    height: 30px;
    line-height: 30px;
    color: #fff;
    padding-left: 10px;
    font-size: 14px;
    margin-bottom: 10px;
}
#menuBox {
    margin-top: 10px;
    background: #eaeaea;
    padding:20px 0;
}
</style>

<div class="row">
    <div class="col-sm-3 nodes">
        <h3>节点列表</h3>
        <div class="btn-group" role="group">
            <button data-title="添加节点菜单"
                    data-url="<?= Url::toRoute('/system/node/create') ?>"
                    type="button" class="btn btn-info btn-xs open_save">创建</button>
            <button id="del" type="button" class="btn btn-danger btn-xs">删除</button>
        </div>

        <div id="menuBox"></div>
    </div>

    <div class="col-sm-9 animated fadeIn node_edit">
        <h3>信息编辑</h3>

        <div class="col-md-12" style="margin-bottom:20px;">
            <div class="bg-info" style="padding:10px;">
                <b>说明：</b>
                <p>1、url为域名后面访问的路径地址,如:http://www.afd56.com/role/add,则url地址为:/role/add;</p>
                <p>2、是否菜单说明:选择是,则将会作为菜单来显示</p>
                <p>3、字体图标,请输入Font Awesome官方提供的图标库,如:eye</p>
            </div>
        </div>

        <form class="form-horizontal layui-form" method="post">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" class="form-control" placeholder="名称" />
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">url地址</label>
                    <div class="layui-input-inline">
                        <input type="text" id="url" class="form-control" placeholder="url地址,如:/role/index" />
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" value="0" id="sort" class="form-control" placeholder="排序" />
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">图标</label>
                    <div class="layui-input-inline">
                        <input type="text" value="circle" id="font_icon" class="form-control" placeholder="字体图标" />
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">菜单</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="is_menu" class="form-control" title="是" value="1" checked />
                        <input type="radio" name="is_menu" class="form-control" title="否" value="0" />
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <input type="radio" name="status" value="1" class="form-control" title="开启" checked />
                        <input type="radio" name="status" value="0" class="form-control" title="关闭" />
                    </div>
                </div>
            </div>

            <div class="layui-form-item" style="padding-left:20px;">
                <button type="button" onclick="save()" class="layui-btn layui-btn-normal">保存信息</button>
            </div>
        </form>
    </div>
</div>

<script src="/js/plugins/jsTree/jstree.min.js"></script>
<script>
    var id = 0;
    $(function() {
        $('#del').click(function() {
            layer.confirm('你确认要删除该节点或菜单吗？删除不可恢复', {
                btn: ['确认', '取消']
            }, function(i) {
                layer.close(i);
                app.post("<?= Url::toRoute('/system/node/del') ?>", {
                    id : id
                }, function(res) {
                    if (res !== false) {
                        app.showMsg(res);
                        initData();
                    }
                });
            });
        });

        $('.open_save').click(function() {
            var url = $(this).attr('data-url');
            var title = $(this).attr('data-title');

            app.openParentWin({
                url  : url + '?pid=' + id,
                title: title,
                callback: function(index, dom) {
                    $(dom).find('iframe')[0].contentWindow.submit(index, function(res) {
                        if (res === true) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
        initData();
    });

    // 保存信息
    function save() {
        var data  = new Object();
        data.name = $('#name').val();
        data.url  = $('#url').val();
        data.sort = $('#sort').val();
        data.font_icon = $('#font_icon').val();
        data.is_menu   = $("[name='is_menu']:checked").val();
        data.status    = $("[name='status']:checked").val();
        data.id = id;

        if (id == 0) {
            app.showMsg('请选择需要修改的节点', 'error');
            return false;
        }
        app.post("<?= Url::toRoute('/system/node/save') ?>", data, function(res) {
            if (res !== false) {
                app.showMsg(res, 'success');

                initData();
            }
        });
    }

    function initData() {
        app.post("<?= Url::toRoute('/system/node/get-data') ?>", {}, function(res) {
            if (res !== false) {
                // 制空数据
                id = 0;
                $('#name').val('');
                $('#url').val('');
                $('#sort').val(0);
                $('#font_icon').val('circle');

                $('#menuBox').data('jstree', false).empty();
                $("#menuBox").jstree({
                    'core' : {
                        'multiple' : false,
                        'data'     : res,
                        'dblclick_toggle' : false, // 禁用双击展开
                    }
                }).bind('click.jstree', function(e) {
                    id = $(e.toElement).parent('li').attr('id');
                    app.post("<?= Url::toRoute('/system/node/get-info') ?>", {
                        id : id
                    }, function(res) {
                        if (res !== false || res !== null) {
                            $('#name').val(res.name);
                            $('#url').val(res.url);
                            $('#sort').val(res.sort);
                            $('#font_icon').val(res.font_icon);

                            $("[name='is_menu']").removeAttr('checked');
                            $("[name='status']").removeAttr('checked');

                            $(":radio[name='is_menu'][value='" + res.is_menu + "']").prop('checked', 'checked');
                            $(":radio[name='status'][value='" + res.status + "']").prop('checked', 'checked');
                            app.form.render('radio');
                        }
                    });
                });
            }
        });
    }
</script>
