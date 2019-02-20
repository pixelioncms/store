<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $form->tabs();
Yii::app()->tpl->closeWidget();

Yii::app()->clientScript->registerScript('mod.shop.models.ShopManufacturer', "
    init_translitter('mod.shop.models.ShopManufacturer', '{$model->primaryKey}', true);
", CClientScript::POS_END);


?>

