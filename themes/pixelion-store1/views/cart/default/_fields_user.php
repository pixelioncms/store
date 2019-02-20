<div class="card">
    <div class="card-header">
        <h5><?= Yii::t('CartModule.default', 'USER_DATA'); ?></h5>
    </div>
    <div class="card-body">
        <div class="form-group">
            <?= Html::activeLabel($form, 'user_name', array('required' => true, 'class' => 'info-title control-label')); ?>
            <?= Html::activeTextField($form, 'user_name', array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($form, 'user_email', array('required' => true, 'class' => 'info-title control-label')); ?>
            <?= Html::activeTextField($form, 'user_email', array('class' => 'form-control')); ?>
            <?= Html::error($form, 'user_email'); ?>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($form, 'user_phone', array('required' => true, 'class' => 'info-title control-label')); ?>
            <?php $this->widget('ext.inputmask.InputMask', array('model' => $form, 'attribute' => 'user_phone')); ?>
            <?= Html::error($form, 'user_phone'); ?>
        </div>

        <?php if (Yii::app()->user->isGuest) { ?>
            <div class="form-group">
                <?= Html::activeLabel($form, 'registerGuest', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
                <?= Html::activeCheckBox($form, 'registerGuest', array('class' => 'form-control')); ?>

            </div>        <?php } ?>
        <div class="text-muted">Поля отмеченные <span class="required">*</span> обязательны для заполнения</div>

    </div>
</div>
