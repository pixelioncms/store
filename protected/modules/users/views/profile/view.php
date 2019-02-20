
<h2><?php echo Yii::t('UsersModule.default', 'PROFILE_NAME', array('{user_name}' => ($user->username) ? $user->username : $user->email)) ?></h2>

<?php if (!$user->banned) { ?>
    <?php

    $label = $user->attributeLabels();
    ?>
    <div class="row">
        <div class="col-md-3 text-center"><img src="<?= $user->getAvatarUrl('100x100'); ?>" alt="<?= $user->username ?>" /></div>
        <div class="col-md-9">
            <table class="table table-striped">
                <?php if ($user->username) { ?>
                    <tr>
                        <td><?= $label['username']; ?></td>
                        <td><?= $user->username; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?= $label['email']; ?></td>
                    <td><?= $user->email; ?></td>
                </tr>
                <tr>
                    <td><?= $label['date_registration']; ?></td>
                    <td><?= CMS::date($user->date_registration); ?></td>
                </tr>
                <tr>
                    <td><?= $label['last_login']; ?></td>
                    <td><?= CMS::date($user->last_login,true); ?></td>
                </tr>
                <tr>
                    <td>Локация:</td>
                    <td>
                        <?= CMS::ip('195.78.247.104', 0); ?>
                        <?= Yii::app()->geoip->get('195.78.247.104')->country ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
} else {
    Yii::app()->tpl->alert('warning', Yii::t('UsersModule.default','ERR_USER_BANNED'));
}
?>

