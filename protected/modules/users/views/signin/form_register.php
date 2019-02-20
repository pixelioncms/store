        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'register-form',
            'action' => array('/users/signin'),
            'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnType' => true,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success',
            ),
            'htmlOptions' => array('class' => 'form-vertical')
        ));
        ?>

        <?php
        echo $form->errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>
        <div class="form-group">
            <?= $form->labelEx($model, 'login', array('class' => 'control-label')); ?>
            <?= $form->textField($model, 'login', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'login'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'username', array('class' => 'control-label')); ?>
            <?= $form->textField($model, 'username', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'username'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
            <?= $form->passwordField($model, 'password', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'password'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($model, 'confirm_password', array('class' => 'control-label')); ?>
            <?= $form->passwordField($model, 'confirm_password', array('class' => 'form-control')); ?>
            <?= $form->error($model, 'confirm_password'); ?>
        </div>
        <?php if (CCaptcha::checkRequirements() && Yii::app()->settings->get('users', 'enable_register_captcha')) { ?>
            <div class="form-group">
                <?= $form->labelEx($model, 'verifyCode', array('class' => 'control-label')); ?>
                <?php
                $this->widget('CCaptcha', array(
                    'clickableImage' => false,
                    'showRefreshButton' => true,
                    'buttonLabel' => 'Обновить',
                    'buttonOptions' => array(
                        'class' => 'refresh_captcha icon-loop-2'
                    )
                ));
                ?>
                <?= $form->textField($model, 'verifyCode', array('style' => 'width:150px', 'class' => 'form-control', 'placeholder' => $user->getAttributeLabel('verifyCode'))); ?>
                <?= $form->error($model, 'verifyCode'); ?>
            </div>
        <?php } ?>
        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('class' => 'btn btn-primary')); ?>
        </div>
        <?php $this->endWidget(); ?>