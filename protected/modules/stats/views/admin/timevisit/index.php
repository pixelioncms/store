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
            'name' => 'time',
            'header' => 'Время',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
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


Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
$this->Widget('ext.highcharts.HighchartsWidget', array(
    'scripts' => array(
        'highcharts-more',
        // 'columnrange', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
        'modules/exporting', // adds Exporting button/menu to chart
    ),
    'options' => array(
        'chart' => array(
            // 'width'=> '100%',

            'defaultSeriesType' => 'areaspline',
            'type' => 'column',
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'backgroundColor' => 'rgba(255, 255, 255, 0)'
        ),
        'credits' => array(
            'enabled' => false
        ),
        'exporting' => array(
            'buttons' => array(
                'contextButton' => array(
                    'menuItems' => array(array(
                            'text' => 'Export to PNG (small)',
                            'onclick' => 'js:function () {
                            this.exportChart({
                                width: 250
                            });
                        }'
                        ), array(
                            'text' => 'Export to PNG (large)',
                            'onclick' => 'js:function () {
                            this.exportChart();
                        }',
                            'separator' => false
                        ))
                )
            )
        ),
        'title' => array('text' => 'Время посещение'),
        'subtitle' => array(
            'text' => 'График времени посещения с ' . date('Y-m-d', strtotime($this->sdate)) . ' по ' . date('Y-m-d', strtotime($this->fdate)) . ''
        ),
        'xAxis' => array(
            'categories' => $times
        ),
        'yAxis' => array(
            'title' => array('text' => null),
            'visible' => false
        ),
        'plotOptions' => array(
            'column' => array(
                'dataLabels' => array(
                    'enabled' => true,
                ),
            ),
            'series' => array(
                'cursor' => 'pointer',
                'point' => array(
                    'events' => array(
                        'click' => "js:function (e) {
                            console.log(this.options);
                            location.href = this.options.url;
                        }"
                    )
                ),
                'marker' => array(
                    'lineWidth' => 1,
                    'enabled' => false
                )
            )
        ),
        'series' => array(
            array('name' => (($this->sort == "hi") ? Yii::t('StatsModule.default', 'HITS') : Yii::t('StatsModule.default', 'HOSTS')), 'data' => $visits),
        )
    )
));


Yii::app()->tpl->closeWidget();
?>
