<div class="dropdown">
    <div class="<?= ($count)?'dropdown-toggle':'cart-empty'; ?>" <?= ($count)?'data-toggle="dropdown"':''; ?> aria-haspopup="true" aria-expanded="false">


            <?php if ($count > 0) { ?>
                <span class="badge badge-success"><?= $count ?></span>
                <span class="price"><span class="total_price"><?= $total ?></span> <sub><?= $currency->symbol; ?></sub></span>

            <?php } else { ?>
                <span class="badge badge-secondary"><?= $count ?></span>
               <?= Yii::t('CartModule.default', 'CART_EMPTY') ?>
            <?php } ?>


        <?php if ($count > 0) { ?>
            <div class="dropdown-menu dropdown-menu-right">

                <?php
                foreach ($items as $index => $product) {
                    ?>
                    <?php
                    $price = ShopProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);
                    ?>


                    <div id="cart-item-<?=$index?>" class="cart-item row">
                        <div class="col-3">
                            <div class="image">
                                <?php echo Html::link(Html::image($product['model']->getMainImageUrl('100x100'), '', array('class' => 'img-thumbnail1')), $product['model']->getUrl()) ?>
                            </div>
                        </div>
                        <div class="col-9">


                            <div class="cart-item-content">
                                <?= Html::link($product['model']->name, $product['model']->getUrl()) ?>
                                <span class="price price-sm">
                            (<?php echo $product['quantity'] ?>шт.)
                                    <?= ShopProduct::formatPrice(Yii::app()->currency->convert($price)) ?> <sub><?= $currency->symbol; ?></sub>
                        </span>

                                <?= Html::link('<i class="icon-delete"></i>', array('/cart/default/remove', 'id' => $index), array('class' => 'remove')) ?>
                            </div>
                        </div>

                    </div>

                <?php } ?>


                <div class="cart-total">

                    <div><?= Yii::t('CartModule.default', 'TOTAL_PAY') ?>:
                    <span class="price"><span class="total_price"><?= $total ?></span> <sub><?= $currency->symbol; ?></sub></span>
                    </div>
                    <?= Html::link(Yii::t('CartModule.default', 'BUTTON_CHECKOUT'), array('/cart'), array('class' => 'btn btn-primary btn-block m-t-20')) ?>
                </div>

            </div>
        <?php } ?>
    </div>

</div>

<script>
    $(function () {
        jQuery('.dropdown-toggle').on('click', function (e) {
            $(this).next().toggle();
        });
        jQuery('.dropdown-menu').on('click', function (e) {
            e.stopPropagation();
        });


        $('.remove').click(function(e){
            var that = $(this);
            $.ajax({
                url:that.attr('href'),
                success:function(data){
                    $('#cart-item-'+data.id).remove();
                    $('.total_price').text(data.total);
                    cart.renderBlockCart();
                    common.notify(data.message,'success');
                }
            })


            e.preventDefault();
        });
    });
</script>

