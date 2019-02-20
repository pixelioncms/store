<?php
if ($sended)
    Yii::app()->tpl->alert('success', Yii::t('CallbackWidget.default', 'SUCCESS'));
?>
<p class="text-muted"><?= Yii::t('CallbackWidget.default', 'TEXT'); ?></p>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'callback-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => '',
        'onsubmit' => "return false;", /* Disable normal form submit */
        'onkeypress' => " if(event.keyCode == 13){ callbackSend(); } " /* Do ajax call when user presses enter key */
    ),
));

if ($model->hasErrors())
    Yii::app()->tpl->alert('danger', $form->error($model, 'phone'));


?>


<?= $form->label($model, 'phone', array('class' => 'sr-only')); ?>
<?= $form->textField($model, 'phone', array('class' => 'form-control', 'placeholder' => 'Телефон')); ?>

<div class="text-center">
    <?php //echo Html::link(Yii::t('CallbackWidget.default', 'BUTTON_SEND'),'javascript:void(0)', array('onclick' => 'callbackSend();', 'class' => 'btn btn-danger btn-callback wait')); ?>
</div>


<?php $this->endWidget(); ?>
