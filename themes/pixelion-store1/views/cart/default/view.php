<?php
$config = Yii::app()->settings->get('shop');
if (Yii::app()->user->hasFlash('success')) {
    Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
}
if (Yii::app()->user->hasFlash('success_register')) {
    Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success_register'));
}



?>

<h1><?= $this->pageName; ?></h1>
<?php
if($model->is_deleted){
    echo Yii::app()->tpl->alert('danger',Yii::t('CartModule.admin','MSG_ORDER_DELETED'),false);
    return;
}


?>
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

                <?= $product->getRenderFullName(false); ?>

                <?= Html::openTag('span', array('class' => 'price')) ?>
                <?= ShopProduct::formatPrice(Yii::app()->currency->convert($product->price)) ?>
                <?= Yii::app()->currency->active->symbol; ?>
                <?= Html::closeTag('span') ?>
            </td>
            <td align="center">
                <?= $product->quantity ?>
            </td>
            <td align="center">
                <span class="price">
                <?php

                    echo ShopProduct::formatPrice(Yii::app()->currency->convert($product->price * $product->quantity));

                ?>
                    <sub><?= Yii::app()->currency->active->symbol; ?></sub>
                    </span>
            </td>
        </tr>
    <?php } ?>
</table>


<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h4><?= Yii::t('CartModule.default', 'USER_DATA') ?></h4></div>
            <div class="card-body">
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
                            <span class="price">
                            <?= ShopProduct::formatPrice(Yii::app()->currency->convert($model->delivery_price)) ?>
                                <sub><?= Yii::app()->currency->active->symbol ?></sub>
                                </span>
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
        <div class="card">
            <div class="card-header"><h4><?= Yii::t('CartModule.default', 'PAYMENT_METHODS') ?></h4></div>
            <div class="card-body">
                <?php foreach ($model->deliveryMethod->paymentMethods as $payment) { ?>
                    <?php
                    $activePay = ($payment->id == $model->payment_id) ? '<span class="icon-check " style="font-size:20px;color:green"></span>' : '';
                    ?>
                    <h3><?= $activePay; ?> <?= $payment->name ?></h3>
                    <p><?= $payment->description ?></p>
                    <p><?= $payment->renderPaymentForm($model) ?></p>
                <?php } ?>

                <?= Yii::t('CartModule.default', 'TOTAL_PAY') ?>
                <span class="price"><?= ShopProduct::formatPrice($model->full_price) ?></span>
                <?= Yii::app()->currency->active->symbol ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><?= Yii::t('CartModule.default', 'Состояние заказа') ?>
                    <span class="badge fr badge-secondary float-right"><?= $model->status_name ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if ($model->paid) { ?>
                    <?= Yii::t('CartModule.Order', 'PAID') ?>: <span
                            class="badge badge-success"><?= Yii::t('app', 'YES') ?></span>
                <?php } else { ?>
                    <?= Yii::t('CartModule.Order', 'PAID') ?>: <span
                            class="badge badge-danger"><?= Yii::t('app', 'NO') ?></span>
                <?php } ?>
                <div>
                <?= Yii::t('CartModule.Order', 'DATE_CREATE') ?>: <?= CMS::date($model->date_create) ?>
                </div>
            </div>
        </div>
    </div>
</div>
