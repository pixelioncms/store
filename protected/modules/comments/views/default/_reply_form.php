<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-reply-form-' . $model->id,
    'action' => array('/comments/reply_submit'),
    //'enableClientValidation' => false,
    //'enableAjaxValidation' => true, // Включаем аякс отправку
    // 'clientOptions' => array(
    //     'validateOnSubmit' => true,
    //     'validateOnChange' => false,
    //),
    'htmlOptions' => array('class' => 'form', 'name' => 'comment-reply-form')
));
$model->unsetAttributes(array('text'));
?>


<div class="input-group">
    <div class="input-group-append">
            <span class="input-group-text">
        <?php

        echo Html::image(Yii::app()->user->getAvatarUrl('50x50'), '');


        ?>

    </span>
    </div>
    <?php echo $form->textArea($model, 'text', array('rows' => 3, 'class' => 'form-control', 'placeholder' => $model->getAttributeLabel('text'))); ?>
    <?php echo $form->error($model, 'text'); ?>
</div>
<div class="text-right" style="margin-top:10px;">
    <?php
    echo Html::link(Yii::t('default', 'Ответить'), 'javascript:void(0)', array('onClick' => '$("#comment_' . $model->id . '").comment("reply_submit",' . $model->id . '); return false;', 'class' => 'btn btn-success'));
    ?>
</div>

<?php $this->endWidget(); ?>
