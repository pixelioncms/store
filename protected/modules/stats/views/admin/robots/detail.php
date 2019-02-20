<?php
$this->timefilter();
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
?>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Время</th>
                <th>Referer</th>
                <th>IP-адрес</th>
                <th>Хост</th>
                <th width="30%">User Agent</th>
                <th>Страница</th>
            </tr>
        </thead>
        <?php
        foreach ($items as $val => $item) {

            ?>
            <tr>
                <td><?= StatsHelper::$DAY[$item['day']] . $item['dt'] ?></td>
                <td><?= $item['tm'] ?></td>
                <td><?= StatsHelper::Ref($item['refer']) ?></td>
                <td width="20%"><?= CMS::ip($item['ip']) ?></td>
                <td><?= StatsHelper::getRowHost($item['ip'], $item['proxy'], $item['host'], $item['lang']); ?></td>
                <td><?= $item['user'] ?></td>
                <td><?= $item['req'] ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<?php
Yii::app()->tpl->closeWidget();
?>
