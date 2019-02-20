


<div class="sidebar-widget hot-deals wow fadeInUp outer-bottom-xs">
    <h3 class="section-title">Скидки</h3>
    <div class="owl-carousel sidebar-carousel custom-carousel owl-theme outer-top-ss">

        <?php
        $productsList = ShopProduct::model()->published()->random()->limited(5)->findAll();
        foreach ($productsList as $data) {
            if($data->appliedDiscount){
            ?>
            <div class="item">
                <div class="products">
                    <div class="hot-deal-wrapper">
                        <div class="image">
                            <?= Html::image($data->getMainImageUrl('700x700'), $data->name, array('class' => '2img-responsive')) ?>
                        </div>

                        <?php
                        if ($data->appliedDiscount) {
                            ?>
                            <div class="sale-offer-tag"><span>- <?= $data->discountSum ?></span></div>

                        <?php } ?>
                        <?php $this->widget('mod.discounts.widgets.countdown.CountdownWidget', array('model' => $data)) ?>



                    </div>

                    <div class="product-info text-left m-t-20">
                        <h3 class="name"><?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?></h3>
                        <div class="rating rateit-small"></div>

                        <div class="product-price">	
                            <span class="price"><?= $data->priceRange() ?> <?= Yii::app()->currency->active->symbol ?></span>
                            <?php
                            if (Yii::app()->hasModule('discounts')) {
                                if ($data->appliedDiscount) {
                                    ?>
                                    <span class="price-before-discount"><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($data->originalPrice)) ?> <sup><?= Yii::app()->currency->active->symbol ?></sup></span>
                                    <?php
                                }
                            }
                            ?>

                        </div><!-- /.product-price -->

                    </div><!-- /.product-info -->
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
                    <div class="cart clearfix animate-effect">
                        <div class="action">
                            <?php
                            if ($data->isAvailable) {
                                echo Html::link(Yii::t('common', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-primary cart-btn'));
                            } else {
                                echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-danger'));
                            }
                            ?>

                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                </div>	
            </div>	
        <?php } } ?>
    </div>
</div>