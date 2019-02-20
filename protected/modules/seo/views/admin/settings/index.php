<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array()
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>

