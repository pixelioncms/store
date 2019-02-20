<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-edit-form',
    'action' => $currentUrl,
    'enableClientValidation' => true,
    'enableAjaxValidation' => true, // Включаем аякс отправку
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,


    ),

    'htmlOptions' => array('class' => 'form', 'name' => 'test')
));
?>

    <div class="row">
        <?php echo $form->labelEx($model, 'text'); ?>
        <?php echo $form->textArea($model, 'text', array('rows' => 5, 'id' => 'comment','class'=>'form-control')); ?>
        <?php echo $form->error($model, 'text'); ?>
    </div>
<?php $this->endWidget(); ?>