<?php




$this->widget('ext.highcharts.HighchartsWidget', array(
    'options' => array(
        'chart' => array(
            'type' => 'spline'
        ),
        'title' => array('text' => 'Национальный Банк Украины'),
        'subtitle' => array(
            'text' => 'Покупка'
        ),
        'xAxis' => array(
            'categories' => $data['USD']['data']
       // 'categories' => $categories
        ),
        'yAxis' => array(
            'title' => false
        ),
        'tooltip' => array(
            'crosshairs' => true,
            'shared' => true
        ),
        'series' => $series
    )
));
