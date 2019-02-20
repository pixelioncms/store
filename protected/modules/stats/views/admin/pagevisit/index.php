<?php

$this->timefilter();
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));




$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'num',
            'header' => '№',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center','width'=>'5%')
        ),
        array(
            'name' => 'req',
            'header' => 'Посещаемая страница',
            'type' => 'raw',
        ),
        array(
            'name' => 'count',
            'header' => (($this->sort == "hi") ? Yii::t('StatsModule.default', 'HITS') : Yii::t('StatsModule.default', 'HOSTS')),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'graphic',
            'header' => Yii::t('StatsModule.default', 'GRAPH'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center','width'=>'33%')
        ),
        array(
            'name' => 'detail',
            'header' => Yii::t('app', 'OPTIONS'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center')
        ),
    )
));

Yii::app()->tpl->closeWidget();
?>
