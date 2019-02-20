<?php
$typeMonth = 2;
$monthArray = array(
    1 => Yii::t('month', 'January', $typeMonth),
    2 => Yii::t('month', 'February', $typeMonth),
    3 => Yii::t('month', 'March', $typeMonth),
    4 => Yii::t('month', 'April', $typeMonth),
    5 => Yii::t('month', 'May', $typeMonth),
    6 => Yii::t('month', 'June', $typeMonth),
    7 => Yii::t('month', 'July', $typeMonth),
    8 => Yii::t('month', 'August', $typeMonth),
    9 => Yii::t('month', 'September', $typeMonth),
    10 => Yii::t('month', 'October', $typeMonth),
    11 => Yii::t('month', 'November', $typeMonth),
    12 => Yii::t('month', 'December', $typeMonth)
);

$cnt = array_sum(array(5, 8));

echo number_format((5 * 100) / $cnt, 2, '.', ',');
echo '<br>';
echo number_format((8 * 100) / $cnt, 2, '.', ',');

?>


<?php

Yii::app()->tpl->openWidget(array(
    'title' => 'Количество заказов по дням',
    'buttons' => array(
        array()
    )
));


echo Html::form(array('/admin/cart/statistics'), 'GET', array('class' => '')); ?>
<div class="row">
    <div class="col-md-4 offset-md-4 col-lg-2 offset-lg-5">
        <?= Html::dropDownList('year', $year, $this->getAvailableYears(), array('class' => 'form-control', 'onchange' => 'this.form.submit()')) ?>
    </div>
</div>

<li id="parentDropdown" class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="/some-link" data-target="#parentDropdown" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Dropdown
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">Something else here</a>
    </div>
</li>
<?php
echo Html::endForm();

$this->widget('ext.highcharts.HighchartsWidget', array(
    'scripts' => array(
        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
        'modules/exporting', // adds Exporting button/menu to chart
        'modules/drilldown', // adds Exporting button/menu to chart
    ),
    'options' => array(
        'chart' => array(
            'type' => 'column',
            'plotBackgroundColor' => null,
            'plotBorderWidth' => null,
            'plotShadow' => false,
            'backgroundColor' => 'rgba(255, 255, 255, 0)'
        ),
        'title' => array('text' => $this->pageName),
        'xAxis' => array(
            'type' => 'category'
            //'categories' => range(1, cal_days_in_month(CAL_GREGORIAN, $month, $year))
            //  'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        ),
        'yAxis' => array(
            'title' => array('text' => 'Сумма')
        ),

        'legend' => array(
            'enabled' => false
        ),
        'plotOptions' => array(
            'areaspline' => array(
                'fillOpacity' => 0.5
            ),
            'area' => array(
                'pointStart' => 1940,
                'marker' => array(
                    'enabled' => false,
                    'symbol' => 'circle',
                    'radius' => 2,
                    'states' => array(
                        'hover' => array(
                            'enabled' => true
                        )
                    )
                )
            ),
            'series' => array(
                'borderWidth' => 0,
                'dataLabels' => array(
                    'enabled' => true,
                    'format' => '{point.y:.1f}%'
                )
            )
        ),
        // 'tooltip' => array(
        //     'shared' => true,
        //     'valueSuffix' => ' кол.'
        // ),
        'tooltip' => array(
            'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
            'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        ),
        'series' => array(
            // array('name' => 'Сумма заказов', 'data' => $data_total),
            //array('name' => 'Заказы', 'data' => $data),
            array(
                'name' => 'Заказов',
                // 'colorByPoint'=> true,
                'tooltip' => array(
                    'pointFormat' => '<span style="font-weight: bold; color: {series.color}">{series.name}</span>: {point.value}<br/><b>Продано товаров: {point.products}<br/>{point.total_price}</b>' // {point.y:.1f}
                ),
                'data' => $highchartsData
            ),

            /* array(
                 'name' => 'Дохол',
                 'tooltip' => array(
                     'pointFormat' => '<span style="font-weight: bold; color: {series.color}">{series.name}</span>: {point.value}<br/><b>Продано товаров: {point.products}<br/>{point.y:.1f} mm</b>'
                 ),
                 'data' => $highchartsData
             ),*/
        ),

        "drilldown" => array(
            'activeDataLabelStyle' => array(
                'color' => '#ea5510',//'#343a40',
                'cursor' => 'pointer',
                'fontWeight' => 'bold',
                'textDecoration' => 'none',
            ),
            "series" => $highchartsDrill
        )
        /*"drilldown" => array(
            "series" => array(
                array(
                    "name" => "Chrome",
                    "id" => "Month_2",
                    "data" => array(
                        [
                            "v65.0",
                            0.1
                        ],
                        [
                            "v64.0",
                            1.3
                        ],
                    )
                ),
            )
        )*/
    )
));
Yii::app()->tpl->closeWidget();
?>


