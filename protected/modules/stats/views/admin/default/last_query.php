<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));


?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Поисковик</th>
            <th>Последние <?= $n ?> запроса</th>
            <th>Время / Страница</th>
        </tr>
    </thead>
    <?php
    foreach ($items as $ref) {

            $refer = StatsHelper::Ref($ref['refer']);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                if ($engine == "G" and !empty($query) and stristr($ref['refer'], "/url?"))
                    $ref['refer'] = str_replace("/url?", "/search?", $ref['refer']);
                
               echo "<tr>";

                echo "<td nowrap title=" . StatsHelper::$MONTH[substr($ref['dt'], 4, 2)] . ">" . StatsHelper::$DAY[$ref['day']] . $ref['dt'] . "</td>";
                echo "<td align=center>";
                echo $this->echo_se($engine);
                if (empty($query))
                    $query = '<span class="text-muted">неизвестно</span>';
                echo "</td><td align=left style='overflow: hidden;text-overflow: ellipsis;'><a target=_blank href=\"" . $ref['refer'] . "\">" . $query . "</a></td>";
                echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;' nowrap>" . $ref['tm'] . " <a target=_blank href=" . $ref['req'] . ">" . $ref['req'] . "</a></td></tr>";
            }
        }
    ?>

</table>
<?php Yii::app()->tpl->closeWidget(); ?>

