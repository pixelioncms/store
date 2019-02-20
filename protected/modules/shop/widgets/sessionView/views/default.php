<?php if (count($model) > 0) { ?>
    <div class="line-title">
        <div class="container">
            <h2>Вы интересовались</h2>
        </div>
    </div>

    <div id="<?= $this->id ?>" class="owl-carousel owl-products">
        <?php foreach ($model as $data) { ?>
            <?php Yii::app()->controller->renderPartial('current_theme.views.shop.category._view_grid', array(
                'data' => $data
            )); ?>
        <?php } ?>
    </div>
<?php } ?>