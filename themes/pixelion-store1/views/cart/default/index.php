<?php
$cs = Yii::app()->clientScript;
//$cs->registerScriptFile($this->module->assetsUrl . '/cart.js', CClientScript::POS_END);
$cs->registerScript('cart', "
//cart.selectorTotal = '#total';
var orderTotalPrice = '$totalPrice';

", CClientScript::POS_HEAD);
?>
<script>

    function submitform() {
        if (document.cartform.onsubmit &&
            !document.cartform.onsubmit()) {
            return;
        }
        document.cartform.submit();
    }
</script>
<?php
if (empty($items)) { ?>
    <div id="empty-cart-page" class="text-center">

        <i class="icon-shopcart" style="font-size:130px"></i>
        <h2><?= Yii::t('CartModule.default', 'CART_EMPTY_HINT') ?></h2>

        <?= Html::link(Yii::t('CartModule.default', 'CART_EMPTY_BTN'), array('/'), array('class' => 'btn btn-lg btn-outline-secondary')); ?>
    </div>
    <?php return;
}
?>
<?php
$this->widget('ext.fancybox.Fancybox', array('target' => 'a.thumbnail'));
?>
<?php echo Html::form(array('/cart'), 'post', array('id' => 'cart-form', 'name' => 'cartform')) ?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th colspan="2"><?= Yii::t('CartModule.default', 'BUTTON_CHECKOUT'); ?></th>

            <th><?= Yii::t('CartModule.default', 'TABLE_NUM'); ?></th>
            <th><?= Yii::t('CartModule.default', 'TABLE_PRICE'); ?></th>
            <th><?= Yii::t('CartModule.default', 'TABLE_SUM'); ?></th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($items as $index => $product) { ?>
            <?php
            // print_r($product);die;
            $price = ShopProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);
            ?>


            <tr id="product-<?= $index ?>">

                <td class="cart-image">

                    <?php
                    echo Html::image($product['model']->getMainImageUrl('100x100'), '', array('class' => 'entry-thumbnail'));
                    ?>
                </td>
                <td>

                    <?php
                    // Display product name with its variants and configurations
                    echo Html::link(Html::encode($product['model']->name), array('/shop/product/view', 'seo_alias' => $product['model']->seo_alias));
                    ?>

                    <div class="row">
                        <div class="col-sm-4">
                            <div>
                                <?php $this->widget('ext.rating.StarRating', array('model' => $product['model'], 'readOnly' => true)); ?>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="reviews">
                                (<?= $product['model']->commentsCount ?> <?= CMS::GetFormatWord('common', 'REVIEWS', $product['model']->commentsCount) ?>
                                )
                            </div>
                        </div>
                    </div>

                </td>
                <td class="cart-product-quantity">

                    <?php echo Html::textField("quantities[$index]", $product['quantity'], array('class' => 'spinner', 'product_id' => $index)) ?>

                </td>
                <td class="cart-product-sub-total">
                                <span class="cart-sub-total-price">

                                    <?php
                                    // Price

                                    echo Html::openTag('span', array('class' => 'price'));
                                    echo ShopProduct::formatPrice(Yii::app()->currency->convert($price));
                                    echo ' ' . Yii::app()->currency->active->symbol;
                                    //echo ' '.($product['currency_id']) ? Yii::app()->currency->getSymbol($product['currency_id']) : Yii::app()->currency->active->symbol;
                                    echo Html::closeTag('span');

                                    // Display variant options
                                    if (!empty($product['variant_models'])) {
                                        echo Html::openTag('span', array('class' => 'cartProductOptions'));
                                        foreach ($product['variant_models'] as $variant)
                                            echo ' - ' . $variant->attribute->title . ': ' . $variant->option->value . '<br/>';
                                        echo Html::closeTag('span');
                                    }

                                    // Display configurable options
                                    if (isset($product['configurable_model'])) {
                                        $attributeModels = ShopAttribute::model()->findAllByPk($product['model']->configurable_attributes);
                                        echo Html::openTag('span', array('class' => 'cartProductOptions'));
                                        foreach ($attributeModels as $attribute) {
                                            $method = 'eav_' . $attribute->name;
                                            echo ' - ' . $attribute->title . ': ' . $product['configurable_model']->$method . '<br/>';
                                        }
                                        echo Html::closeTag('span');
                                    }
                                    ?></span>
                </td>
                <td id="price-<?= $index ?>" class="cart-product-grand-total">

                                    <span class="cart-total-product" id="row-total-price<?= $index ?>">
                                        <?php
                                        echo ShopProduct::formatPrice(Yii::app()->currency->convert($price * $product['quantity']));
                                        ?>
                                    </span>
                    <sup><?= Yii::app()->currency->active->symbol; ?></sup>
                </td>
                <td class="romove-item">
                    <?= Html::link(Html::icon('icon-delete'), array('/cart/default/remove', 'id' => $index), array('class' => 'remove')) ?>

                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</div>

<div class="col-md-4 col-sm-12">
    <?php
    $this->renderPartial('_fields_user', array('form' => $this->form));


    ?>
</div>

<div class="col-md-4 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5><?= Yii::t('CartModule.default', 'DELIVERY_PAYMENT'); ?></h5>
        </div>
        <div class="card-body">

            <p><?= Yii::t('CartModule.default', 'DELIVERY_PAYMENT_HINT'); ?></p>
            <?php
            $this->renderPartial('_fields_delivery', array(
                    'form' => $this->form,
                    'deliveryMethods' => $deliveryMethods)
            );
            $this->renderPartial('_fields_payment', array(
                    'form' => $this->form,
                    'paymenyMethods' => $paymenyMethods)
            );
            ?>
            <div id="delivery-form">

            </div>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5><?= Yii::t('CartModule.default', 'TOTAL_PAY'); ?></h5>
        </div>
        <div class="card-body">
            <p>asdasd</p>

            <div class="cart-grand-total">
                Всего к оплате:
                <span id="total" class="inner-left-md"><?= ShopProduct::formatPrice($totalPrice) ?></span>
                <i><?php echo Yii::app()->currency->active->symbol; ?></i>
            </div>

            <a href="javascript:submitform();"
               class="btn btn-lg btn-primary  btn-block"><?= Yii::t('CartModule.default', 'BUTTON_CHECKOUT'); ?></a>

        </div>
    </div>


</div>
<input class="button btn-green" type="hidden" name="create" value="1">
<?php echo Html::endForm() ?>



