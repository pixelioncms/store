<p><?= Yii::t('SubscribeWidget.default', 'WGT_TEXT') ?></p>
<?php
if (Yii::app()->user->hasFlash('success')) {
    Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
} else {

    $form = $this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => true,
        'id' => 'delivery-form',
        'action' => Yii::app()->createUrl('/delivery/subscribe.action'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false,
        ),
        'htmlOptions' => array(
            'name' => 'delivery-form',
            'onsubmit' => "return false;",
            'onkeypress' => 'if(event.keyCode==13){send("#delivery-form", "#side-subscribe")}'
        )
            ));

    if ($model->hasErrors())
        Yii::app()->tpl->alert('danger', $form->error($model, 'email'));
    ?>




    <div class="form-group">
        <?php echo $form->label($model, 'email', array('class' => 'sr-only')); ?>
        <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email'))); ?>
    </div>
    <a href="javascript:void(0)" class="btn btn-default" onclick="send('#delivery-form','#side-subscribe')"><?= Yii::t('SubscribeWidget.default', 'BUTTON') ?></a>


    <?php $this->endWidget(); ?>

<?php } ?>