<div class="social-sign-in2 outer-top-xs">
    <?php
    if (Yii::app()->hasComponent('eauth')) {
        Yii::app()->eauth->renderWidget();
    }
    ?>
</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
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
//echo Html::errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
?>
<div class="form-group">
    <?= $form->labelEx($model, 'login', array('class' => 'info-title')); ?>
    <?= $form->textField($model, 'login', array('class' => 'form-control unicase-form-control text-input')); ?>
    <?= $form->error($model, 'login'); ?>
</div>
<div class="form-group">
    <?= $form->labelEx($model, 'password', array('class' => 'info-title')); ?>
    <?= $form->passwordField($model, 'password', array('class' => 'form-control unicase-form-control text-input')); ?>
    <?= $form->error($model, 'password'); ?>
</div>
<div class="form-group checkbox">
    <label>
        <?= Html::activeCheckBox($model, 'rememberMe', array('class' => 'info-title')); ?>
        <?= Yii::t('common', 'REMEMBER_ME') ?>
    </label>

</div>
<div class="form-group">
    <?= Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), '/users/remind', array('class' => '')); ?>
</div>

<div class="form-group text-center">
    <?= Html::submitButton(Yii::t('common', 'LOG_IN'), array('class' => 'btn-upper btn btn-primary checkout-page-button')); ?>
</div>
<?php $this->endWidget(); ?>
<?php
//echo Html::endForm(); ?>