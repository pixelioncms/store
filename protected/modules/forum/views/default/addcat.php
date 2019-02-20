

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'register-form',
    'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnType' => true,
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => 'addcat')
        ));
?>

<?php
echo $form->errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
?>
<div class="form-group">
    <?= $form->labelEx($model, 'name', array('class' => 'info-title')); ?>
    <?= $form->textField($model, 'name', array('class' => 'form-control')); ?>
    <?= $form->error($model, 'name'); ?>
</div>
<div class="form-group">
    <?= $form->labelEx($model, 'hint', array('class' => 'info-title')); ?>
    <?= $form->textField($model, 'hint', array('class' => 'form-control')); ?>
    <?= $form->error($model, 'hint'); ?>
</div>
<div class="form-group text-center">
    <?= Html::submitButton(Yii::t('default', 'BTN_REGISTER'), array('class' => 'btn btn-primary')); ?>
</div>
<?php $this->endWidget(); ?>