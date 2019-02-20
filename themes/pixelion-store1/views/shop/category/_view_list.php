
<div class="col-xs-12">
    <div class="category-product-inner wow fadeInUp">
        <div class="products">				
            <div class="product-list product">
                <div class="row">
                    <div class="col-sm-4 col-lg-4">
                        <div class="product-image">
                            <div class="image">
                                <?php
                                echo Html::link(Html::image($data->getMainImageUrl('340x340'), $data->name, array('class' => '2img-responsive')), $data->getUrl(), array('class' => ''));
                                ?>
                            </div>
                        </div><!-- /.product-image -->
                    </div><!-- /.col -->
                    <div class="col-sm-8 col-lg-8">
                        <div class="product-info">
                            <h3 class="name"><?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?></h3>
                            <div>
                                <?php $this->widget('ext.rating.StarRating', array('model' => $data, 'readOnly' => true)); ?>
                            </div>
                            <div class="product-price clearfix">	
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
                            <div class="description m-t-10">
                                <?= $data->short_description ?>
                            </div>
                            <?php
                            if (!$data->archive) {
                                echo $data->beginCartForm();
                                ?>
                                <div class="cart clearfix animate-effect">
                                    <div class="action">
                                        <ul class="list-unstyled">
                                            <li class="add-cart-button btn-group">
                                                <?php
                                                if ($data->isAvailable) {
                                                    echo Html::link(Yii::t('common', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-primary icon'));
                                                } else {
                                                    echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-danger'));
                                                }
                                                ?>
                                            </li>

                                            <li class="lnk wishlist">
                                                <a class="add-to-cart" href="detail.html" title="Wishlist">
                                                    <i class="icon fa fa-heart"></i>
                                                </a>
                                            </li>

                                            <li class="lnk">
                                                <a class="add-to-cart" href="detail.html" title="Compare">
                                                    <i class="fa fa-signal"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div><!-- /.action -->
                                </div><!-- /.cart -->
                                <?php echo $data->endCartForm(); ?>
                            <?php } else { ?>
                                <?= Yii::app()->tpl->alert('info', 'Снят с производства.'); ?>
                            <?php } ?>
                        </div><!-- /.product-info -->	
                    </div><!-- /.col -->
                </div><!-- /.product-list-row -->







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


            </div><!-- /.product-list -->
        </div>
    </div>
</div>

