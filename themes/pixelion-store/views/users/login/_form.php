<?php


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-login-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,

    ),
    'htmlOptions' => array(
        'class' => 'form-vertical register-form',

    )
));

/*echo Html::form(array('/users/login'), 'post', array(
    'id' => 'user-login-form',

));*/

//if ($model->hasErrors())
//Yii::app()->tpl->alert('danger', Html::errorSummary($model));
?>

<?php
echo $form->errorSummary($model, '', null, array('class' => 'errorSummary alert alert-danger'));
?>
<div class="form-group">
    <?= $form->labelEx($model, 'login', array('class' => '')); ?>
    <?= $form->textField($model, 'login', array('class' => 'form-control')); ?>
    <?= $form->error($model, 'login'); ?>
</div>
<div class="form-group show-password-group">
    <?= $form->labelEx($model, 'password', array('class' => '')); ?>
    <div class="input-group">
        <?= $form->passwordField($model, 'password', array('class' => 'form-control')); ?>
        <a class="btn-show-password" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('UsersModule.default','HINT_SHOW_PWD'); ?>"
           href="javascript:common.switchInputPass('<?= Html::activeId($model, 'password'); ?>');">
            <i class="icon-eye"></i>
        </a>
    </div>
    <?= $form->error($model, 'password'); ?>
</div>

<div class="form-group row">
    <div class="col-md-6">
        <div class="form-group checkbox">
            <label>
                <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'control-label')); ?>
                <?= Html::activeLabel($model, 'rememberMe'); ?>
            </label>
        </div>
    </div>
    <div class="col-md-6 text-right">
        <ul class="list-unstyled">
            <li><?= Html::link(Yii::t('UsersModule.default', 'REMIND_PASS'), array('/users/remind'), array()); ?></li>
            <li><?= Html::link(Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register'), array()); ?></li>
        </ul>
    </div>
    <?php if (!Yii::app()->request->isAjaxRequest) { ?>
        <div class="text-center col">
            <?= Html::submitButton(Yii::t('common', 'LOG_IN'), array('class' => 'btn btn-primary btn-signin', 'id' => 'btn-signin')); ?>

        </div>
    <?php } ?>
</div>


<?php $this->endWidget(); ?>

