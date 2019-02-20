<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName
));
if ($model->isService) {
    echo CHtml::openTag('div', array('class' => ''));
    Yii::app()->tpl->alert('info', Yii::t('UsersModule.default', 'USER_IS_SERVICE', array('{service}' => $model->service)));
    echo CHtml::closeTag('div');
}
echo $model->getForm();
Yii::app()->tpl->closeWidget();
