<?php if ($count > 0) { ?>

    <a href="/cart" class="cart-info">
        <span class="count"><?= $count ?></span>
        <span><?= $total; ?></span> <small><?= $currency->symbol; ?></small>
    </a>


<?php } else { ?>
    <div class="cart-info">
        <span class="hidden-xs"><?= Yii::t('CartModule.default', 'CART_EMPTY') ?></span>
    </div>
<?php } ?>
