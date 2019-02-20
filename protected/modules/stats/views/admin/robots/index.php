
<?php
$this->timefilter(false);
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
            'htmlOptions' => array('class' => 'text-center', 'width' => '5%')
        ),
        array(
            'name' => 'bot',
            'header' => 'Поисковый робот',
            'type' => 'raw',
            'htmlOptions' => array('width' => '30%')
        ),
        array(
            'name' => 'visit',
            'header' => 'Последний визит',
            'type' => 'raw',
            'htmlOptions' => array('width' => '30%')
        ),
        array(
            'name' => 'count',
            'header' => 'Кол-во страниц',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
        ),
        array(
            'name' => 'progressbar',
            'header' => Yii::t('StatsModule.default', 'GRAPH'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '45%')
        ),
        array(
            'name' => 'detail',
            'header' => Yii::t('StatsModule.default', 'DETAIL'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '45%')
        ),
    )
));

Yii::app()->tpl->closeWidget();
?>



