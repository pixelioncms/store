<?php
$contact = Yii::app()->settings->get('contacts');
?>
<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12">
            <?php
            $this->widget('mod.contacts.widgets.map.MapStaticWidget', array('pk' => 1));
            ?>
        </div>
        <div class="col-sm-6">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'contact_form',
                'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'validateOnChange' => true,
                    'errorCssClass' => 'has-error',
                    'successCssClass' => 'has-success',
                ),
                'htmlOptions' => array('name' => 'contact_form', 'class' => 'contact_form')
            ));

            if ($model->hasErrors())
                Yii::app()->tpl->alert('danger', Html::errorSummary($model));

            if (Yii::app()->user->hasFlash('success')) {
                Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
            }
            ?>


                <div class="col">
                    <h4><?= Yii::t('ContactsModule.default', 'FB_FORM_NAME') ?></h4>
                </div>

                <div class="form-group row">
                    <?= $form->labelEx($model, 'name', array('class' => 'col-form-label col-sm-4')); ?>
                    <div class="col-sm-4">
                        <?= $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('name'))); ?>
                        <?= $form->error($model, 'name'); ?>
                    </div>
                </div>


                <div class="form-group row">
                    <?= $form->labelEx($model, 'phone', array('class' => 'col-form-label col-sm-4')); ?>
                    <div class="col-sm-4">
                        <?php $this->widget('ext.inputmask.InputMask', array('model' => $model, 'attribute' => 'phone')); ?>
                        <?= $form->error($model, 'phone'); ?>
                    </div>
                </div>


                <div class="form-group row">
                    <?= $form->labelEx($model, 'email', array('class' => 'col-form-label col-sm-4')); ?>
                    <div class="col-sm-4">
                        <?= $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email'))); ?>
                        <?= $form->error($model, 'email'); ?>
                    </div>
                </div>


                <div class="form-group row">
                    <?= $form->labelEx($model, 'msg', array('class' => 'info-title')); ?>
                    <?= $form->textArea($model, 'msg', array('class' => 'form-control unicase-form-control', 'rows' => '5', 'placeholder' => $model->getAttributeLabel('msg'))); ?>
                    <?= $form->error($model, 'msg'); ?>
                </div>

                <div class="form-group row">
                    <?php if (Yii::app()->settings->get('contacts', 'enable_captcha') && false) { ?>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <?= $form->labelEx($model, 'verifyCode', array('class' => '')) ?>
                            </div>
                            <div class="col-sm-4">
                                <?php
                                $this->widget('CCaptcha', array(
                                    'imageOptions' => array('class' => 'captcha'),
                                    'clickableImage' => true,
                                    'showRefreshButton' => false,
                                ))
                                ?>

                            </div>
                            <div class="col-sm-5">
                                <?= $form->textField($model, 'verifyCode', array('class' => 'form-control unicase-form-control')) ?>
                                <?= $form->error($model, 'verifyCode', array(), false, false) ?>
                            </div>
                        </div>
                    <?php } ?>            </div>
                <div class="col-md-6 text-right">
                    <?php
                    $this->widget('ext.recaptcha.ReCaptcha', array(
                        'model'     => $model,
                        'attribute' => 'verifyCode',
                        //'isSecureToken' => true, //для нескольких доменов
                    ));
                    ?>

                    <?= Html::submitButton(Yii::t('app', 'SEND_MSG'), array('class' => 'btn-upper btn btn-primary checkout-page-button')); ?>
                </div>

            <?php $this->endWidget(); ?>
        </div>
        <div class="col-md-4">
            <div class="contact-title">
                <h4><?= Yii::t('ContactsModule.default', 'CONTACT_INFO') ?></h4>
            </div>

            <?php if ($contact->phone) { ?>
                <div class="clearfix phone-no" style="position:relative">
                    <span class="contact-i"><i class="icon-phone"></i></span>
                    <span class="contact-span">
                    <?php
                    $phoneArrary = explode(',', $contact->phone);
                    foreach ($phoneArrary as $phone) {
                        echo '<div>' . $phone . '</div>';
                    }
                    ?>
                    </span>
                </div>
            <?php } ?>
            <?php if ($contact->skype) { ?>
                <div class="clearfix phone-no">
                    <span class="contact-i"><i class="icon-skype"></i></span>
                    <span class="contact-span"><?= $contact->skype ?></span>
                </div>

            <?php } ?>
            <?php if ($contact->address) { ?>
                <div class="clearfix address">
                    <span class="contact-i"><i class="icon-location"></i></span>
                    <span class="contact-span"><?= $contact->address ?></span>
                </div>
            <?php } ?>
            <div class="clearfix email">
                <span class="contact-i"><i class="icon-envelope"></i></span>
                <span class="contact-span"><a
                            href="mailto:<?= $contact->form_emails ?>"><?= $contact->form_emails ?></a></span>
            </div>


        </div>
    </div>
</div>





