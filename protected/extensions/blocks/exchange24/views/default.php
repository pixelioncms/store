<?php

$this->widget('ext.highcharts.HighchartsWidget', array(
    'options' => array(
        'chart' => array(
            'type' => 'column',
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'backgroundColor' => 'rgba(255, 255, 255, 0)'
        ),
        'title' => array('text' => 'Банк Приват24'),
        'subtitle' => array(
            'text' => 'Курс обмен валюты'
        ),
        'xAxis' => array(
            'categories' => $categories
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

            
?>
<script>
    $(function(){
       $('#page-content-wrapper .container-fluid').resize(function(){
          
       });
       if($('#wrapper').hasClass('active')){
            console.log($(this).width());
          
       }

    });
    </script>


