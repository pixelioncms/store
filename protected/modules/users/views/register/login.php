<?php
/*echo Html::form($this->createUrl('/users/register'), 'post', array(
    'id' => 'user-login-form',
    'class' => 'register-form',
));*/

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'action'=>array('/users/register'),
    'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnType' => true,
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => 'form-vertical')
        ));


?>
<?php
echo Html::errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
?>
<div class="form-group">
    <?= $form->labelEx($model, 'login', array('class' => 'control-label')); ?>
    <?= $form->textField($model, 'login', array('class' => 'form-control')); ?>
    <?= $form->error($model, 'login'); ?>
</div>
<div class="form-group">
    <?= $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
    <?= $form->passwordField($model, 'password', array('class' => 'form-control')); ?>
</div>
<div class="form-group checkbox">
    <label>
        <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'control-label')); ?>
        <?= Yii::t('common', 'REMEMBER_ME') ?>
    </label>

</div>
<div class="form-group">
    <?= Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), '/users/remind', array('class' => '')); ?>
</div>

<div class="form-group text-center">
    <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_LOGIN'), array('class' => 'btn-upper btn btn-primary checkout-page-button')); ?>
</div>
   <?php $this->endWidget(); ?>
<?php //echo Html::endForm(); ?>