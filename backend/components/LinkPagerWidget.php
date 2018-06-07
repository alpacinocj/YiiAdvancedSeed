<?php

namespace backend\components;

use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class LinkPagerWidget extends Widget
{
    /**
     * @var Pagination
     */
    public $pagination;

    public $maxButtonCount = 6;

    public $prevPageLabel = '上一页';

    public $nextPageLabel = '下一页';

    public $firstPageLabel = '首页';

    public $lastPageLabel = '尾页';

    public $totalCountCssClass = 'total_count';

    public $totalPageCssClass = 'total_page';

    public function init()
    {
        parent::init();
    }

    public function getViewPath()
    {
        return \Yii::getAlias('@backend/views/widgets');
    }

    public function run()
    {
        return $this->render('link_pager', [
            'total'             => $this->pagination->totalCount,
            'totalCountCssClass'=> $this->totalCountCssClass,
            'totalPageCssClass' => $this->totalPageCssClass,
            'totalPage'         => $this->pagination->getPageCount(),
            'pagination'        => $this->pagination,
            'maxButtonCount'    => $this->maxButtonCount,
            'nextPageLabel'     => $this->nextPageLabel,
            'prevPageLabel'     => $this->prevPageLabel,
            'firstPageLabel'    => $this->firstPageLabel,
            'lastPageLabel'     => $this->lastPageLabel
        ]);
    }
}