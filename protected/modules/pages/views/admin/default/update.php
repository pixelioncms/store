<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $form->tabs();
Yii::app()->tpl->closeWidget();

Yii::app()->clientScript->registerScript('mod.pages.models.Page', "
    init_translitter('mod.pages.models.Page', '{$model->primaryKey}', true);
", CClientScript::POS_END);

?>





