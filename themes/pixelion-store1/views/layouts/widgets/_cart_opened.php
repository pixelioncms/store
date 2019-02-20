<?php if ($count > 0) { ?>
    <div class="dropdown">
        <div class="cart-info dropdown-toggle" id="cart-items" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="count"><?= $count ?></span>
            <span><?= $total; ?></span> <small><?= $currency->symbol; ?></small>
        </div>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cart-items">
            <?php
            foreach ($items as $product) {
                ?>
                <?php
                $price = ShopProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);
                ?>
                <div class="cart-product-item">
                    <div class="cart-product-item-image">
                        <?php echo Html::image($product['model']->getMainImageUrl('50x50'), '', array('class' => 'img-thumbnail')) ?>
                    </div>
                    <div class="cart-product-item-detail">
                        <?php echo Html::link($product['model']->name, $product['model']->getUrl()) ?>
                        <br/>
                        (<?php echo $product['quantity'] ?>)
                        <?= ShopProduct::formatPrice(Yii::app()->currency->convert($price)) ?> <?= $currency->symbol; ?>
                    </div>
                </div>

            <?php } ?>
            <div class="cart-detail clearfix">
                <span class="total-price pull-left"><span class="label label-success"><?= $total ?></span> <?= $currency->symbol; ?></span>
                <?= Html::link(Yii::t('CartModule.default', 'BUTTON_CHECKOUT'), array('/cart'), array('class' => 'btn btn-sm btn-primary pull-right')) ?>
            </div>
        </div>
    </div>

<?php } else { ?>
    <div class="cart-info">
        <span class="hidden-xs"><?= Yii::t('CartModule.default', 'CART_EMPTY') ?></span>
    </div>
<?php } ?>
