<?php
$ip = CMS::ip($data->ip_address);
$userName = ($data->user_name) ? $data->user_name : 'Unknown';

$browser = Yii::app()->browser->getName($data->user_agent);
$platform = Yii::app()->browser->getPlatform($data->user_agent);



?>

<div class="card bg-light">
    <div class="card-header">
        <h5 data-toggle="collapse" data-parent="#accordion" href="#collapse-user-<?= $index ?>">
            <?php //echo ($data->user) ? Html::image($data->user->getAvatarUrl('50x50'), $userName, array('class' => 'img-thumbnail2', 'width' => 25)) : ''; ?>
            <?php echo Html::image($data->getAvatarUrl('50x50'), $userName, array('class' => 'img-thumbnail2', 'width' => 25)); ?>
            <?php echo $userName; ?>
            <div class="float-right">
                <?php
                if ($data->user) {
                    echo $data->user->getRolesGrid();
                } else {
                    echo '<span class="badge badge-secondary">' . Yii::t('app', strtoupper($data->user_type)) . '</span>';
                }
                ?>
            </div>
        </h5>
    </div>
    <div id="collapse-user-<?= $index ?>" class="panel-collapse collapse">
        <ul class="list-group">
            <li class="list-group-item">
                <?php if ($data->iconBrowser) { ?>
                    <i class="icon-<?= $data->iconBrowser ?>"></i> <?= $data->browserName; ?>
                <?php } else { ?>
                    <b>Браузер:</b> <?= $data->browserName; ?>
                <?php } ?>
                <span class="badge badge-secondary"><?= $data->browserVersion ?></span>
            </li>
            <li class="list-group-item">
                <?php if ($data->iconPlatform) { ?>
                    <i class="icon-<?= $data->iconPlatform ?>"></i> <?= $data->platformName; ?>
                <?php } else { ?>
                    <b>Платформа:</b> <?= $data->platformName; ?>
                <?php } ?>

            </li>
            <li class="list-group-item">
                <b>IP:</b> <?= $ip; ?>
            </li>
            <li class="list-group-item">
                <b><?= Yii::t('default', 'ONLINE') ?>:</b> <?= $data->onlineTime ?>
            </li>
            <li class="list-group-item">
                <b>Страница:</b> <?= Html::link($data->current_url, $data->current_url, array('target' => '_blank')) ?>
            </li>
        </ul>
    </div>
</div>
