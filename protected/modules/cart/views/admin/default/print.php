<table border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table table-bordered table-striped">
    <tr>
        <td width="55%">

              <table border="0" cellspacing="0" cellpadding="0" style="width:100%;" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td width="30%"><?= $model->getAttributeLabel('user_name'); ?>:</td>
                        <td width="70%"><?= $model->user_name; ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?= $model->getAttributeLabel('user_phone'); ?>:</td>
                        <td width="70%"><?= $model->user_phone; ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?= Yii::t('CartModule.default', 'ADDRESS'); ?>:</td>
                        <td width="70%" align="left" class="text-center"><?= ($model->user_address) ? $model->user_address : str_repeat('__', 14); ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?= Yii::t('CartModule.default', 'DELIVERY'); ?>:</td>
                        <td width="70%"><?= ($model->deliveryMethod) ? $model->deliveryMethod->name : str_repeat('__', 14); ?></td>
                    </tr>
                    <tr>
                        <td width="30%"><?= Yii::t('CartModule.default', 'PAYMENT'); ?>:</td>
                        <td width="70%"><?= ($model->paymentMethod) ? $model->paymentMethod->name : str_repeat('__', 14); ?></td>
                    </tr>
                </thead>
            </table>
        </td>
        <td width="45%">
            <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <?php if($model->user_comment){ ?>
                    <tr>
                        <td width="100%"><small><b><?= $model->getAttributeLabel('user_comment'); ?>:</b></small></td>
                    </tr>
                    <tr>
                        <td width="100%"><small><?= $model->user_comment; ?></small></td>
                    </tr>
                    <?php } ?>
                    <?php if($model->admin_comment){ ?>
                    <tr>
                        <td width="100%"><small><b><?= $model->getAttributeLabel('admin_comment'); ?>:</b></small></td>
                    </tr>
                    <tr>
                        <td width="100%"><small><?= $model->admin_comment; ?></small></td>
                    </tr>
                    <?php } ?>
                </thead>
            </table>
        </td>
    </tr>
</table>

<center><h1><?= Yii::t('CartModule.default', 'ORDER_PRODUCTS_ID',array('{id}'=>$model->id)); ?></h1></center>
<?php if ($model->products) { ?>
    <table border="1" cellspacing="0" cellpadding="2" style="width:100%;" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="50%" colspan="2" align="center" class="text-center"><?= Yii::t('CartModule.default', 'TABLE_TH_MAIL_NAME'); ?></th>
                <th width="10%" align="center" class="text-center"><?= Yii::t('CartModule.default', 'TABLE_TH_MAIL_QUANTITY', 1); ?></th>
                <th width="20%" align="center" class="text-center">Цена за шт.</th>
                <th width="20%" align="center" class="text-center">Общая цена</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($model->products as $product) {
                if(isset($product->prd->mainImage)){
                    $image = Html::image(Yii::app()->createAbsoluteUrl($product->prd->getMainImageUrl('50x50')), $product->prd->name, array('width' => 50, 'height' => 50));
                }else{
                    $image='No image';
                }

                $newprice = ($product->prd->appliedDiscount) ? $product->prd->discountPrice : $product->price;
                ?>
                <tr>
                    <td width="10%" align="center"><?= $image; ?></td>
                    <td width="40%"><?= $product->prd->name ?></td>
                    <td width="10%" align="center"><?= $product->quantity ?></td>
                    <td width="20%" align="center"><?= Yii::app()->currency->number_format($newprice) ?> <?= Yii::app()->currency->active->symbol ?></td>
                    <td width="20%" align="center"><?= Yii::app()->currency->number_format($newprice * $product->quantity) ?> <?= Yii::app()->currency->active->symbol ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <br/><br/>
    <?= Yii::t('CartModule.default', 'TOTAL_PAY'); ?>: <h1><?= $model->total_price; ?> <?= Yii::app()->currency->active->symbol ?></h1>
<?php } ?>         


