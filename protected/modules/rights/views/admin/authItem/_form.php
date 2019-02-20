


<?php
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('class' => '')
        ));
?>

<div class="form-group row">
    <div class="col-sm-4"><?= $form->labelEx($model, 'name', array('class' => 'col-form-label')); ?></div>
    <div class="col-sm-8"><?= $form->textField($model, 'name', array('class' => 'form-control')); ?>
        <?= $form->error($model, 'name'); ?>
        <div class="hint"><?= Rights::t('default', 'Do not change the name unless you know what you are doing.'); ?></div>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-4"><?= $form->labelEx($model, 'description', array('class' => 'col-form-label')); ?></div>
    <div class="col-sm-8"><?= $form->textField($model, 'description', array('class' => 'form-control')); ?>
        <?= $form->error($model, 'description'); ?>
        <div class="hint"><?= Rights::t('default', 'A descriptive name for this item.'); ?></div>

    </div>
</div>


<?php if (Rights::module()->enableBizRule === true) { ?>
    <div class="form-group row">
        <div class="col-sm-4"><?= $form->labelEx($model, 'bizRule', array('class' => 'col-form-label')); ?></div>
        <div class="col-sm-8"><?= $form->textField($model, 'bizRule', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'bizRule'); ?>
            <div class="hint"><?= Rights::t('default', 'Code that will be executed when performing access checking.'); ?></div>

        </div>
    </div>


<?php } ?>

<?php if (Rights::module()->enableBizRule === true && Rights::module()->enableBizRuleData) { ?>
    <div class="form-group row">
        <div class="col-sm-4"><?= $form->labelEx($model, 'data', array('class' => 'col-form-label')); ?></div>
        <div class="col-sm-8"><?= $form->textField($model, 'data', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'data'); ?>
            <div class="hint"><?= Rights::t('default', 'Additional data available when executing the business rule.'); ?></div>
        </div>
    </div>


<?php } ?>
<div class="form-group text-center">
    <div class="btn-group">
        <?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
        <?= Html::link(Yii::t('app', 'CANCEL'), Yii::app()->user->rightsReturnUrl, array('class' => 'btn btn-outline-secondary')); ?>
    </div>
</div>


<?php $this->endWidget(); ?>

