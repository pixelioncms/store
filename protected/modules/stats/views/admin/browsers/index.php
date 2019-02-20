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
            'htmlOptions' => array('class' => 'text-center', 'width' => '5%')
        ),
        array(
            'name' => 'browser',
            'header' => 'Браузер',
            'type' => 'raw',
            'htmlOptions' => array('width' => '20%')
        ),
        array(
            'name' => 'val',
            'header' => 'Хосты',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '5%')
        ),
        array(
            'name' => 'progressbar',
            'header' => 'График',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '15%')
        ),
        array(
            'name' => 'detail',
            'header' => Yii::t('StatsModule.default', 'DETAIL'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
        ),
    )
));

Yii::app()->tpl->closeWidget();


$this->Widget('ext.highcharts.HighchartsWidget', array(
    'scripts' => array(
        'highcharts-more',
        // 'columnrange', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
        'modules/exporting', // adds Exporting button/menu to chart
    ),
    'options' => array(
        'chart' => array(
            'height' => 500,
            'defaultSeriesType' => 'areaspline',
            'type' => 'pie',
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'backgroundColor' => 'rgba(255, 255, 255, 0)'
        ),
        'credits' => array(
            'enabled' => false
        ),
        'exporting' => false,
        'title' => array('text' => null),
        'subtitle' => array(
            //'text' => "Monitoring: " . date('F', mktime(0, 0, 0, substr($m_dt[0], 0, 2), 1, 0)) . " " . $m_gd[0] . " - " . date('F', mktime(0, 0, 0, substr($m_dt[count($m_dt) - 1], 0, 2), 1, 0)) . " " . $m_gd[count($m_gd) - 1],
        ),
        'xAxis' => false,
        'yAxis' => array(
            'title' => array('text' => null),
            'visible' => false
        ),
        'plotOptions' => array(
            'pie' => array(
                'allowPointSelect' => true,
                'dataLabels' => array(
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.hosts} ',//{point.percentage:.1f} %
                   // 'connectorColor' => 'silver'
               ),
            ),

        ),
        'series' => array(
            array('data' => $pie),
        )
    )
));
?>