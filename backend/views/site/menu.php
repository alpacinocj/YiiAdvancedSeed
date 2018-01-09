<?php
/**
 * 菜单页面
 * @author: Gene
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php foreach ($menu as $row) { ?>
    <li>
        <a href="#">
            <i class="fa fa-<?= $row['font_icon'] ? $row['font_icon'] : 'leaf' ?>"></i>
            <span class="nav-label"><?= Html::encode($row['text']) ?></span>
            <span class="fa arrow"></span>
        </a>

        <ul class="nav nav-second-level">
            <?php  foreach ($row['children'] as $r) {?>

            <?php if (isset($r['children']) && !empty($r['children'])) { ?>
                <li>
                    <a href="#">
                        <span class="nav-label"><?= Html::encode($r['text']) ?></span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-third-level">
                        <?php foreach ($r['children'] as $rr) { ?>
                            <li>
                                <a class="J_menuItem" href="<?= Url::toRoute($rr['url_key']) ?>"><?= Html::encode($rr['text']) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

            <?php } else { ?>
                <li>
                    <a class="J_menuItem" href="<?= Url::toRoute($r['url_key']) ?>"><?= Html::encode($r['text']) ?></a>
                </li>
            <?php }} ?>
        </ul>
    </li>
<?php } ?>