<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'autoColumns'=>false,
    'enableHeader'=>false,
    'filter'=>$model,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->name), array("update", "id"=>$data->id))',
        ),

        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',

        ),
    ),
));

Yii::app()->tpl->closeWidget();
?>



