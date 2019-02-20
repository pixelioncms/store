<?php
$ip = Yii::app()->request->getUserHostAddress();
$geoip = Yii::app()->geoip->get($ip);

?>

<table class="table table-striped">
    <tr>
        <td width="50%"><i class="icon-user"></i> <?= Yii::app()->user->getName() . ' ' . CMS::ip($ip); ?></td>
        <td width="50%"><i class="icon-<?= $browserIcon ?>"></i>  <?= $browser->getBrowser() ?> <span class="float-right badge badge-secondary"><?= $browser->getVersion() ?></span></td>
    </tr>
    <tr>
        <td><i class="icon-ip4"></i> <?= $ip . ' ' . $geoip->country . '(' . $geoip->countryCode . ')'; ?></td>
        <td><i class="icon-<?= $platformIcon ?>"></i> <?= $browser->getPlatform() ?></td>
    </tr>
    <tr>
        <td><?=Yii::t('UsersModule.User','TIMEZONE')?> <span class="float-right badge badge-secondary"><?= CMS::timezone() ?></span></td>
        <td><?=Yii::t('UsersModule.User','LAST_LOGIN')?> <span class="float-right badge badge-secondary"><?= Yii::app()->user->last_login ?></span></td>
    </tr>

</table>
