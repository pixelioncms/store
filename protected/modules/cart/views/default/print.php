<?php
$config = Yii::app()->settings->get('shop');

?>

<h1><?= $this->pageName; ?></h1>
<table width="100%" border="0" class="table table-striped table-condensed">
    <tr>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_IMG') ?></th>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_NAME') ?></th>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_NUM') ?></th>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_SUM') ?></th>
    </tr>
    <?php foreach ($model->getOrderedProducts()->getData() as $product) { //$model->getOrderedProducts()->getData()  ?> 
        <tr>
            <td align="center">
                <?php

                echo Html::link(Html::image($product->prd->getMainImageUrl('100x100'), $product->prd->mainImageTitle), array('product/view', 'seo_alias' => $product->prd->seo_alias), array('class' => 'thumbnail'));
                ?>
            </td>
            <td>
                <?= Html::openTag('h3') ?>
                <?= $product->getRenderFullName(false); ?>
                <?= Html::closeTag('h3') ?>
                <?= Html::openTag('span', array('class' => 'price')) ?>
                <?= Yii::app()->currency->number_format(Yii::app()->currency->convert($product->price)) ?>
                <?= Yii::app()->currency->active->symbol; ?>
                <?= Html::closeTag('span') ?> 
            </td>
            <td align="center">
                <?= $product->quantity ?>
            </td>
            <td align="center">
                <?php
                    echo Yii::app()->currency->number_format(Yii::app()->currency->convert($product->price * $product->quantity));
                ?>
                <?= Yii::app()->currency->active->symbol; ?>
            </td>
        </tr>
    <?php } ?>
</table>



<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading"><h4><?= Yii::t('CartModule.default', 'USER_DATA') ?></h4></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6"><?= $model->getAttributeLabel('user_name') ?></div>
                    <div class="col-md-6 text-right"><?= Html::encode($model->user_name); ?></div>
                    <div class="col-md-6"><?= $model->getAttributeLabel('user_email') ?></div>
                    <div class="col-md-6 text-right"><?= Html::encode($model->user_email); ?></div>
                    <div class="col-md-6"><?= $model->getAttributeLabel('user_phone') ?></div>
                    <div class="col-md-6 text-right"><?= Html::encode($model->user_phone); ?></div>
                    <div class="col-md-6"><?= $model->getAttributeLabel('user_address') ?></div>
                    <div class="col-md-6 text-right"><?= Html::encode($model->user_address); ?></div>
                    <?php if ($model->delivery_price > 0) { ?>
                        <div class="col-md-6"><?= Yii::t('CartModule.default', 'COST_DELIVERY') ?></div>
                        <div class="col-md-6 text-right">
                            <?= Yii::app()->currency->number_format(Yii::app()->currency->convert($model->delivery_price)) ?>
                            <?= Yii::app()->currency->active->symbol ?>
                        </div>
                    <?php } ?>
                    <div class="col-md-6"><?= Yii::t('CartModule.default', 'DELIVERY') ?></div>
                    <div class="col-md-6 text-right"><?= Html::encode($model->delivery_name); ?></div>
                    <?php if (!empty($model->user_comment)) { ?>
                        <div class="col-md-6"><?= $model->getAttributeLabel('user_comment') ?></div>
                        <div class="col-md-6 text-right"><?= Html::encode($model->user_comment); ?></div>
                    <?php } ?>
                </div>


            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading"><h4><?= Yii::t('CartModule.default', 'PAYMENT_METHODS') ?></h4></div>
            <div class="panel-body">
                <?php foreach ($model->deliveryMethod->paymentMethods as $payment) { ?>
                    <?php
                    $activePay = ($payment->id == $model->payment_id) ? '<span class="icon-checkmark " style="font-size:20px;color:green"></span>' : '';
                    ?>
                    <h3><?= $activePay; ?> <?= $payment->name ?></h3>
                    <p><?= $payment->description ?></p>
                    <p><?= $payment->renderPaymentForm($model) ?></p>
                <?php } ?>

                <?= Yii::t('CartModule.default', 'TOTAL_PAY') ?>:<?=$model->full_price?>
                <span class="badge badge-success"><?= Yii::app()->currency->number_format($model->full_price) ?></span>
                <?= Yii::app()->currency->active->symbol ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?= Yii::t('CartModule.default', 'Состояние заказа') ?><span class="badge fr badge-secondary" style=""><?= $model->status_name ?></span></h4><div class="clear"></div></div>
            <div class="panel-body">
                <?php if ($model->paid) { ?>
                <?= Yii::t('CartModule.Order','PAID')?>: <span class="badge badge-success"><?= Yii::t('app','YES')?></span>
                <?php }else{ ?>
                <?= Yii::t('CartModule.Order','PAID')?>: <span class="badge badge-secondary"><?= Yii::t('app','NO')?></span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
