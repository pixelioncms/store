
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-change_password-form',
    'htmlOptions' => array('class' => ''))
);

if ($changePasswordForm->hasErrors())
    Yii::app()->tpl->alert('danger', Html::errorSummary($changePasswordForm));
?>


<div class="form-group">
    <?= $form->label($changePasswordForm, 'current_password', array('class' => 'control-label')); ?>
    <?= $form->passwordField($changePasswordForm, 'current_password', array('class' => 'form-control')); ?>

</div>
<div class="form-group">
<?= $form->label($changePasswordForm, 'new_password', array('class' => 'control-label')); ?>
<?= $form->passwordField($changePasswordForm, 'new_password', array('class' => 'form-control')); ?>

</div>

<div class="form-group">
<?= $form->label($changePasswordForm, 'new_repeat_password', array('class' => 'control-label')); ?>
<?= $form->passwordField($changePasswordForm, 'new_repeat_password', array('class' => 'form-control')); ?>

</div>
<div class="text-center">
    <?php echo Html::submitButton(Yii::t('app', '_CHANGE'), array('class' => 'btn btn-success')); ?>
</div>
<?php $this->endWidget(); ?> 
