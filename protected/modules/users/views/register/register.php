<div class="row">
    <div class="col-md-6 col-sm-6">
        <h4><?= $this->pageName; ?></h4>
        <p>Заполните пожалуйста поля ниже</p>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'register-form',
    'action'=>array('/users/register'),
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
        echo $form->errorSummary($user, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>
        <div class="form-group">
            <?= $form->labelEx($user, 'login', array('class' => 'control-label')); ?>
            <?= $form->textField($user, 'login', array('class' => 'form-control')); ?>
            <?= $form->error($user, 'login'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'username', array('class' => 'control-label')); ?>
            <?= $form->textField($user, 'username', array('class' => 'form-control')); ?>
            <?= $form->error($user, 'username'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'password', array('class' => 'control-label')); ?>
            <?= $form->passwordField($user, 'password', array('class' => 'form-control')); ?>
            <?= $form->error($user, 'password'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'confirm_password', array('class' => 'control-label')); ?>
            <?= $form->passwordField($user, 'confirm_password', array('class' => 'form-control')); ?>
            <?= $form->error($user, 'confirm_password'); ?>
        </div>
        <?php if (CCaptcha::checkRequirements() && Yii::app()->settings->get('users','enable_register_captcha')) { ?>
            <div class="form-group">
                <?= $form->labelEx($user, 'verifyCode', array('class' => 'control-label')); ?>
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
                <?= $form->textField($user, 'verifyCode', array('style' => 'width:150px', 'class' => 'form-control', 'placeholder' => $user->getAttributeLabel('verifyCode'))); ?>
                <?= $form->error($user, 'verifyCode'); ?>
            </div>
        <?php } ?>
        <div class="form-group text-center">
        <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('class' => 'btn btn-primary')); ?>
         </div>
             <?php $this->endWidget(); ?>


    </div>

    <div class="col-md-6 col-sm-6">
        <h4>Вход</h4>
        <p>Здравствуйте, войдите в свой аккаунт</p>
        <?php
        Yii::import('mod.users.forms.UserLoginForm');
        $this->renderPartial('login', array('model' => new UserLoginForm()));
        ?>

    </div>	
</div>

