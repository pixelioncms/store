<tr>
    <td class="col-md-2">
        <?= Html::image($data->getMainImageUrl('700x700'), Html::encode($data->name)) ?>
    </td>
    <td class="col-md-5">
        <div class="product-name"><?= Html::link(Html::encode($data->name), $data->getUrl()) ?></div>
        <div class="rating">
            <?php $this->widget('ext.rating.StarRating', array('model' => $data)); ?>
            <span class="review">(<?= $data->commentsCount ?> <?= CMS::GetFormatWord('common', 'REVIEWS', $data->commentsCount) ?>)</span>
        </div>



        <div class="price"><?= ShopProduct::formatPrice($data->getFrontPrice()); ?> <?= Yii::app()->currency->active->symbol; ?>

            <?php
            if (Yii::app()->hasModule('discounts')) {
                if ($data->appliedDiscount) {
                    ?>
                    <span><?= ShopProduct::formatPrice(Yii::app()->currency->convert($data->originalPrice)) ?> <?= Yii::app()->currency->active->symbol ?></span>

                    <?php
                }
            }
            ?>
        </div>




    </td>
    <td class="col-md-4">
        <?php
        echo $data->beginCartForm();
        ?>

        <?php
        if ($data->isAvailable) {
            $this->widget('mod.cart.widgets.buyOneClick.BuyOneClickWidget', array('pk' => $data->id));
            Yii::import('mod.cart.CartModule');
            CartModule::registerAssets();
            echo Html::link('<i class="icon-shopcart inner-right-vs"></i>' . Yii::t('common', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn-upper btn btn-primary'));
        } else {
            echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
        }
        ?>



        <?php echo $data->endCartForm(); ?>
    </td>
    <td class="col-md-1 close-btn">
        <?php
        if ($this->model->getUserId() === Yii::app()->user->id) {
            echo Html::link('<i class="icon-delete"></i>', array('remove', 'id' => $data->id), array(
                'class' => 'btn btn-danger remove',
            ));
        }
        ?>

    </td>
</tr>


