<section class="section featured-product wow fadeInUp">
    <h3 class="section-title">Новые товары1</h3>
    <div class="owl-carousel home-owl-carousel custom-carousel owl-theme outer-top-xs">
        <?php

        $model = ShopProduct::model()
                ->published()
                ->limited(10)
                //->between(date('Y-m-d H:i:s', strtotime("-30 days")),date('Y-m-d H:i:s'))
                ->findAll();
        foreach ($model as $data) {
            ?>
            <div class="item item-carousel">
                <div class="products">

                    <div class="product">		
                        <div class="product-image">
                            <div class="image">
                                <?php
                                echo Html::link(Html::image($data->getMainImageUrl('700x700'), $data->name, array('class' => '2img-responsive')), $data->getUrl(), array('class' => ''));
                                ?>
                            </div><!-- /.image -->			

                            <div class="product-label">            
                                <?php
                                if ($data->productLabel) {
                                    if ($data->productLabel['class'] == 'new') {
                                        $color = 'new';
                                    } elseif ($model->productLabel['class'] == 'hit') {
                                        $color = 'purple';
                                    } else {
                                        $color = 'blue';
                                    }
                                    ?>
                                    <div class="product-label-tag <?= $color ?>"><span><?= Yii::t('ShopModule.default', 'PRODUCT_LABEL', $data->label) ?></span></div>


                                <?php } ?>
                                <?php
                                if ($data->appliedDiscount) {
                                    ?>
                                    <div class="product-label-tag sale"><span>- <?= $data->discountSum ?></span></div>

                                <?php } ?>
                            </div>
                        </div><!-- /.product-image -->


                        <div class="product-info text-left">
                            <h3 class="name"><?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?></h3>
                            <div class="rating clearfix">
                                <?php $this->widget('ext.rating.StarRating', array('model' => $data, 'readOnly' => true)); ?>
                            </div>
                            <div class="description"></div>

                            <div class="product-price">	
                                <span class="price"><?= $data->priceRange() ?> <?= Yii::app()->currency->active->symbol ?></span>

                                <?php
                                if (Yii::app()->hasModule('discounts')) {
                                    if ($data->appliedDiscount) {
                                        ?>
                                        <span class="price-before-discount"><?= ShopProduct::formatPrice(Yii::app()->currency->convert($data->originalPrice)) ?> <sup><?= Yii::app()->currency->active->symbol ?></sup></span>
                                        <?php
                                    }
                                }
                                ?>


                            </div><!-- /.product-price -->

                        </div><!-- /.product-info -->
                        <div class="cart clearfix animate-effect">
                            <div class="action">
                                <ul class="list-unstyled">
                                    <li class="add-cart-button btn-group">
                                        <button class="btn btn-primary icon" data-toggle="dropdown" type="button">
                                            <i class="icon-shopcart"></i>													
                                        </button>
                                        <button class="btn btn-primary cart-btn" type="button">Add to cart</button>

                                    </li>

                                    <li class="lnk wishlist">
                                        <a class="add-to-cart" href="detail.html" title="Wishlist">
                                            <i class="icon-heart"></i>
                                        </a>
                                    </li>

                                    <li class="lnk">
                                        <a class="add-to-cart" href="detail.html" title="Compare">
                                            <i class="icon-compare" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- /.action -->
                        </div><!-- /.cart -->
                    </div><!-- /.product -->

                </div><!-- /.products -->
            </div><!-- /.item -->
        <?php } ?>

    </div><!-- /.home-owl-carousel -->
</section><!-- /.section -->