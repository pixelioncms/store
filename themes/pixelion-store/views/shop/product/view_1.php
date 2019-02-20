
<?php
$config = Yii::app()->settings->get('shop');

$this->widget('ext.fancybox.Fancybox', array(
    'target' => 'a.thumbnail',
));
?>


<div class="container-fluid">
    <div class="row">

        <div class="col-md-5">
            <?php
            echo Html::link(Html::image($model->getMainImageUrl('240x240'), $model->mainImageTitle, array('class' => 'img-responsive', 'height' => 240)), $model->getUrl(), array('class' => 'thumbnail'));
            ?>

            <?php if (isset($model->imagesNoMain)) { ?>
                <div class="row">
                    <?php
                    foreach ($model->imagesNoMain as $image) {
                        echo Html::openTag('div', array('class' => 'col-md-3'));
                        echo Html::link(Html::image($image->getImageUrl('name', 'product', '100x100'), $image->title), $image->getImageUrl('name', 'product', '500x500'), array('class' => 'thumbnail2', 'rel' => 'gallery'));
                        echo Html::closeTag('div');
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-7">
            <h3><?php echo Html::encode($model->name); ?></h3>
            <?php if($model->sku) { ?><p>Аритукул <?php echo Html::encode($model->sku); ?></p><?php } ?>
            <?php if($model->manufacturer) { ?><p>Производитель <?php echo Html::link(Html::encode($model->manufacturer->name),$model->manufacturer->getUrl()); ?></p><?php } ?>
            <?php
            $this->renderPartial('_configurations', array('model' => $model));
            ?>
            <div class="price">
                <span id="productPrice">
                    <?= Yii::app()->currency->number_format($model->getFrontPrice()); ?>
                </span>

                <?= Yii::app()->currency->active->symbol; ?>
            </div>   
            <?php
            if (Yii::app()->hasModule('discounts')) {
                if ($model->appliedDiscount) {
                    ?>
                    <div class="product-price clearfix product-price-discount"><span><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($model->originalPrice)) ?></span><sup><?= Yii::app()->currency->active->symbol ?></sup></div>
                    <?php
                }
            }
            ?>
            <div class="actions row">
                <div class="col-md-5">
                    <?php
                    if(!$model->archive){
                    echo Html::form(array('/cart/add'), 'post', array('id' => 'form-add-cart-' . $model->id));
                    echo Html::hiddenField('product_id', $model->id);
                    echo Html::hiddenField('product_price', $model->price);
                    echo Html::hiddenField('use_configurations', $model->use_configurations);
                    echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
                    echo Html::hiddenField('currency_id', $model->currency_id);
                    echo Html::hiddenField('supplier_id', $model->supplier_id);
                    echo Html::hiddenField('configurable_id', 0);
                    ?>
                    <?= Html::textField('quantity', 1, array('class' => 'spinner text-center')); ?>
                    <div class="text-center product-action">
                        <div class="btn-group btn-group-sm">
                            <?php
                            if (Yii::app()->hasModule('compare')) {
                                $this->widget('mod.compare.widgets.CompareWidget', array('pk' => $model->id));
                            }
                            if (Yii::app()->hasModule('wishlist') && !Yii::app()->user->isGuest) {
                                $this->widget('mod.wishlist.widgets.WishlistWidget', array('pk' => $model->id));
                            }
                            $this->widget('mod.cart.widgets.buyOneClick.BuyOneClickWidget', array('pk' => $model->id));
                            if ($model->isAvailable) {
                                Yii::import('mod.cart.CartModule');
                                CartModule::registerAssets();
                                echo Html::link(Yii::t('CartModule.default', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $model->id . '")', array('class' => 'btn btn-success'));
                            } else {
                                echo Html::link(Yii::t('CartModule.default', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $model->id . ');', array('class' => 'btn btn-link'));
                            }
                            ?>
                        </div>
                    </div>
                    <?php echo Html::endForm(); ?>
                    <?php }else{ ?>
                    <?=Yii::app()->tpl->alert('info','Снят с производства.'); ?>
                    <?php } ?>
                </div>


            </div>


            <?= $model->short_description; ?>
        </div>






        <div class="col-md-12">


            <?php
            $tabs = array();
            if($model->full_description){
            $tabs[Yii::t('ShopModule.default', 'TAB_DESC')] = array(
                'id' => 'description_tab',
                'content' => $model->full_description);
            }
// EAV tab
            if ($model->getEavAttributes()) {
                $tabs[Yii::t('ShopModule.default', 'TAB_ATTRIBUTES')] = array(
                    'id'=>'attributes',
                    'content' => $this->renderPartial('_attributes', array('model' => $model), true
                ));
            }

// Comments tab
            if (Yii::app()->hasModule('comments')) {
                $tabs[Yii::t('ShopModule.default', 'TAB_COMMENTS', array('{num}' => $model->commentsCount))] = array(
                    'id' => 'comments_tab',
                    'content' => $this->renderPartial('_comments', array('model' => $model), true));
            }
// Related products tab
            if ($model->relatedProductCount) {
                $tabs[Yii::t('ShopModule.default', 'TAB_RELATED_PRODUCTS') . ' (' . $model->relatedProductCount . ')'] = array(
                    'id' => 'related_products',
                    'content' => $this->renderPartial('_related', array(
                        'model' => $model,
                            ), true));
            }

// Render tabs
            $this->widget('app.jui.JuiTabs', array(
                'id' => 'tabs',
                'ulClass' => 'nav1 nav-tabs1',
                'tabs' => $tabs,
                'options'=>array()
            ));

// Fix tabs opening by anchor
            Yii::app()->clientScript->registerScript('tabSelector', '
			$(function() {
				var anchor = $(document).attr("location").hash;
				var result = $("#tabs").find(anchor).parents(".ui-tabs-panel");
				if($(result).length){
					$("#tabs").tabs("select", "#"+$(result).attr("id"));
				}
			});
		');
            ?>




        </div>
    </div>
</div>









