<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title"><?= Yii::t('CartModule.default', 'USER_DATA'); ?></div>
    </div>
    <div class="panel-body">
        <?php //echo Html::errorSummary($this->form);      ?>

        <div class="form-group">
            <?= Html::activeLabel($form, 'user_name', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
            <div class="col-sm-8">
                <?= Html::activeTextField($form, 'user_name', array('class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($form, 'user_email', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
            <div class="col-sm-8">
                <?= Html::activeTextField($form, 'user_email', array('class' => 'form-control')); ?>
                <?= Html::error($form, 'user_email'); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($form, 'user_phone', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
            <div class="col-sm-8">
               <?php $this->widget('ext.inputmask.InputMask', array('model' => $form, 'attribute' => 'user_phone')); ?>
                <?= Html::error($form, 'user_phone'); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($form, 'user_address', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
            <div class="col-sm-8">
                <?= Html::activeTextField($form, 'user_address', array('class' => 'form-control')); ?>
                <?= Html::error($form, 'user_address'); ?>
            </div>
        </div>
        <?php if (Yii::app()->user->isGuest) { ?>
            <div class="form-group">
                <?= Html::activeLabel($form, 'registerGuest', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
                <div class="col-sm-8">
                    <?= Html::activeCheckBox($form, 'registerGuest', array('class' => 'form-control')); ?>
                </div>
            </div>
        <?php } ?>
        <div class="hint">Поля отмеченные <span class="required">*</span> обязательны для заполнения</div>
    </div>
</div>
