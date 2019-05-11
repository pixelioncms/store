<?php

Yii::app()->clientScript->registerScript('auth', "
    $(function () {
        var h = $('.card').height();
        var dh = $(window).height();
        $('#loginbox').css({'margin-top': (dh / 2) - h});
        $(window).resize(function () {
            var h = $('.card').height();
            var dh = $(window).height();
            $('#loginbox').css({'margin-top': (dh / 2) - h});
        });
        $('.auth-logo').hover(function () {
            // $(this).removeClass('zoomInDown').addClass('swing'); 
        }, function () {
            //  $(this).removeClass('swing'); 
        });
        
        
        
    });
", CClientScript::POS_END);
?>
<div class="container">
    <div class="row">
        <div id="login-container" style="margin-top:80px;"
             class="animated <?php if (!Yii::app()->user->hasFlash('error')) { ?>bounceInDown<?php } ?> col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-lg-4 offset-lg-4">

            <div class="text-center auth-logo animated zoomInDown2 ">
                <a href="//pixelion.com.ua" target="_blank">PIXELION</a>
                <div class="auth-logo-hint"><?= Yii::t('app', 'CMS') ?></div>
            </div>
            <div class="card panel-default">
                <div class="card-header">
                    <h5 class="text-center"><?= Yii::t('admin', 'LOGIN_ADMIN_PANEL') ?></h5>
                </div>
                <div class="card-body">


                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'login-form',
                        'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
                        'enableClientValidation' => false,
                        'clientOptions' => array(
                            'validateOnType' => false,
                            'validateOnSubmit' => false,
                            'validateOnChange' => false,
                            'errorCssClass' => 'has-error',
                            'successCssClass' => 'has-success',
                        ),
                        //   'htmlOptions' => array('class' => '')
                    ));

                    if (Yii::app()->user->hasFlash('error')) {
                       Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'), false);
                    }
                    ?>
                    <div class="form-group row">
                        <div class="col">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-user"></i></span>
                                </div>
                                <?= $form->textField($model, 'login', array('placeholder' => Yii::t('app', 'LOGIN'), 'class' => 'form-control')); ?>
                                <?= $form->error($model, 'login'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-key"></i></span>
                                </div>
                                <?= $form->passwordField($model, 'password', array('placeholder' => Yii::t('app', 'PASSWORD'), 'class' => 'form-control')); ?>
                                <div class="input-group-append">
                                    <a href="#" data-target="#UserLoginForm_password" class="input-group-text bg-transparent border-0" onclick="common.switchInputPass(this,'UserLoginForm_password');"><i class="icon-eye"></i></a>
                                </div>
                                <?= $form->error($model, 'password'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <div class="form-check">
                                <?= Html::label(Html::activeCheckBox($model, 'rememberMe', array('class' => 'form-check-input')) . Yii::t('common', 'REMEMBER_ME'), Html::activeId($model, 'rememberMe')) ?>
                            </div>
                        </div>
                        <div class="col-sm-6 controls text-right"><?= Html::submitButton(Yii::t('common', 'LOG_IN'), array('class' => 'btn btn-success')); ?></div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
            <div class="text-center copyright">{copyright}</div>
        </div>
    </div>
</div>


