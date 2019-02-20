<div class="product-box">

    <div class="browseBlock">
    


    </div>
    <div class="fleft">
        <div class="Title">
            <?php echo CHtml::link(CHtml::encode($data->name), '/product/'.$data->seo_alias) ?>

        </div>


        <div class="Price">
            <?php
            if ($data->appliedDiscount)
                echo '<span style="color:red; "><s>' . $data->toCurrentCurrency('originalPrice') . '</s></span>';
            ?>
            <?php echo $data->priceRange() ?>
        </div>

        <div class="buts">
            <span class="item-more">
                   <?php echo CHtml::link('подробнее', array('product/view', 'seo_alias' => $data->seo_alias)) ?>
            </span>
            <span class="item-do">
                <?php
                echo CHtml::form(array('/cart/add'));
                echo CHtml::hiddenField('product_id', $data->id);
                echo CHtml::hiddenField('product_price', $data->price);
                echo CHtml::hiddenField('use_configurations', $data->use_configurations);
                echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
                echo CHtml::hiddenField('currency_id', $data->currency_id);
                echo CHtml::hiddenField('supplier_id', $data->supplier_id);
                echo CHtml::hiddenField('configurable_id', 0);
                ?>
                <span class="qntf">
                    <?php echo CHtml::textField('quantity', 1, array('id' => 'spinner', 'class' => 'spinner qnt')); ?>
                </span> 	
                <span class="right">
                    <?php
                    if ($data->getIsAvailable()) {
                        echo CHtml::ajaxSubmitButton(Yii::t('ShopModule.core', 'Купить'), array('/cart/add'), array(
                            'id' => 'addProduct' . $data->id,
                            'dataType' => 'json',
                            'success' => 'js:function(data, textStatus, jqXHR){processCartResponseFromList(data, textStatus, jqXHR, "' . Yii::app()->createAbsoluteUrl('/shop/product/view', array('seo_alias' => $data->seo_alias)) . '")}',
                                ), array('class' => 'in-da-cart'));
                    } else {
                        echo CHtml::link('Нет в наличии', '#', array(
                            'onclick' => 'showNotifierPopup(' . $data->id . '); return false;',
                            'class' => 'notify_link',
                        ));
                    }
                    ?>

                </span>
                    <?php echo CHtml::endForm() ?>
                	<!--<button class="small_silver_button" title="<?=Yii::t('core','Сравнить')?>" onclick="return addProductToCompare(<?php echo $data->id ?>);"><span class="compare">&nbsp</span></button>
			<button class="small_silver_button" title="<?=Yii::t('core','В список желаний')?>" onclick="return addProductToWishList(<?php echo $data->id ?>);"><span class="heart">&nbsp;</span></button>-->
            </span>
        </div>
    </div>	
</div>
