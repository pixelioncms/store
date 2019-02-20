

<div class="dialog-centered"></div>
<div class="dialog-centered-two">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'orderproject-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => '',
            'onsubmit' => "return false;", /* Disable normal form submit */
            'onkeypress' => " if(event.keyCode == 13){ orderprojectSend(); } " /* Do ajax call when user presses enter key */
        ),
    ));

if ($sended)
    Yii::app()->tpl->alert('success', Yii::t('OrderprojectWidget.default', 'SUCCESS'));

    if ($model->hasErrors()) {

        if ($form->error($model, 'phone')){
            Yii::app()->tpl->alert('danger', $form->error($model, 'phone'));
        }elseif ($form->error($model, 'rules')){
            Yii::app()->tpl->alert('danger', $form->error($model, 'rules'));
        }

    }

    ?>

    <div class="form-group form-group-auto2">
        <?= $form->label($model, 'name', array('class' => 'sr-only')); ?>
        <?= $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('name'))); ?>
    </div>
    <div class="form-group form-group-auto2">
        <?= $form->label($model, 'email', array('class' => 'sr-only')); ?>
        <?= $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email'))); ?>
    </div>
    <div class="form-group form-group-auto2">
        <?= $form->label($model, 'phone', array('class' => 'sr-only')); ?>
        <?php //$form->textField($model, 'phone', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('phone'))); ?>
    <?php $this->widget('ext.inputmask.InputMask',array(
            'model'=>$model,
            'attribute'=>'phone',
            'htmlOptions'=>array('placeholder' => $model->getAttributeLabel('phone'))
        )) ?>
    </div>
    <div class="form-group form-group-auto2">
        <?= $form->label($model, 'text', array('class' => 'sr-only')); ?>
        <?= $form->textarea($model, 'text', array('rows' => 5, 'class' => 'form-control', 'placeholder' => $model->getAttributeLabel('text'), 'style' => 'resize:none')); ?>
    </div>
    <?php if (Yii::app()->request->serverName == 'pixelion.moscow') { ?>
        <div class="form-group form-group-auto2">
            <?= $form->checkbox($model, 'rules',  array('uncheckValue' => null)); ?>
            <?= $form->label($model, 'rules', array('class' => 'sr-only')); ?>
            <?= Yii::t('OrderprojectWidget.default','FORM_RULES_LINK_PRE'); ?>
			
			                    <?php
                    Yii::import('mod.pages.models.*');
                    $page = Page::model()->findByPk(3);
                    ?>
					
            <?= Html::link(mb_strtolower(Yii::t('OrderprojectWidget.default','FORM_RULES')),$page->getUrl()); ?>
        </div>
    <?php } ?>
    <div class="form-group text-center">
        <?php echo Html::link(Yii::t('OrderprojectWidget.default', 'BUTTON_SEND'),'javascript:void(0)', array('onclick' => 'orderprojectSend();', 'class' => 'btn btn-default btn-orderproject')); ?>
    </div>


    <?php $this->endWidget(); ?>
</div>

