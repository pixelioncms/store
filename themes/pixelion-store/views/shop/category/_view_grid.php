<div class="product">
    <div class="product-label-container">
        <?php foreach ($data->labelData as $label) { ?>
            <div>
                <span class="product-label-tag <?= $label['class'] ?>">
                    <span><?= $label['value'] ?></span>
                </span>
            </div>

        <?php } ?>
    </div>


    <div class="product-image d-flex justify-content-center align-items-center">
        <?php
        echo Html::link(Html::image($data->getMainImageUrl('340x265', true), $data->name, array('class' => 'img-fluid loading')), $data->getUrl(), array());
        //echo Html::link(Html::image(Yii::app()->createUrl('/site/attachment',array('id'=>33)), $data->name, array('class' => 'img-fluid')), $data->getUrl(), array());
        ?>
    </div>
    <div class="product-info">
        <?= Html::link(Html::text(Html::encode($data->name)), $data->getUrl(), array('class' => 'product-title')) ?>
    </div>
    <div class="">

        <?php
        echo $data->beginCartForm();
        ?>


        <div class="product-data">
            <div class="row no-gutters">
                <div class="col-6 col-sm-6 col-lg-6">
                    <?php $this->widget('ext.rating.StarRating', array('model' => $data, 'readOnly' => true)); ?>
                    <br/>
                    <span class="product-review">
                <a href="<?= $data->getUrl() ?>#comments_tab">(<?= $data->commentsCount ?> <?= CMS::GetFormatWord('common', 'REVIEWS', $data->commentsCount) ?>
                    )</a>
            </span>
                </div>
                <div class="col-6 col-sm-6 col-lg-6 text-right">
                    <?php
                    if (Yii::app()->hasModule('compare')) {
                        $this->widget('mod.compare.widgets.CompareWidget', array('pk' => $data->id, 'skin' => 'icon', 'linkOptions' => array('class' => 'btn btn-compare')));
                    }
                    if (Yii::app()->hasModule('wishlist') && !Yii::app()->user->isGuest) {
                        $this->widget('mod.wishlist.widgets.WishlistWidget', array('pk' => $data->id, 'skin' => 'icon', 'linkOptions' => array('class' => 'btn btn-wishlist')));
                    }
                    ?>
                </div>
            </div>
            <div class="row no-gutters mt-2">
                <div class="col-6 col-sm-6 col-lg-6 d-flex align-items-center">
                    <div class="product-price">

                        <?php
                        if (Yii::app()->hasModule('discounts')) {
                            if ($data->appliedDiscount) {
                                ?>
                                <span class="price price-discount">
                            <span><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($data->originalPrice)) ?></span>
                            <sub><?= Yii::app()->currency->active->symbol ?></sub>
                                    <span class="discount-sum">-<?= $data->discountSum; ?></span>
                        </span>
                                <?php
                            }
                        }
                        ?>
                        <div>
                            <span class="price"><span><?= $data->priceRange() ?></span> <sub><?= Yii::app()->currency->active->symbol ?></sub></span>
                        </div>


                    </div>
                </div>
                <div class="col-6 col-sm-6 col-lg-6 text-right">
                    <?php
                    if ($data->isAvailable) {
                        echo Html::link('Купить', 'javascript:cart.add(' . $data->id . ')', array('class' => 'btn btn-secondary btn-buy d-block'));
                    } else {
                        echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-danger'));
                    }
                    ?>
                </div>
            </div>

        </div>

        <div class="action btn-group2">
            <?php print_r($data->eav_box); ?>

        </div><!-- /.action -->


        <?php echo $data->endCartForm(); ?>
    </div>
</div>








