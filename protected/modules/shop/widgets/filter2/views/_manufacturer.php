<div class="card filter-block">
    <div class="card-header collapsed" data-toggle="collapse"
         data-target="#filter<?= md5('manufacturer'); ?>">
        <h5 class="panel-title"><?= Yii::t('ShopModule.default', 'FILTER_MANUFACTURER') ?></h5>
    </div>
    <div class="card-collapse collapse" id="filter<?= md5('manufacturer'); ?>">
    <div class="card-body">
        <ul class="filter-list">
            <?php
            foreach ($manufacturers['filters'] as $filter) {
                $url = Yii::app()->request->addUrlParam('/shop/category/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany']);
                $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));

                echo Html::openTag('li');

                if ($filter['count'] > 0) {
                    $countHtml = Html::tag('sup', array(), $filter['count'], true);
                } else {
                    $countHtml = Html::tag('sup', array(), 0, true);
                }

                // Filter link was selected.
                if (in_array($filter['queryParam'], $queryData)) {
                    // Create link to clear current filter
                    $url = Yii::app()->request->removeUrlParam('/shop/category/view', $filter['queryKey'], $filter['queryParam']);
                    echo Html::link($filter['title'], $url, array('class' => 'active', 'rel' => 'nofollow'));
                } else {
                    echo Html::link($filter['title'], $url, array('rel' => 'nofollow'));
                }
                echo $this->getCount($filter) . Html::closeTag('li');
            }
            ?>
        </ul>
    </div>
    </div>
</div>
