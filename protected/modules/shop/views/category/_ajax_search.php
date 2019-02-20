<?php

if ($model) { ?>
    <div class="list-group">
        <?php foreach ($model as $data) { ?>
            <div href="<?= $data->getUrl(); ?>" class="list-group-item">
                <div class="row">
                    <div class="col-sm-2">
                        <?php
                        echo Html::link(Html::image($data->getMainImageUrl('100x100'), $data->mainImageTitle, array('class' => 'img-fluid')), $data->getUrl(), array('class' => ''));
                        ?>
                    </div>
                    <div class="col-sm-10">
                        <?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?>
                        <div class="product-price clearfix">
                            <span><?= $data->priceRange() ?></span><sup><?= Yii::app()->currency->active->symbol ?></sup>
                        </div>
                        <?php
                        if (Yii::app()->hasModule('discounts')) {
                            if ($data->appliedDiscount) {
                                ?>
                                <div class="product-price clearfix product-price-discount">
                                    <span><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($data->originalPrice)) ?></span><sup><?= Yii::app()->currency->active->symbol ?></sup>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <a href="<?= $data->getUrl(); ?>" class="list-group-item active text-center">
            Весь результат

        </a>
    </div>
<?php }