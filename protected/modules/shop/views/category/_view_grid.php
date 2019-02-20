

<div class="col-md-4">
    <div class="product-item hvr-float">
        <div class="product-item-border2">

            <?php
            /* $this->widget('mod.shop.widgets.productLabel.ProductLabelWidget', array(
              'model' => $data,
              )); */
            ?>
            <div class="product-img">

                <?php
                echo Html::link(Html::image($data->getMainImageUrl('340x340'), $data->mainImageTitle, array('class' => 'img-responsive')), $data->getUrl(), array('class' => ''));
                ?>
                <div>
                    <?php
                    if (Yii::app()->hasModule('compare')) {
                        $this->widget('mod.compare.widgets.CompareWidget', array('pk' => $data->id));
                    }
                    if (Yii::app()->hasModule('wishlist') && !Yii::app()->user->isGuest) {
                        $this->widget('mod.wishlist.widgets.WishlistWidget', array('pk' => $data->id));
                    }
                    ?>

                    <?php echo Html::link('View', $data->getUrl(), array('class' => 'btn')) ?>
                </div>
            </div>
            <h3><?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?></h3>
            <?php
            if (Yii::app()->hasModule('discounts')) {
                if ($data->appliedDiscount) {
                    ?>
                    <div class="product-price clearfix product-price-discount"><span><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($data->originalPrice)) ?></span><sup><?= Yii::app()->currency->active->symbol ?></sup></div>
                    <?php
                }
            }
            ?>
            <div class="product-price clearfix"><span><?= $data->priceRange() ?></span><sup><?= Yii::app()->currency->active->symbol ?></sup></div>
            <?php
            echo Html::form(array('/cart/add'), 'post', array('id' => 'form-add-cart-' . $data->id));
            echo Html::hiddenField('product_id', $data->id);
            echo Html::hiddenField('product_price', $data->price);
            echo Html::hiddenField('use_configurations', $data->use_configurations);
            echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
            echo Html::hiddenField('currency_id', $data->currency_id);
            echo Html::hiddenField('supplier_id', $data->supplier_id);
            echo Html::hiddenField('configurable_id', 0);
            ?>
            <div class="clearfix"></div>
            <div class="clearfix">
                <?php
                if ($data->isAvailable) {
                    echo Html::link(Yii::t('common', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-add2cart'));
                } else {
                    echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
                }
                ?>
            </div>
            <?php echo Html::endForm(); ?>
        </div>
    </div>
</div>
