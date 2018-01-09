<?php
/**
 * 添加节点
 * @author: Gene
 */
use yii\helpers\Url;

$this->title = '添加菜单';
?>
<style>

</style>
<div class="col-md-12 animated fadeIn node_edit">
    <div class="col-md-12" style="margin-bottom:20px;margin-top:20px;">
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
    </form>
</div>

<script>
    // 数据提交
    function submit(index, callback) {
        var data = new Object();
        data.name = $('#name').val();
        data.url = $('#url').val();
        data.sort = $('#sort').val();
        data.is_menu = $("[name='is_menu']:checked").val();
        data.status = $("[name='status']:checked").val();
        data.font_icon = $('#font_icon').val();
        data.pid = "<?= $pid ?>";

        app.post("<?= Url::toRoute('/system/node/save') ?>", data, function(res) {
            if (res !== false) {
                app.showMsg(res);
                app.parent.layer.close(index);
                callback(true);
            }

            callback(false);
        });
    }
</script>
