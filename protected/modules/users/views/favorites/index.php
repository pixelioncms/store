

<?php

Yii::app()->tpl->alert($alert['type'], $alert['text'], false);
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'ajaxUpdate' => true,
    // 'itemsCssClass'=>'table table-striped',
    'autoColumns' => false,
    'enableHeader' => false,
    'columns' => array(
        array(
            'name' => 'owner_title',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->owner_title),array($data->url))',
        ),
        array(
            'class' => 'ButtonColumn',
            //'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
            'template' => '{delete}',
        ),
    ),
));
