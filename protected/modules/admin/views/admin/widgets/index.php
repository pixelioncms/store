
<?php

Yii::app()->tpl->openWidget(array(
    'title' => Yii::t('app', 'WIDGETS')
));
$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $data_db,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'title',
            'header' => 'Название файла',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'text-left'),
        ),
        array(
            'name' => 'alias',
            'header' => 'Название файла',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'text-left'),
        ),
        array(
            'name' => 'category',
            'header' => 'Категория',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'name' => 'edit',
            'header' => Yii::t('app', 'OPTIONS'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
        ),
    )
        )
);
Yii::app()->tpl->closeWidget();
?>

