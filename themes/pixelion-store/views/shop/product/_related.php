<div id="relatedProducts" class="_view_grid">
    <?php foreach ($model->relatedProducts as $data){ ?>

        <?php Yii::app()->controller->renderPartial('current_theme.views.shop.category._view_grid',array(
            'data'=>$data
        )); ?>

    <?php } ?>

</div>

