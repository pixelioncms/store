

<li>

    <div class="product">
        <?php
        echo CHtml::link(Yii::t('app', 'DELETE'), array('/compare/default/remove', 'id' => $data->id), array(
            'class' => 'remove',
        ));
        ?>
        <?php
        $this->widget('mod.shop.widgets.productLabel.ProductLabelWidget', array(
            'model' => $data,
            'skin' => 'april'
        ));
        ?>
        <div class="product-box">
            <div class="product-image-box">



                <?php
                echo Html::link(Html::image($data->getMainImageUrl('261x280'), $data->mainImageTitle, array('class' => 'img-responsive', 'height' => 265)), $data->getUrl(), array('class' => ''));
                ?>
            </div>





            <div class="product-title-shadow">
                <?= Html::link(Html::encode($data->name), $data->getUrl(), array('class' => 'product-title')) ?>
            </div>
            <div class="product-info">
            <div class="product-availability">
                <?php if ($data->availability == 1) { ?>
                    <span class="yes"><?= Yii::t('ShopModule.default', 'AVAILABILITY', $data->availability) ?></span>
                <?php } elseif ($data->availability == 3) { ?>
                    <span class="order"><?= Yii::t('ShopModule.default', 'AVAILABILITY', $data->availability) ?></span>
                <?php } else { ?>
                    <span class="no"><?= Yii::t('ShopModule.default', 'AVAILABILITY', $data->availability) ?></span>
                <?php } ?>

            </div>
                <div class="product-price">

                    <?php
                    if ($data->appliedDiscount) {
                        ?>
                        <span class="price price-xs price-discount"><?= Yii::app()->currency->number_format($data->priceOriginal) ?> <sub><?= Yii::app()->currency->active->symbol; ?></sub></span>
                        <?php
                    }
                    ?>
                    <span class="price price-md">
                        <?= $data->priceRange() ?>
                        <sub><?= Yii::app()->currency->active->symbol; ?></sub>
                    </span>

                </div>
                <?php
                echo $data->beginCartForm();
                ?>
                <div class="product-options">
                    <?php
                    if ($data->availability == 1 || $data->availability == 3) {
                        Yii::import('mod.cart.CartModule');
                        // CartModule::registerAssets();
                        echo Html::link(Yii::t('CartModule.default', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-buy'));
                    } else {
                        echo Html::link(Yii::t('app', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
                    }
                    if (Yii::app()->hasModule('wishlist') && !Yii::app()->user->isGuest) {
                        $this->widget('mod.wishlist.widgets.WishlistWidget', array('pk' => $data->id));
                    }
                    ?>
                </div>


                <?php echo $data->endCartForm(); ?>
            </div>
        </div>
    </div>


    <table class="table table-striped compare-table">
     
        <?php

        if(isset($this->model->attributes[$cat_id][$data->id])){

        foreach ($this->model->attributes[$cat_id][$data->id]['attrs'] as $attribute) {
            if ($isType) {
                $unq = array();

                foreach ($gp[$cat_id]['items'] as $product) {

                    $unq[] = (string) $product->{'eav_' . $attribute->name};
               }

                foreach (array_count_values($unq) as $pid => $count) {
                    $flag = true;

                    if ($count == count($gp[$cat_id]['items'])) {
                        $flag = false;
                    }
                }
            } else {
                $flag = true;
            }
            if ($flag) {//$flag
                ?>

                <tr>
                    <td>123
                        <?php
                        $value = $data->{'eav_' . $attribute->name};
                        echo $value === null ? Yii::t('ShopModule.default', 'Не указано') : $value;
                        ?>
                    </td>
                </tr>


                <?php
            }
        }
        }
        ?>

    </table>
</li>

