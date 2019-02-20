<style type="text/css">
    div.userData input[type=text] {
        width: 385px;
    }
    div.userData textarea {
        width: 385px;
    }
    #orderedProducts {
        padding: 0 0 5px 0;
    }
    .ui-dialog .ui-dialog-content {
        padding: 0;
    }
    #dialog-modal .grid-view {
        padding: 0;
    }
    #orderSummaryTable tr td {
        padding: 3px;
    }
</style>

<div class="car2d">
    <?php
    if ($model->isNewRecord)
        $action = 'create';
    else
        $action = 'update';
    echo Html::form($this->createUrl($action, array('id' => $model->id)), 'post', array('class' => '', 'id' => 'orderUpdateForm'));

    if ($model->hasErrors())
        echo Html::errorSummary($model);
    ?>



    <div class="form-group row">
        <?php if (!$model->is_deleted) { ?>
            <div class="col-sm-4"><?= Html::activeLabel($model, 'status_id', array('class' => 'control-label')); ?></div>
            <div class="col-sm-8"><?= Html::activeDropDownList($model, 'status_id', Html::listData($statuses, 'id', 'name'), array('class' => 'form-control')); ?>
                <?php if (Yii::app()->settings->get('cart', 'notify_change_status_order')) { ?>
                    <small class="text-warning"><i class="icon-warning text-danger"></i> <?= Yii::t('CartModule.admin', 'STATUS_CHANGE_NOTIFY') ?>
                        <?php
                        if (Yii::app()->user->openAccess(array('Cart.Settings.*', 'Cart.Settings.Index'))) {
                            echo Html::link(Yii::t('app', 'SETTINGS'), array('/admin/cart/settings'));
                        }
                        ?>
                    </small>

                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="col-sm-4"><?= Html::activeLabel($model, 'status_id', array('class' => 'control-label')); ?></div>
            <div class="col-sm-8"><span class="badge" style="background-color:<?= $model->status->color; ?>"><?= $model->status->name; ?></div>

        <?php } ?>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'delivery_id', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeDropDownList($model, 'delivery_id', Html::listData($deliveryMethods, 'id', 'name'), array('empty' => Yii::t('app', 'EMPTY_LIST'), 'class' => 'form-control', 'onChange' => 'recountOrderTotalPrice(this)')); ?>
            <?php } else { ?>
                <?= $model->delivery_name; ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'payment_id', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeDropDownList($model, 'payment_id', Html::listData($paymentMethods, 'id', 'name'), array('empty' => Yii::t('app', 'EMPTY_LIST'), 'class' => 'form-control')); ?>

            <?php } else { ?>
                <?= $model->payment_name; ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'paid', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeCheckBox($model, 'paid'); ?>
            <?php } else { ?>
                <?= ($model->paid)?Yii::t('app','YES'):Yii::t('app','NO'); ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'discount', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeTextField($model, 'discount', array('class' => 'form-control')); ?>
            <?php } else { ?>
                <?= $model->discount; ?>
            <?php } ?>
            <div class="hint"><?php echo Yii::t('CartModule.admin', 'Применить скидку для общей суммы заказа'); ?></div></div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'user_name', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeTextField($model, 'user_name', array('class' => 'form-control')); ?>
            <?php } else { ?>
                <?= $model->user_name; ?>
            <?php } ?>
            <?php if ($model->user_id): ?>
                <div class="hint">
                    <?php
                    echo Html::link(Yii::t('CartModule.admin', 'EDIT_USER'), array(
                        '/admin/users/default/update',
                        'id' => $model->user_id,
                    ));
                    ?>
                </div>
            <?php endif; ?></div>

    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'user_email', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeTextField($model, 'user_email', array('class' => 'form-control')); ?>
            <?php } else { ?>
                <?= $model->user_email; ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'user_phone', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!false) { //$model->status_id == ?>
                <?php $this->widget('ext.inputmask.InputMask', array('model' => $model, 'attribute' => 'user_phone')); ?>
            <?php } else { ?>
                <?= $model->user_phone; ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'user_address', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeTextField($model, 'user_address', array('class' => 'form-control')); ?>
            <?php } else { ?>
                <?= $model->user_address; ?>
            <?php } ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'user_comment', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeTextArea($model, 'user_comment', array('class' => 'form-control')); ?>
            <?php } else { ?>
                <?= $model->user_comment; ?>
            <?php } ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'admin_comment', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php if (!$model->is_deleted) { ?>
                <?= Html::activeTextArea($model, 'admin_comment', array('class' => 'form-control')); ?>
            <?php } else { ?>
                <?= $model->admin_comment; ?>
            <?php } ?>
            <div class="hint"><?php echo Yii::t('CartModule.admin', 'Этот текст не виден для пользователя.'); ?></div></div>
    </div>
    <div class="form-group text-center">
        <?= Html::submitButton(($model->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
    </div>
    <?= Html::endForm(); ?>
</div>