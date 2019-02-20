<?php
$active = $this->getActiveFilters();

if (!empty($active)) {
    ?>
    <div class="card bg-light" id="filter-current">

        <div class="card-header">
            <div class="panel-title"><?= Yii::t('ShopModule.default', 'CURRENT_FILTER_TITLE') ?></div>
        </div>
        <div class="card-body">
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'htmlOptions' => array('class' => 'current-filter-list'),
                'items' => $active
            ));
            echo Html::link(Yii::t('ShopModule.default', 'RESET_FILTERS_BTN'), $this->getOwner()->dataModel->getUrl(), array('class' => 'btn btn-xs btn-default'));
            ?>
        </div>

    </div>
<?php } ?>
