<?php
if (!$model->isNewRecord && !empty(Yii::app()->settings->get('shop', 'auto_gen_product_title'))) {
    Yii::app()->tpl->alert('warning', Yii::t('ShopModule.admin', 'ENABLE_AUTOURL_MODE'));
}
if(!$model->isNewRecord) {
    ?>
    <div class="row mb-3 mt-4">
        <div class="col-sm-12 col-md-6 col-lg-3">
            <h6>Просмотрели <span class="badge badge-secondary"><?= $model->views; ?></span> раз</h6>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <h6>Добавили в корзину <span class="badge badge-secondary"><?= $model->added_to_cart_count; ?></span></h6>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <h6>Комментариев <span class="badge badge-secondary"><?= $model->commentsCount; ?></span></h6>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
            <h6>Рейтинг: <?php $this->widget('ext.rating.StarRating', array('model' => $model, 'readOnly' => true)); ?></h6>
        </div>
    </div>
    <?php
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
$typesList = ShopProductType::model()->orderByName()->findAll();
if (count($typesList) > 0) {
// If selected `configurable` product without attributes display error
    if ($model->isNewRecord && $model->use_configurations == true && empty($model->configurable_attributes))
        $attributeError = true;
    else
        $attributeError = false;

    if ($model->isNewRecord && !$model->type_id || $attributeError === true) {
        // Display "choose type" form
        echo Html::form('', 'get', array('class' => ''));
        Yii::app()->clientScript->registerScriptFile(
            $this->module->assetsUrl . '/admin/products.js', CClientScript::POS_END
        );
        if ($attributeError) {
            Yii::app()->tpl->alert('danger', Yii::t('ShopModule.admin', 'Выберите атрибуты для конфигурации продуктов.'), false);
        }
        ?>
        <div class="form-group row">
            <?= Html::activeLabel($model, 'type_id', array('class' => 'col-sm-4 col-form-label')); ?>
            <div class="col-sm-8">
                <?php
                echo Html::activeDropDownList($model, 'type_id', CHtml::listData($typesList, 'id', 'name'), array('class' => 'form-control'));
                ?>
            </div>
        </div>

        <div class="form-group row">
            <?= Html::activeLabel($model, 'use_configurations', array('class' => 'col-sm-4 col-form-label')); ?>
            <div class="col-sm-8">
                <?php
                echo Html::activeDropDownList($model, 'use_configurations', array(0 => Yii::t('app', 'NO'), 1 => Yii::t('app', 'YES')), array('class' => 'form-control'));

                ?>
            </div>
        </div>

        <div id="availableAttributes" class="form-group row d-none"></div>

        <div class="form-group row text-center">
            <div class="col-sm-12">
                <?= Html::submitButton(Yii::t('app', 'CREATE', 0), array('name' => false, 'class' => 'btn btn-success')); ?>
            </div>
        </div>
        <?php
        echo Html::endForm();
    } else { ?>

        <?= $form->tabs(); ?>
    <?php }

} else {
    Yii::app()->tpl->alert('info', 'Для начало необходимо создать Тип товара', false);
}
Yii::app()->tpl->closeWidget();
?>

<?php

Yii::app()->clientScript->registerScript('mod.shop.models.ShopProduct', "
    init_translitter('mod.shop.models.ShopProduct', '{$model->primaryKey}', true);
", CClientScript::POS_END);
//if (!$model->isNewRecord) {  ?>
<?php //}  ?>
