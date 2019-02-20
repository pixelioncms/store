<?php

/**
 * Display related product
 * @var ShopProduct $model
 */
?>
<div class="products_list">
	<?php foreach($model->relatedProducts as $data):  ?>










<div class="product">
    <div class="product-box clearfix">

        <div class="clearfix gtile-i-150-minheight">
<?php
if ($data->mainImage) {
    $imgSource = $data->mainImage->getUrl(Yii::app()->settings->get('shop', 'img_preview_size_list')); //
} else {
    $imgSource = CMS::placeholderUrl(array('size'=>Yii::app()->settings->get('shop', 'img_preview_size_list')));
}
echo CHtml::link(CHtml::image($imgSource, $data->mainImageTitle), array('product/view', 'seo_alias' => $data->seo_alias), array('class' => 'thumbnail'));
?>
        </div>


        <div class="product-title">
            <?php echo CHtml::link(CHtml::encode($data->name), array('product/view', 'seo_alias' => $data->seo_alias)) ?>
        </div>

            <div class="product-price">
            <span class="price">
                    <?php
if(Yii::app()->hasModule('discounts')){
    if ($data->appliedDiscount){
    echo '<span style="color:red; "><s>' . $data->toCurrentCurrency('originalPrice') . '</s></span>';
    }
}
?>
                    <?= $data->priceRange() ?></span>
            <sup><?= Yii::app()->currency->active->symbol ?></sup>
            </div>

<?php
echo Html::form(array('/cart/add'), 'post', array('class'=>'product-form','id' => 'form-add-cart' . $data->id));
echo Html::hiddenField('product_id', $data->id);
echo Html::hiddenField('product_price', $data->price);
echo Html::hiddenField('use_configurations', $data->use_configurations);
echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
echo Html::hiddenField('currency_id', $data->currency_id);
echo Html::hiddenField('supplier_id', $data->supplier_id);
echo Html::hiddenField('configurable_id', 0);
echo Html::button(Yii::t('ShopModule.default', 'BUY'), array('class'=>'button btn-green cart','onClick'=>'shop.addCart(' . $data->id . ')'));
//echo Html::link(Yii::t('ShopModule.default', 'BUY'), 'javascript:addCart(' . $data->id . ')', array('class' => 'button btn-green cart'));
echo Html::endForm();
?>


 


        <a class="add-compare" href="javascript:shop.addCompare(<?= $data->id?>);">К сравнению</a>
        <a class="add-wishlist" href="javascript:shop.addWishlist(<?= $data->id?>);">В список желаний</a>

        <ul class="product-detail">
            <li><?php echo $data->short_description; ?></li>
        </ul>

    </div>
</div>

	<?php endforeach; ?>
</div>


<div style="clear: both;"></div>