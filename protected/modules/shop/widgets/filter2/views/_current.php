<?php
$active = $this->getActiveFilters();

if (!empty($active)) {
    ?>
    <div class="card" id="filter-current">


        <div class="card-header collapsed" data-toggle="collapse" data-target="#filter<?= md5('current-filter'); ?>">
            <h5><?= Yii::t('ShopModule.default', 'CURRENT_FILTER_TITLE') ?></h5>
        </div>
        <div class="card-collapse collapse" id="filter<?= md5('current-filter'); ?>">
            <div class="card-body">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'htmlOptions' => array('class' => 'current-filter-list'),
                    'items' => $active
                ));
                echo Html::link(Html::icon('icon-refresh').' '.Yii::t('ShopModule.default', 'RESET_FILTERS_BTN'), $this->getOwner()->dataModel->getUrl(), array('class' => 'btn btn-sm btn-outline-secondary'));
                ?>
            </div>
        </div>
    </div>
<?php } ?>
