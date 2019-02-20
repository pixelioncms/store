<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-12 col-xl-4 offset-xl-1">
        <h4><?= $this->pageName; ?></h4>
        <p>Заполните пожалуйста поля ниже</p>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-register-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,

            ),
            'htmlOptions' => array(
                'class' => 'form-vertical register-form',

            )
        ));
        ?>

        <?php
        echo $form->errorSummary($user, '', null, array('class' => 'errorSummary alert alert-icon alert-danger'));
        ?>
        <div class="form-group">
            <?= $form->labelEx($user, 'login', array('class' => '')); ?>
            <?= $form->textField($user, 'login', array('class' => 'form-control')); ?>
            <?= $form->error($user, 'login'); ?>
        </div>
        <div class="form-group">
            <?= $form->labelEx($user, 'username', array('class' => '')); ?>
            <?= $form->textField($user, 'username', array('class' => 'form-control')); ?>
            <?= $form->error($user, 'username'); ?>
        </div>
        <div class="form-group show-password-group">

            <?= $form->labelEx($user, 'password', array('class' => '')); ?>
            <div class="input-group">
                <?= $form->passwordField($user, 'password', array('class' => 'form-control')); ?>


    <a class="btn-show-password" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('UsersModule.default','HINT_SHOW_PWD'); ?>"
       href="javascript:common.switchInputPass('<?= Html::activeId($user, 'password'); ?>');">
        <i class="icon-eye"></i>
    </a>

            </div>
            <?= $form->error($user, 'password'); ?>
        </div>
        <div class="form-group show-password-group">
            <?= $form->labelEx($user, 'confirm_password', array('class' => '')); ?>
            <div class="input-group">
                <?= $form->passwordField($user, 'confirm_password', array('class' => 'form-control')); ?>
                <a class="btn-show-password" data-toggle="tooltip" data-placement="bottom" title="<?= Yii::t('UsersModule.default','HINT_SHOW_PWD'); ?>"
                   href="javascript:common.switchInputPass('<?= Html::activeId($user, 'confirm_password'); ?>');">
                    <i class="icon-eye"></i>
                </a>
            </div>

            <?= $form->error($user, 'confirm_password'); ?>
        </div>
        <?php if (CCaptcha::checkRequirements() && false) { ?>
            <div class="form-group row">
                <div class="col-sm-4">
                    <?= $form->labelEx($user, 'verifyCode', array('class' => '')); ?>

                    <?php
                    $this->widget('CCaptcha', array(
                        'clickableImage' => false,
                        'showRefreshButton' => true,
                        'buttonLabel' => '',
                        'buttonOptions' => array(
                            'class' => 'refresh_captcha icon-refresh'
                        )
                    ));
                    ?>
                </div>
                <div class="col-sm-8">
                    <?= $form->textField($user, 'verifyCode', array('style' => 'width:150px', 'class' => 'form-control unicase-form-control text-input', 'placeholder' => $user->getAttributeLabel('verifyCode'))); ?>
                </div>
            </div>
        <?php } ?>
        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('class' => 'btn-upper btn btn-primary checkout-page-button')); ?>
        </div>
        <?php $this->endWidget(); ?>


    </div>

    <div class="col-md-6 col-lg-6 col-sm-12 col-xl-4 offset-xl-2">
        <h4>Вход</h4>
        <p>Здравствуйте, войдите в свой аккаунт</p>
        <?php
        Yii::import('mod.users.forms.UserLoginForm');
        $this->renderPartial('current_theme.views.users.login._form', array('model' => new UserLoginForm()));
        ?>

    </div>
</div>

