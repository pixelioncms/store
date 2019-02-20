
<div class="row">
    <div class="col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Статистика по месяцам</div>
            </div>
            <div class="panel-body">
                <?php
                $this->Widget('ext.highcharts.HighchartsWidget', array(
                    'scripts' => array(
                        'highcharts-more',
                        // 'columnrange', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                        'modules/exporting', // adds Exporting button/menu to chart
                    ),
                    'options' => array(
                        'chart' => array(
                            'height' => 250,
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
                            'column' => array(
                                'dataLabels' => array(
                                    'enabled' => false,
                                ),
                            ),
                            'series' => array(
                                'cursor' => 'pointer',
                                'point' => array(
                                    'events' => array(
                                        'click' => "js:function (e) {
//location.href = this.options.url;
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
                            array('name' => 'Скоро', 'data' => array(0)),
                        )
                    )
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Статистика по дням</div>
            </div>
            <div class="panel-body">
                <?php
                $this->widget('ext.highcharts.HighchartsWidget', array(
                    'options' => array(
                        'chart' => array(
                            'height' => 250,
                            // 'zoomType'=> 'xy',
                            'defaultSeriesType' => 'areaspline',
                            'type' => 'line',
                            'plotBackgroundColor' => null,
                            'plotBorderWidth' => null,
                            'plotShadow' => false,
                            'backgroundColor' => 'rgba(255, 255, 255, 0)'
                        ),
                        'exporting' => false,
                        'credits' => array(
                            'enabled' => false
                        ),
                        'title' => array('text' => null),
                        'subtitle' => array(
                            'text' => "Monitoring: " . $m_date[count($m_date) - 1] . " - " . $m_date[0],
                        ),
                        'xAxis' => array(
                            'categories' => $weekResult['cats']
                        ),
                        'yAxis' => array(
                            'title' => array('text' => null),
                            'visible' => false
                        ),
                        'plotOptions' => array(
                            'line' => array(
                                'dataLabels' => array(
                                    'enabled' => true
                                ),
                                'enableMouseTracking' => false
                            )
                        ),
                        'series' => array(
                            array('type' => 'line', 'name' => Yii::t('StatsModule.default', 'HITS'), 'data' => $weekResult['hits']),
                            array('type' => 'line', 'name' => Yii::t('StatsModule.default', 'HOSTS'), 'data' => $weekResult['hosts']),
                        )
                    )
                ));
                ?>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td><b>Всего хостов:</b></td>
                        <td class="text-center"><span class="badge"><?= $all_uniqs; ?></span></td>
                    </tr>
                    <tr>
                        <td><b>Всего хитов:</b></td>
                        <td class="text-center"><span class="badge"><?= $total_hits; ?></span></td>
                    </tr>
                    <tr>
                        <td><b>Всего с поиска:</b></td>
                        <td class="text-center"><span class="badge"><?= $total_search; ?></span></td>
                    </tr>
                    <tr>
                        <td><b>Всего с др. сайтов:</b></td>
                        <td class="text-center"><span class="badge"><?= $total_other; ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= Html::link('Последние 100 запроса', array('/admin/stats/default/last', 'query' => 100)) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?= Html::link('Последние 100 других сайта', array('/admin/stats/default/last', 'site' => 100)) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
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
                    'htmlOptions' => array('width' => '15%')
                ),
                array(
                    'name' => 'graphic',
                    'header' => Yii::t('StatsModule.default', 'GRAPH'),
                    'type' => 'raw',
                ),
                array(
                    'name' => 'hosts',
                    'header' => Yii::t('StatsModule.default', 'HOSTS'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
                ),
                array(
                    'name' => 'hits',
                    'header' => Yii::t('StatsModule.default', 'HITS'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
                ),
                array(
                    'name' => 'search',
                    'header' => 'С поиска',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
                ),
                array(
                    'name' => 'sites',
                    'header' => 'С сайтов',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
                ),
                array(
                    'name' => 'fix',
                    'header' => 'fix',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center', 'width' => '10%')
                ),
            )
        ));

        Yii::app()->tpl->closeWidget();
        ?>
    </div>
</div>


