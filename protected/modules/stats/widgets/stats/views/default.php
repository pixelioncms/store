<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->widget('ext.highcharts.HighchartsWidget', array(
    'options' => array(
        'credits' => false,

        'chart' => array(
            'type' => 'pie',
            'height'=>200
        ),
        'title' => array('text' => 'Сегодня'),
        'xAxis' => array(
            'categories' => array('Сегодня'),
        ),
        'yAxis' => array(
            'title' => null
        ),
        'series' => array(
            array(
                'name' => '#',
                'type' => 'pie',
                'dataLabels' => false,
                'data' => array(
                    array(
                        'name' => 'Hits',
                        'y' => array_sum($hits)
                    ),
                    array(
                        'name' => 'Hosts',
                        'y' => array_sum($hosts)
                    ),
                    array(
                        'name' => 'С поиска',
                        'y' => array_sum($search)
                    ),
                    array(
                        'name' => 'С др. сайтов',
                        'y' => array_sum($sites)
                    ),
                )
            ),
        )
    )
))
?>


<table cellpadding=0 cellspacing=0>
    <tr title='Только уникальные хосты, которые есть в БД'>
        <td>Всего хостов:</td>
        <td align=right style=padding-left:10px><?= array_sum($hosts); ?></td>
    </tr>
    <tr title='Все хиты: из БД'>
        <td>Всего хитов:</td>
        <td align=right style=padding-left:10px><?= array_sum($hits); ?></td>
    </tr>
    <tr title='Все хиты с поиска: из БД'>
        <td>Всего с поиска:</td>
        <td align=right style=padding-left:10px><?= array_sum($search); ?></td>
    </tr>
    <tr title='Все хиты с других сайтов: из БД'>
        <td>Всего с других сайтов:</td>
        <td align=right style=padding-left:10px><?= array_sum($sites); ?></td>
    </tr>
    <tr>
        <td colspan=2 style=padding-top:4px><a class=e target=_blank href='/admin/stats/default/last/?query=100'>Последние 100 запроса</a>
            <br><a class=e target=_blank href='/admin/stats/default/last/?site=100'>Последние 100 других сайта</a></td>
    </tr>
</table>
