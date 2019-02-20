<div class="delivery-subscribe gradient">
    <div class="container">
        <div class="" id="ajax-subscribe">
            <?php
            if (Yii::app()->user->hasFlash('success')) {
                Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
            } else {

                $form = $this->beginWidget('ActiveForm', array(
                    'enableAjaxValidation' => true,
                    //  'enableClientValidation' => true,
                    'id' => 'subscribe-form',
                    'action' => Yii::app()->createUrl('/delivery/subscribe.action'),
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'validateOnChange' => true,
                       // 'errorCssClass' => 'is-invalid', //bootstrap 4
                       // 'successCssClass' => 'is-valid', //bootstrap 4
                    ),
                    'htmlOptions' => array(
                        'class' => 'row',
                        'name' => 'subscribe-form',
                        'onsubmit' => "return false;",
                        'onkeypress' => 'if(event.keyCode==13){subscribeSubmit("#subscribe-form", "#ajax-subscribe")}'
                    )
                ));

                // if ($model->hasErrors())
                //   Yii::app()->tpl->alert('danger', $form->error($model, 'email'));
                ?>

                <div class="col-sm-12 col-md-12 col-lg-4 text-center text-lg-left">
                    <div class="text">
                        <strong><?= Yii::t('SubscribeWidget.default', 'WGT_NAME') ?></strong>
                        <div><?= Yii::t('SubscribeWidget.default', 'WGT_TEXT') ?></div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <?= $form->labelEx($model, 'name', array('class' => 'sr-only')); ?>
                    <?= $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('name'))); ?>
                    <?= $form->error($model, 'name'); ?>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <?= $form->labelEx($model, 'email', array('class' => 'sr-only')); ?>
                    <?= $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email'))); ?>
                    <?= $form->error($model, 'email'); ?>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 text-center text-lg-right">

                    <a href="javascript:void(0)" class="btn btn-secondary"
                       onclick="subscribeSubmit('#subscribe-form','#ajax-subscribe')"><?= Yii::t('SubscribeWidget.default', 'BUTTON') ?></a>

                </div>


                <?php $this->endWidget(); ?>

            <?php } ?>
        </div>
    </div>
</div>



