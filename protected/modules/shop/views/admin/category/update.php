<?php
$this->widget('ext.fancybox.Fancybox', array(
    'target' => 'a.overview-image',
    'config' => array(),
));

if (!ShopCategory::model()->findByPk(1)) {
    Yii::app()->tpl->alert('warning', Yii::t('ShopModule.admin', 'CREATE_ROOT_CATEGORY', array(
        '{link}' => Html::link(mb_strtolower(Yii::t('app', 'CREATE', 0)), array('/admin/shop/category/createRoot'), array('class' => 'btn btn-xs btn-success')))), false);
} else {
    ?>
    <div class="row">
        <div class="col-lg-7">
            <?php
            Yii::app()->tpl->openWidget(array(
                'title' => $this->pageName,
            ));
            echo $form->tabs();
            Yii::app()->tpl->closeWidget();
            ?>
        </div>


        <div class="col-lg-5">
            <?php $this->renderPartial('_categories', array('model' => $model)); ?>
        </div>
    </div>
    <?php

    Yii::app()->clientScript->registerScript('mod.shop.models.ShopCategory', "
    init_translitter('mod.shop.models.ShopCategory', '{$model->primaryKey}', true);
", CClientScript::POS_END);
}

