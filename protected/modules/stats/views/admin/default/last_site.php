<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,

));
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Последние <?= $n ?> других сайта</th>
            <th>Время / Страница</th>
        </tr>
    </thead>
    <?php
    foreach ($items as $ref) {

        echo "<tr>";
        echo "<td title=" . StatsHelper::$MONTH[substr($ref['dt'], 4, 2)] . ">" . StatsHelper::$DAY[$ref['day']] . $ref['dt'] . "</td>";
        echo "<td class='textL'><a target=_blank href=\"" . $ref['refer'] . "\">";
        if (stristr(urldecode($ref['refer']), "xn--")) {
            $IDN = new idna_convert(array('idn_version' => 2008));
            echo $IDN->decode(urldecode($ref['refer']));
        } else
            echo urldecode($ref['refer']);
        echo "</a></td>";
        echo "<td class='textL'>" . $ref['tm'] . " <a target=_blank href=" . $ref['req'] . ">" . $ref['req'] . "</a></td></tr>";
    }
    ?>

</table>
<?php Yii::app()->tpl->closeWidget(); ?>

