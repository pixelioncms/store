


    <div class="product">


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
                <div class="product-label-tag <?= $color ?>">
                    <span><?= Yii::t('ShopModule.default', 'PRODUCT_LABEL', $data->label) ?></span>
                </div>


            <?php } ?>
            <div class="product-label-tag sale">
                <span><?= Yii::t('ShopModule.default', 'PRODUCT_LABEL', 3) ?></span>
            </div>
            <?php
            if ($data->appliedDiscount) {
                ?>
                <div class="product-label-tag discount"><span>- <?= $data->discountSum ?></span></div>

            <?php } ?>


        <div class="product-image">

                <?php

                //echo $data->attachmentsMain->id;
               echo Html::link(Html::image($data->getMainImageUrl('340x340',true), $data->name, array('class' => 'img-fluid')), $data->getUrl(), array('class' => ''));
                //echo Html::link(Html::image(Yii::app()->createUrl('/site/attachment',array('id'=>33)), $data->name, array('class' => 'img-fluid')), $data->getUrl(), array());
                ?>



        </div>


        <div class="product-info">
            <div class="product-title">
            <?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?>
            </div>
            <div class="clearfix">
                <?php $this->widget('ext.rating.StarRating', array('model' => $data, 'readOnly' => true)); ?>

                <small class="reviews float-right">
                    <a href="<?= $data->getUrl() ?>#comments_tab" class="lnk">(<?= $data->commentsCount ?> <?= CMS::GetFormatWord('common', 'REVIEWS', $data->commentsCount) ?>)</a>
                </small>

            </div>
            <div class="product-price clearfix">

                <span class="price float-left"><span><?= $data->priceRange() ?></span> <sup><?= Yii::app()->currency->active->symbol ?></sup></span>

                <?php
                if (Yii::app()->hasModule('discounts')) {
                    if ($data->appliedDiscount) {
                        ?>
                        <span class="price price-discount float-right">
                            <span><?= ShopProduct::formatPrice(Yii::app()->currency->convert($data->originalPrice)) ?></span>
                            <sup><?= Yii::app()->currency->active->symbol ?></sup>
                        </span>
                        <?php
                    }
                }
                ?>


            </div>

        </div>
        <div class="">

            <?php
            echo $data->beginCartForm();
            ?>
            <div class="action btn-group">

                <?php
                if ($data->isAvailable) {
                    echo Html::link('<i class="icon-cart"></i>', 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-primary icon btn-buy '));
                } else {
                    echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-danger'));
                }
                ?>


                <?php
                if (Yii::app()->hasModule('compare')) {
                    $this->widget('mod.compare.widgets.CompareWidget', array('pk' => $data->id, 'skin' => 'icon', 'linkOptions' => array('class' => 'btn btn-primary btn-wishlist')));
                }
                if (Yii::app()->hasModule('wishlist') && !Yii::app()->user->isGuest) {
                    $this->widget('mod.wishlist.widgets.WishlistWidget', array('pk' => $data->id, 'skin' => 'icon', 'linkOptions' => array('class' => 'btn btn-primary btn-compare')));
                }
                ?>


            </div><!-- /.action -->


            <?php echo $data->endCartForm(); ?>
        </div>
    </div>








