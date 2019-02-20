<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
//echo $form->tabs();
echo new CMSForm($model->getForm(),$model);
Yii::app()->tpl->closeWidget();
Yii::app()->clientScript->registerScript('mod.news.models.News', "
    init_translitter('mod.news.models.News', '{$model->primaryKey}', true);
", CClientScript::POS_END);
?>




