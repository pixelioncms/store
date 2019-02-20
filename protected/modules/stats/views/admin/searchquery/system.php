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
            'header' => '#',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
        ),
        array(
            'name' => 'engine',
            'header' => 'Поисковый запрос',
            'type' => 'raw',
            'htmlOptions' => array('width' => '20%')
        ),
        array(
            'name' => 'val',
            'header' => (($this->sort == "hi") ? Yii::t('StatsModule.default', 'HITS') : Yii::t('StatsModule.default', 'HOSTS')),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '20%')
        ),
        array(
            'name' => 'progressbar',
            'header' => Yii::t('StatsModule.default', 'GRAPH'),
            'type' => 'raw',
            'htmlOptions' => array('width' => '70%')
        ),
    )
));

Yii::app()->tpl->closeWidget();
