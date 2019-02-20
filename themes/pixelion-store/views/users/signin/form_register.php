

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'register-form',
    'action' => array('/users/signin'),
    'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnType' => true,
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => 'register-form outer-top-xs')
        ));
?>

<?php
echo $form->errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
?>
<div class="form-group">
    <?= $form->labelEx($model, 'login', array('class' => 'info-title')); ?>
    <?= $form->textField($model, 'login', array('class' => 'form-control unicase-form-control text-input')); ?>
    <?= $form->error($model, 'login'); ?>
</div>
<div class="form-group">
    <?= $form->labelEx($model, 'username', array('class' => 'info-title')); ?>
    <?= $form->textField($model, 'username', array('class' => 'form-control unicase-form-control text-input')); ?>
    <?= $form->error($model, 'username'); ?>
</div>
<div class="form-group">
    <?= $form->labelEx($model, 'password', array('class' => 'info-title')); ?>
    <?= $form->passwordField($model, 'password', array('class' => 'form-control unicase-form-control text-input')); ?>
    <?= $form->error($model, 'password'); ?>
</div>
<div class="form-group">
    <?= $form->labelEx($model, 'confirm_password', array('class' => 'info-title')); ?>
    <?= $form->passwordField($model, 'confirm_password', array('class' => 'form-control unicase-form-control text-input')); ?>
    <?= $form->error($model, 'confirm_password'); ?>
</div>
<?php if (CCaptcha::checkRequirements() && Yii::app()->settings->get('users', 'enable_register_capcha')) { ?>
    <div class="form-group">
        <?= $form->labelEx($model, 'verifyCode', array('class' => 'info-title')); ?>
        <?php
        $this->widget('CCaptcha', array(
            'clickableImage' => false,
            'showRefreshButton' => true,
            'buttonLabel' => 'Обновить',
            'buttonOptions' => array(
                'class' => 'refresh_captcha icon-loop-2'
            )
        ));
        ?>
        <?= $form->textField($model, 'verifyCode', array('style' => 'width:150px', 'class' => 'form-control', 'placeholder' => $user->getAttributeLabel('verifyCode'))); ?>
        <?= $form->error($model, 'verifyCode'); ?>
    </div>
<?php } ?>
<div class="form-group text-center">
    <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('class' => 'btn-upper btn btn-primary checkout-page-button')); ?>
</div>
<?php $this->endWidget(); ?>