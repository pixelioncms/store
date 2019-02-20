<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 product text-left">
    <div class="product-box">
        <div class="product-image-box">
            <div class="thumbnail">
            <?php

            $this->widget('mod.shop.widgets.productLabel.ProductLabelWidget',array('model'=>$data));
            ?>

            <?php
            $this->widget('ext.admin.frontControl.FrontControlWidget', array(
                'data' => $data,
                'widget' => $widget
            ));
            ?>

            <?php
            if ($data->mainImage) {
                $imgSource = $data->mainImage->getImageUrl('name', 'product', '240x240'); //
            } else {
                $imgSource = CMS::placeholderUrl(array('size'=>'240x240'));
            }
            echo Html::link(Html::image($imgSource, $data->mainImageTitle, array('class' => 'img-responsive', 'height' => 240)), $data->getUrl(), array('class' => ''));
            ?>
        </div>
</div>

<?php echo Html::link(Html::encode($data->name), $data->getUrl()) ?>

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
echo Html::form(array('/cart/add'), 'post', array('id' => 'form-add-cart-' . $data->id));
echo Html::hiddenField('product_id', $data->id);
echo Html::hiddenField('product_price', $data->price);
echo Html::hiddenField('use_configurations', $data->use_configurations);
echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
echo Html::hiddenField('currency_id', $data->currency_id);
echo Html::hiddenField('supplier_id', $data->supplier_id);
echo Html::hiddenField('pcs', $data->pcs);
echo Html::hiddenField('configurable_id', 0);
?>
<div class="text-center product-action">
    <div class="btn-group btn-group-sm">
        <?php
        if (Yii::app()->hasModule('compare')) {
            $this->widget('mod.compare.widgets.CompareWidget', array('pk' => $data->id));
        }
        if ($data->isAvailable) {
            echo Html::link(Yii::t('app', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-success'));
        } else {
            echo Html::link(Yii::t('app', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
        }
        ?>
    </div>
</div>
<?php
if ($this->model->getUserId() === Yii::app()->user->id) {
    echo Html::link(Yii::t('app', 'DELETE'), array('remove', 'id' => $data->id), array(
        'class' => 'btn btn-primary remove',
    ));
}else{
    echo 'no uiser';
}
echo Html::endForm();
?>
    </div>
</div>