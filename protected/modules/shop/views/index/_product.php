
<div class="product floatleft width25">
    <div class="spacer">
        <div class="cat-image">
            <a class="modal" href=""><img src="<?php echo $data->getMainImageUrl() ?>" alt="img-leo-tools-14" class="featuredProductImage" border="0" /></a>                    <h3>
                <a href="#" title="Fermentum">Fermentum</a>				</h3>
            <div class="cat-view">	
                <a title="img-leo-tools-14" class="modal" href="img-leo-tools-14.jpg"><img src="<?php echo $imgSource ?>" alt="img-leo-tools-14" class="browseProductImage" border="0" title="Fermentum"  /></a>                       </div>
            <div class="catProductPrice">
                <span class="label">Price:</span><div class="PricesalesPrice" style="display : block;" ><span class="PricesalesPrice" >$245.00</span></div><div class="PricetaxAmount" style="display : block;" >Tax amount: <span class="PricetaxAmount" >$5.00</span></div><div class="PricediscountAmount" style="display : none;" >Discount: <span class="PricediscountAmount" ></span></div>				</div>
        </div>
        <div class="cat-info">

            <p class="product_s_desc">
                Lorem ipsum dolor sit amet, consectetur...				</p>
        </div>


        <div class="cat-cart">                 

            <div class="addtocart-bar">

                <!-- <label for="quantity" class="quantity_box">Quantity: </label> -->
                <span class="quantity-box">
                    <input type="text" class="quantity-input js-recalculate" name="quantity[]" value="1" />
                </span>
                <span class="quantity-controls js-recalculate">
                    <input type="button" class="quantity-controls quantity-plus" />
                    <input type="button" class="quantity-controls quantity-minus" />
                </span>

                <span class="addtocart-button">
                    <input type="submit" name="addtocart"  class="addtocart-button" value="Add to Cart" title="Add to Cart" />
                </span>

                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>




<div class="product_block" style="display: none">

    <div class="name">
        <?php echo CHtml::link(CHtml::encode($data->name), $data->getUrl()) ?>
    </div>
    <div class="price">
        <?php
        if ($data->appliedDiscount)
            echo '<span style="color:red; "><s>' . $data->toCurrentCurrency('originalPrice') . '</s></span>';
        ?>
        <?php echo $data->priceRange() ?>
    </div>
    <div class="actions">
        <?php
        echo CHtml::form(array('/orders/cart/add'));
        echo CHtml::hiddenField('product_id', $data->id);
        echo CHtml::hiddenField('product_price', $data->price);
        echo CHtml::hiddenField('use_configurations', $data->use_configurations);
        echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
        echo CHtml::hiddenField('configurable_id', 0);
        echo CHtml::hiddenField('quantity', 1);

        if ($data->getIsAvailable()) {
            echo CHtml::ajaxSubmitButton(Yii::t('ShopModule.default', 'Купить'), array('/orders/cart/add'), array(
                'id' => 'addProduct' . $data->id,
                'dataType' => 'json',
                'success' => 'js:function(data, textStatus, jqXHR){processCartResponseFromList(data, textStatus, jqXHR, "' . Yii::app()->createAbsoluteUrl('/shop/Product/view', array('url' => $data->url)) . '")}',
                    ), array('class' => 'blue_button'));
        } else {
            echo CHtml::link('Нет в наличии', '#', array(
                'onclick' => 'showNotifierPopup(' . $data->id . '); return false;',
                'class' => 'notify_link',
            ));
        }
        ?>
        <button class="small_silver_button" title="<?= Yii::t('app', 'Сравнить') ?>" onclick="return addProductToCompare(<?php echo $data->id ?>);"><span class="compare">&nbsp</span></button>
        <button class="small_silver_button" title="<?= Yii::t('app', 'В список желаний') ?>" onclick="return addProductToWishList(<?php echo $data->id ?>);"><span class="heart">&nbsp;</span></button>
        <?php echo CHtml::endForm() ?>
    </div>
</div>