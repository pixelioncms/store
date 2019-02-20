<div class="text-center">
    <img src="<?php echo Yii::app()->user->getAvatarUrl('50x50'); ?>" alt="<?php echo Yii::app()->user->login ?>">
    <br/>
    <?php
    echo Yii::t('default', 'HELLO', array(
        '{username}' => Html::link(Yii::app()->user->login, '/users/profile'))
    )
    ?>
</div>
<br/>
<ul class="list-unstyled">
    <?php if (Yii::app()->user->isSuperuser) { ?>
        <li><?= Html::link(Yii::t('app', 'ADMIN_PANEL'), '/admin'); ?></li>
    <?php } ?>
    <li><?= Html::link(Yii::t('UsersModule.default', 'PROFILE'), '/users/profile'); ?></li>
    <li><?= Html::link(Yii::t('default', 'LOGOUT'), '/users/logout'); ?></li>
</ul>
<ul class="list-unstyled">
    <li>: <?= $online['totals']['guest'] ?></li>
    <li>: <?= $online['totals']['bot'] ?></li>
    <li>: <?= $online['totals']['users'] ?></li>
    <li>: <?= $online['totals']['admin'] ?></li>
    <li><?= Yii::t('app', 'TOTAL') ?>: <?= $online['totals']['all'] ?></li>
</ul>

