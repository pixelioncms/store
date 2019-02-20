<?php
echo Html::form(array('/users/login'), 'post', array(
    'id' => 'user-login-form',
    'class' => ''
));

//if ($model->hasErrors())
    //Yii::app()->tpl->alert('danger', Html::errorSummary($model));
?>
<div class="form-group">
    <?=
    Html::activeTextField($model, 'login', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('login')
    ));
    ?>
    <?=
    Html::error($model, 'login');
    ?>
</div>

<div class="form-group" style="margin-bottom: 23px;">
    <?=
    Html::activePasswordField($model, 'password', array(
        'class' => 'form-control',
        'placeholder' => $model->getAttributeLabel('password')
    ));
    ?>
        <?=
    Html::error($model, 'password');
    ?>
</div>
<div class="row">
    <div class="col-md-9">
        <div class="input-group">
            <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'form-control2')); ?>
            <?= Html::activeLabel($model, 'rememberMe'); ?>
        </div>
    </div>
    <div class="col-md-9">
        <ul class="list-unstyled">
            <li><?= Html::link(Yii::t('UsersModule.default', 'REMIND_PASS'), array('/users/remind'),array('class'=>'link-style')); ?></li>
            <li><?= Html::link(Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register'),array('class'=>'link-style')); ?></li>
        </ul>
    </div>
    <?php if(!Yii::app()->request->isAjaxRequest){ ?>
    <div class="text-center col-xs-18" style="margin-top: 30px;">
        <?= Html::submitButton('Войти', array('class' => 'btn btn-danger btn-signin','id'=>'btn-signin')); ?>

    </div>
    <?php } ?>
</div>


<?= Html::endForm(); ?>

