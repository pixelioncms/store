









<div class="product">
    <div class="product-box clearfix">

        <div class="clearfix gtile-i-150-minheight">
            <?php
            echo CHtml::link(CHtml::image($data->getMainImageUrl('240x240'), $data->mainImageTitle), $data->getUrl(), array('class' => 'thumbnail'));
            ?>
        </div>


        <div class="product-title">
            <?php echo CHtml::link(CHtml::encode($data->name), $data->getUrl()) ?>
        </div>

        <div class="product-price">
            <span class="price">
                <?php
                if (Yii::app()->hasModule('discounts')) {
                    if ($data->appliedDiscount) {
                        echo '<span style="color:red; "><s>' . $data->toCurrentCurrency('originalPrice') . '</s></span>';
                    }
                }
                ?>
                <?= $data->priceRange() ?></span>
            <sup><?= Yii::app()->currency->active->symbol ?></sup>
        </div>

        <?php
        echo Html::form(array('/cart/add'), 'post', array('class' => 'product-form', 'id' => 'form-add-cart' . $data->id));
        echo Html::hiddenField('product_id', $data->id);
        echo Html::hiddenField('product_price', $data->price);
        echo Html::hiddenField('use_configurations', $data->use_configurations);
        echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
        echo Html::hiddenField('currency_id', $data->currency_id);
        echo Html::hiddenField('supplier_id', $data->supplier_id);
        echo Html::hiddenField('pcs', $data->pcs);
        echo Html::hiddenField('configurable_id', 0);
        if (Yii::app()->hasModule('cart')) {
            if ($data->isAvailable) {
                echo Html::link(Yii::t('ShopModule.default', 'BUY'), 'javascript:shop.addCart(' . $data->id . ')', array('class' => 'button btn-green cart', 'onClick' => ''));
                //   echo Html::button(Yii::t('shopModule.default', 'BUY'), array('class' => 'button btn-green cart', 'onClick' => 'shop.addCart(' . $data->id . ')'));
            } else {
                echo Html::link('Нет в наличии', 'javascript:shop.notifier(' . $data->id . ');');
            }
        }
        echo Html::endForm();
        ?>

        <?php
        echo CHtml::link(Yii::t('app', 'DELETE'), array('/compare/default/remove', 'id' => $data->id), array(
            'class' => 'remove',
        ));
        ?>



        <ul class="product-detail">
            <li><?php echo $data->short_description; ?></li>
        </ul>

    </div>
</div>









