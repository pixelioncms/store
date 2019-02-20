
<div class="product-list text-left">
    <div class="product-box">
        <div class="gtile-i-150-minheight">

        </div>


        <div class="product-title h4">
            <?php echo Html::link(Html::encode($data->name), array('product/view', 'seo_alias' => $data->seo_alias)) ?>
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
        if (Yii::app()->hasModule('cart'))
            $this->widget('cart.widgets.addcart.AddcartWidget', array('data' => $data));
        ?>
        <?php
        if(Yii::app()->user->isSuperuser)
            echo Html::link('ред.',array('/shop/admin/products/update','id'=>$data->id),array('class'=>'btn btn-xsm btn-info'));
        ?>
        

        <?php
        if (Yii::app()->hasModule('compare'))
            $this->widget('compare.widgets.CompareWidget', array('pk' => $data->id));

        if (Yii::app()->hasModule('wishlist'))
            $this->widget('wishlist.widgets.WishlistWidget', array('pk' => $data->id));
        ?>

        <ul class="product-detail list-unstyled">
            <li><?php echo $data->short_description; ?></li>
        </ul>
    </div>
</div>
