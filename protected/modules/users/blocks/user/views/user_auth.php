
<div class="text-center">
    <img src="<?php echo Yii::app()->user->getAvatarUrl('50x50'); ?>" alt="<?php echo Yii::t('app', 'CHECKUSER', 0) ?>">
    <br/>
    <?php
    echo Yii::t('default', 'HELLO', array(
        '{username}' => 'TEST')
    )
    ?>
</div>
<br/>

<?php
echo Html::form('', 'post', array('id' => 'userblock-login-form', 'class' => 'form-vertical'));

if ($model->hasErrors())
    Yii::app()->tpl->alert('danger', Html::errorSummary($model));
?>
<div class="form-group">
    <?=
    Html::activeTextField($model, 'login', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('login')
    ));
    ?>
</div>

<div class="form-group">
    <?=
    Html::activePasswordField($model, 'password', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('password')
    ));
    ?>
</div>

<div class="form-group">
    <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'control-label')); ?>
    <?= Html::activeLabel($model, 'rememberMe'); ?>
</div>

<div class="form-group text-center">
<?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_LOGIN'), array('class' => 'btn btn-success')); ?>
</div>


<ul class="list-unstyled">
    <li><?= Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), '/users/remind'); ?></li>
    <li><?= Html::link(Yii::t('UsersModule.default', 'REGISTRATION'), '/users/register'); ?></li>
</ul>

<?php echo Html::endForm(); ?>