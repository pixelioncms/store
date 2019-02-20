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
        array('class' => 'ext.sortable.SortableColumn'),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => '$data->name',
        ),
        array(
            'header'=>'Адресов',
            'type' => 'raw',
            'value' => '$data->countAddress',
            'htmlOptions'=>array('class'=>'text-center')
        ),

        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',

        ),
    ),
));

Yii::app()->tpl->closeWidget();
?>



