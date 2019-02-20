<?php


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
            'name' => 'date',
            'header' => 'Дата',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
        ),
        array(
            'name' => 'time',
            'header' => 'Время',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '5%')
        ),
        array(
            'name' => 'refer',
            'header' => 'Referer',
            'type' => 'raw',
            'htmlOptions' => array('width' => '10%')
        ),
        array(
            'name' => 'ip',
            'header' => 'IP-адрес',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
        ),
        array(
            'name' => 'host',
            'header' => 'Хост',
            'type' => 'raw',
            'htmlOptions' => array('width' => '10%')
        ),
        array(
            'name' => 'user_agent',
            'header' => 'User-agent',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
        ),
        array(
            'name' => 'page',
            'header' => 'Страница',
            'type' => 'raw',
            'htmlOptions' => array('width' => '45%')
        ),

        
    )
));

Yii::app()->tpl->closeWidget();
?>
