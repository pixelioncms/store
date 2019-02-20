<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

echo Html::beginForm('', 'post', array(
    'id' => 'ShopProductTypeForm',
));
echo Html::errorSummary($model);

echo Html::hiddenField('main_category', $model->main_category);

$this->widget('mod.admin.widgets.AdminTabs', array(
    'tabs' => array(
        Yii::t('app', 'OPTIONS') => $this->renderPartial('_options', array('model' => $model, 'attributes' => $attributes), true),
        Yii::t('ShopModule.admin', 'Категории') => $this->renderPartial('_tree', array('model' => $model), true),
    )
));
?>

<div class="form-group row text-center">
    <div class="col">

        <?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
    </div>
</div>
<?php echo Html::endForm();
Yii::app()->tpl->closeWidget();
?>

