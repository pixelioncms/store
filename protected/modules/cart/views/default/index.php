<?php
$cs = Yii::app()->clientScript;
//$cs->registerScriptFile($this->module->assetsUrl . '/cart.js', CClientScript::POS_END);
$cs->registerScript('cart', "
//cart.selectorTotal = '#total';
var orderTotalPrice = '$totalPrice';

", CClientScript::POS_HEAD);
?>
<script>
    /*    $(function () {
     
     
     
     
     $('.payment_checkbox').click(function () {
     $('#payment').text($(this).attr('data-value'));
     });
     $('.delivery_checkbox').click(function () {
     $('#delivery').text($(this).attr('data-value'));
     
     });
     hasChecked('.payment_checkbox', '#payment');
     hasChecked('.delivery_checkbox', '#delivery');
     });
     
     function hasChecked(selector, div) {
     $(selector).each(function (k, i) {
     var inp = $(i).attr('checked');
     if (inp == 'checked') {
     $(div).text($(this).attr('data-value'))
     }
     });
     }*/
    function submitform() {
        if (document.cartform.onsubmit &&
                !document.cartform.onsubmit())
        {
            return;
        }
        document.cartform.submit();
    }
</script>


<div class="col-xs-12">
    <?php
    if (empty($items)) {
        echo Html::openTag('div', array('id' => 'container-cart', 'class' => 'indent'));
        echo Html::openTag('h1');
        echo Yii::t('CartModule.default', 'CART_EMPTY');
        echo Html::closeTag('h1');
        echo Html::closeTag('div');
        return;
    }
    $this->widget('ext.fancybox.Fancybox', array('target' => 'a.thumbnail'));
    ?>


    <h1><?= $this->pageName ?></h1>
    <div id="cart-left">
        <?php echo Html::form(array('/cart/'), 'post', array('id' => 'cart-form', 'name' => 'cartform')) ?>
        <div class="table-responsive">
            <table id="cart-table" class="table table-striped table-condensed" width="100%" border="0" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th></th>
                        <th>Товар</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $index => $product) { ?>
                        <?php
                        // print_r($product);die;
                        $price = ShopProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);
                        ?>
                        <tr id="product-<?= $index ?>">
                            <td width="110px" align="center">
                                <?php
                                // Display image

                                echo Html::image($product['model']->getMainImageUrl('100x100'), '', array('class' => 'thumbnail img-responsive'));
                                ?>
                            </td>
                            <td>
                                <?php
                                // Display product name with its variants and configurations
                                echo Html::link(Html::encode($product['model']->name), array('/shop/product/view', 'seo_alias' => $product['model']->seo_alias));
                                ?>

                                <br/> <?php
                                // Price

                                echo Html::openTag('span', array('class' => 'price'));
                                echo Yii::app()->currency->number_format(Yii::app()->currency->convert($price));
                                echo ' ' . Yii::app()->currency->active->symbol;
                                //echo ' '.($product['currency_id']) ? Yii::app()->currency->getSymbol($product['currency_id']) : Yii::app()->currency->active->symbol;
                                echo Html::closeTag('span');

                                // Display variant options
                                if (!empty($product['variant_models'])) {
                                    echo Html::openTag('span', array('class' => 'cartProductOptions'));
                                    foreach ($product['variant_models'] as $variant)
                                        echo ' - ' . $variant->attribute->title . ': ' . $variant->option->value . '<br/>';
                                    echo Html::closeTag('span');
                                }

                                // Display configurable options
                                if (isset($product['configurable_model'])) {
                                    $attributeModels = ShopAttribute::model()->findAllByPk($product['model']->configurable_attributes);
                                    echo Html::openTag('span', array('class' => 'cartProductOptions'));
                                    foreach ($attributeModels as $attribute) {
                                        $method = 'eav_' . $attribute->name;
                                        echo ' - ' . $attribute->title . ': ' . $product['configurable_model']->$method . '<br/>';
                                    }
                                    echo Html::closeTag('span');
                                }
                                ?>
                            </td>
                            <td>

                                <?php echo Html::textField("quantities[$index]", $product['quantity'], array('class' => 'spinner btn-group', 'product_id' => $index)) ?>

                            </td>
                            <td id="price-<?= $index ?>">
                                <span class="price">
                                    <span class="cart-total-product" id="row-total-price<?= $index ?>">
                                        <?php
                                        echo Yii::app()->currency->number_format(Yii::app()->currency->convert($price * $product['quantity']));
                                        ?>
                                    </span>
                                    <sup><?= Yii::app()->currency->active->symbol; ?></sup>
                                </span>
                                <?php
                                //  echo Html::openTag('span', array('class' => 'price cart-total-product', 'id' => 'row-total-price' . $index));
                                //  echo Html::closeTag('span');
                                //echo $convertTotalPrice;// echo Yii::app()->currency->number_format(Yii::app()->currency->convert($convertPrice, $product['currency_id']));
                                //  echo ' ' . Yii::app()->currency->active->symbol;
                                //echo ' '.($product['currency_id'])? Yii::app()->currency->getSymbol($product['currency_id']): Yii::app()->currency->active->symbol;
                                ?>
                            </td>
                            <td style="vertical-align:middle;" width="50px" class="text-center">
                                <?= Html::link('х', array('/cart/remove', 'id' => $index), array('class' => 'btn btn-danger btn-xs remove')) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>


        </div>
        <?php
        Yii::app()->tpl->alert('info', Yii::t('CartModule.default', 'ALERT_CART'))
        ?>
        <div class="container-fluid">
            <div class="row">
            <div class="col-md-4">

                <?php
                $this->renderPartial('_fields_user', array('form' => $this->form));
                ?>
            </div>
            <div class="col-md-4">


                <?php
                $this->renderPartial('_fields_delivery', array(
                    'form' => $this->form,
                    'deliveryMethods' => $deliveryMethods)
                );
                $this->renderPartial('_fields_payment', array(
                    'form' => $this->form,
                    'paymenyMethods' => $paymenyMethods)
                );
                ?>





            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title"><?= Yii::t('CartModule.default', 'PAYMENT_METHODS'); ?></div>
                    </div>
                    <div class="panel-body">
                        <div id="cart-check" class="text-center">
                            <div>Сумма заказа</div>
                            <div><span class="price"><span id="total"><?= Yii::app()->currency->number_format($totalPrice) ?></span> <i><?php echo Yii::app()->currency->active->symbol; ?></i></span></div>
                            <div style="margin-top:40px"><?= Yii::t('CartModule.default', 'PAYMENT'); ?>:</div>
                            <div id="payment">---</div>
                            <div><?= Yii::t('CartModule.default', 'DELIVERY'); ?>:</div>
                            <div id="delivery">---</div>
                            <a href="javascript:submitform();" class="btn btn-lg btn-success btn-block"><?= Yii::t('CartModule.default', 'BUTTON_CHECKOUT'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
             </div>
    </div>
    <input class="button btn-green" type="hidden" name="create" value="1">
    <?php echo Html::endForm() ?>
</div>
</div>