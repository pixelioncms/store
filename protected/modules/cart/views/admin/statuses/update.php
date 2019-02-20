<?php

if (!$model->isNewRecord && $model->id == 1) {
    Yii::app()->tpl->alert('warning', Yii::t('CartModule.admin', 'STATUS_NEW_NOTIFY'), false);
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

echo $model->getForm();
Yii::app()->tpl->closeWidget();
?>
