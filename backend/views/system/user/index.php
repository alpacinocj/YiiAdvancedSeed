<?php
/**
 * 用户列表
 * @author: Gene
 */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '管理员列表';
?>

<div class="row" style="margin-top:20px;">
    <div class="col-md-12">
        <div>
            <a href="#" class="btn btn-default btn-sm open_save"
               data-title="添加系统用户"
               data-url="<?= Url::toRoute('/system/user/create') ?>">
                <i class="fa fa-plus"></i> 添加
            </a>
        </div>
        <hr>

        <form class="layui-form" method="get">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">邮箱:</label>
                    <div class="layui-input-inline">
                        <input type="text" value="<?= $email ?>" name="email" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态:</label>
                    <div class="layui-input-inline">
                        <select name="status">
                            <option value="all" <?= $status == 'all' ? 'selected' : '' ?> >全部</option>
                            <option value="1" <?= $status == '1' ? 'selected' : '' ?> >正常</option>
                            <option value="0" <?= $status == '0' ? 'selected' : '' ?> >禁用</option>
                        </select>
                    </div>
                </div>

                <div class="layui-inline">
                    <button type="submit" class="btn btn-info">查询</button>
                </div>
            </div>
        </form>
        <hr>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                <tr class="active">
                    <th class="text-center">#UID</th>
                    <th class="text-center">登录邮箱</th>
                    <th class="text-center">姓名</th>
                    <th class="text-center">联系号码</th>
                    <th class="text-center">生日</th>
                    <th class="text-center">角色</th>
                    <th class="text-center">添加时间</th>
                    <th class="text-center">状态</th>
                    <th class="text-center">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($result)) { ?>
                    <tr>
                        <td class="text-center" colspan="20">暂无数据</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($result as $item) { ?>
                        <tr>
                            <td class="text-center"><?= Html::encode($item['id']) ?></td>
                            <td class="text-center"><?= Html::encode($item['email']) ?></td>
                            <td class="text-center"><?= Html::encode($item['real_name']) ?></td>
                            <td class="text-center"><?= Html::encode($item['phone']) ?></td>
                            <td class="text-center"><?= Html::encode($item['birth_date']) ?></td>
                            <td class="text-center">
                                <?php if ($item['email'] == Yii::$app->params['sys_user_email']) { ?>
                                    <span>超级管理员</span>
                                <?php } else if (is_array($item['roles'])) { ?>
                                    <?php foreach ($item['roles'] as $r) { ?>
                                        <p><?= $r['name'] ?></p>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td class="text-center"><?= $item['created'] ?></td>
                            <td class="text-center"><?= $statusList[$item['status']] ?></td>
                            <td class="text-center">
                                <a href="#"
                                   class="open_save"
                                   data-url="<?= Url::toRoute([
                                       '/system/user/update',
                                       'id'=>$item['id']
                                   ]) ?>"
                                   data-title="编辑管理员"
                                >修改</a>
                            </td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>

        <div class="item-pagination pull-right">
            <ul class="pagination">
                <li><a>共 <?= $total ?> 条</a></li>
            </ul>
            <?= \yii\widgets\LinkPager::widget([
                'pagination'     => $page,
                'maxButtonCount' => 10,
                'nextPageLabel'  => '下一页',
                'prevPageLabel'  => '上一页',
                'firstPageLabel' => '首页',
                'lastPageLabel'  => '尾页',
            ]); ?>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.open_save').click(function() {
            var url = $(this).attr('data-url');
            var title = $(this).attr('data-title');
            app.openParentWin({
                url  : url,
                title: title,
                callback: function(index, dom) {
                    $(dom).find("iframe")[0].contentWindow.submit(index, function(res) {
                        if (res === true) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    });
</script>