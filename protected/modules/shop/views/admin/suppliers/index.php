<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName
));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'enableHeader'=>false,
    'autoColumns'=>false,
    'columns' => array(
        array(
            'class' => 'CheckBoxColumn',
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => '$data->getGridName()',
        ),
        array(
            'name' => 'phone',
            'type' => 'raw',
            'value' => 'Html::tel($data->phone)',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'name' => 'email',
            'type' => 'raw',
            'value' => 'Html::mailto($data->email)',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));
Yii::app()->tpl->closeWidget();
