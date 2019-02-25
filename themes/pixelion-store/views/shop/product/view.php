<?php


$config = Yii::app()->settings->get('shop');

$this->widget('ext.fancybox.Fancybox', array(
    'target' => '[data-fancybox=gallery]',

    'config' => array(
        'padding' => 0,
        'transitionIn' => 'none',
        'transitionOut' => 'none',
        'titlePosition' => 'over',
        'thumbs' => array(
            'autoStart' => true
        ),
        'buttons' => array(
            //"zoom",
            "share",
            "slideShow",
            //"fullScreen",
            //"download",
            "thumbs",
            "close"
        ),
        'infobar' => false,
        'preventCaptionOverlap' => true,
        'protect' => true,
    )
));
?>
<?php print_r($model->eav_box); ?>

<?php
if (strtotime($model->date_create) <= CMS::time() + 86400 * 7) { //неделя
    echo ' Новый 7 day';
}
if (Yii::app()->hasModule('discounts') && $model->appliedDiscount) {
    echo ' Скидка ' . $model->discountSum;
}
//Топ продаж
//1. Сделать крон задачу каторая будет обнулять параметр added_to_cart_count продаж каждый N days
//2. Считать к примеру от 10 продаж в месяц это уже считается топ

//Популярный
//1. Сделать крон задачу каторая будет обнулять параметр views просмотров каждый N days
//2. Считать к примеру от 100 просмотров в месяц это уже считается топ

Yii::app()->clientScript->registerScript('product-gallery', "
    $(function () {
        $('.thumb').click(function () {
            $('.thumb').removeClass('active');
            $(this).addClass('active');
            var src_bg = $(this).attr('href');
            var src_middle = $(this).attr('data-img');

            //set params main image
            $('#main-image').attr('href', src_bg);
            $('#main-image img').attr('src', src_middle);

            return false;
        });
    });
", CClientScript::POS_END);
?>

<div class="container">


    <a href="javascript:void(0)" title=""
       onclick="popUp = window.open('<?= $model->getUpdateUrl(array('lofiversion' => 1)) ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false;">
        <i class="icon-edit"></i>
    </a>
    <a href="javascript:void(0)" title=""
       onclick="popUp = window.open('<?= $model->getCreateUrl(array('lofiversion' => 1, 'ShopProduct[main_category_id]' => $model->mainCategory->id)) ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false;">
        <i class="icon-add"></i>
    </a>
    <div class="row">
        <div class="col-sm-6 col-md-5">


            <a id="main-image" href="<?= $model->getMainImageUrl() ?>"
               data-fancybox="gallery">
                <img class="img-fluid" src="<?= $model->getMainImageUrl('400x400') ?>"
                     alt="<?= $model->getMainImageTitle() ?>"/>
            </a>


            <?php
            if (isset($model->attachments)) { ?>


                <?php

                $defaultOptions = array(
                    'navText' => array('<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'),
                    'responsiveClass' => false,
                    'margin' => 0,
                    //'stagePadding'=>15,
                    'responsive' => array(
                        0 => array(
                            'items' => 1,
                            'nav' => false,
                            'dots' => true,
                            'center' => true,
                            'loop' => true,
                        ),
                        480 => array(
                            'items' => 2,
                            'nav' => false,
                            'dots' => true
                        ),
                        768 => array(
                            'items' => 2,
                            'nav' => false,
                            'dots' => true
                        ),
                        992 => array(
                            'items' => 2,
                            'nav' => false,
                            'dots' => true
                        ),
                        1200 => array(
                            'items' => 4,
                            'nav' => true,
                            'loop' => false,
                            'mouseDrag' => false,
                        )
                    )
                );
                $config = CJavaScript::encode($defaultOptions);
                $cs = Yii::app()->clientScript;
                $cs->registerCoreScript('owl.carousel');
                $cs->registerScript('owl-products-smile', "$('#owl-products-smile').owlCarousel($config);", CClientScript::POS_END);
                ?>


                <div id="owl-products-smile" class="owl-products-smile owl-carousel">
                    <?php foreach ($model->attachments as $k => $image) { ?>


                        <a href="<?= $image->getImageUrl() ?>"
                           data-fancybox="gallery"
                           data-caption="<?php echo Html::encode($model->name); ?>"
                           data-img="<?= $image->getImageUrl('400x400') ?>"
                           class="thumb">
                            <img class="img-fluid"
                                 src="<?= $image->getImageUrl('100x100') ?>"
                                 alt=""/>
                        </a>

                    <?php } ?>
                </div>
            <?php } ?>


        </div>


        <div class='col-sm-6 col-md-7 product-info-block'>
            <div class="product-info">
                <h1 class="name heading-gradient">

                    <?php if (Yii::app()->seo->h1) { ?>
                        <?= Yii::app()->seo->h1; ?>
                    <?php } else { ?>
                        <?= Html::encode($model->name); ?>
                    <?php } ?>
                </h1>

                <?php $this->widget('mod.discounts.widgets.countdown.CountdownWidget', array('model' => $model)) ?>

                <div class="m-t-20">
                    <div class="row">
                        <div class="col-sm-3">
                            <?php $this->widget('ext.rating.StarRating', array('model' => $model)); ?>

                        </div>
                        <div class="col-sm-8">
                            <div class="reviews">
                                <a href="<?= $model->getUrl() ?>#comments_tab"
                                   class="lnk">(<?= $model->commentsCount ?> <?= CMS::GetFormatWord('common', 'REVIEWS', $model->commentsCount) ?>
                                    )</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!$model->archive) {
                    echo $model->beginCartForm();
                    ?>
                    <div class="stock-container info-container m-t-10">
                        <?php
                        $this->renderPartial('_configurations', array('model' => $model));
                        ?>
                        <div class="row">

                            <?php if ($model->sku) { ?>
                                <div class="col-sm-3 mb-2"><?= $model->getAttributeLabel('sku') ?>:</div>
                                <div class="col-sm-9 mb-2"><?= Html::encode($model->sku); ?></div>
                            <?php } ?>
                            <?php if ($model->manufacturer) { ?>
                                <?php Yii::app()->clientScript->registerScript('popover.manufacturer', "$('.manufacturer-popover').popover({
                                    html: true,
                                    trigger: 'focus',
                                    content: function () {
                                        return $('#manufacturer-image').html();
                                        }
                                    });"); ?>
                                <div id="manufacturer-image" class="d-none">
                                    <?= Html::image($model->manufacturer->getImageUrl('image','300x300'), $model->manufacturer->name, array('class' => 'img-fluid')) ?>
                                    <?php
                                    if (!empty($model->manufacturer->description)) {
                                        echo $model->manufacturer->description;
                                    }
                                    echo Html::link(Html::encode($model->manufacturer->name), $model->manufacturer->getUrl(), array('class' => "btn btn-link"));
                                    ?>
                                </div>
                                <div class="col-sm-3 mb-2"><?= $model->getAttributeLabel('manufacturer_id') ?>:</div>
                                <div class="col-sm-9 mb-2"><?= Html::link(Html::encode($model->manufacturer->name), 'javascript:void(0)', array('title' => $model->getAttributeLabel('manufacturer_id'), 'class' => "manufacturer-popover")); ?></div>
                            <?php } ?>
                            <div class="col-sm-3 mb-2">Наличие:</div>
                            <div class="col-sm-9 mb-2">
                                <?php if ($model->availability == 1) { ?>
                                    <span class="text-success"><?= Yii::t('ShopModule.default', 'AVAILABILITY', $model->availability) ?></span>
                                <?php } elseif ($model->availability == 3) { ?>
                                    <span class="text-warning"><?= Yii::t('ShopModule.default', 'AVAILABILITY', $model->availability) ?></span>
                                <?php } else { ?>
                                    <span class="text-danger"><?= Yii::t('ShopModule.default', 'AVAILABILITY', $model->isAvailable) ?></span>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                    <div class="description-container">

                        <?= $model->short_description; ?>
                    </div>

                    <div class="price-container info-container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="price-box">
                                    <span class="price price-lg text-success"><span
                                                id="productPrice"><?= Yii::app()->currency->number_format($model->getFrontPrice()); ?></span> <sub><?= Yii::app()->currency->active->symbol; ?></sub></span>
                                    <?php
                                    if (Yii::app()->hasModule('discounts')) {
                                        if ($model->appliedDiscount) {
                                            ?>
                                            <span class="price-strike"><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($model->originalPrice)) ?> <?= Yii::app()->currency->active->symbol ?></span>

                                            <?php
                                        }
                                    }
                                    ?>

                                    <?php if($model->prices){ ?>
                                        <a class="btn btn-sm btn-link" data-toggle="collapse" href="#prices" role="button" aria-expanded="false" aria-controls="prices">
                                            Показать все оптовые цены
                                        </a>
                                       <div class="collapse" id="prices">
                                        <?php foreach($model->prices as $price){ ?>

                                            <div>
                                                <span class="price price-sm text-success">
                                                    <span><?= Yii::app()->currency->number_format(Yii::app()->currency->convert($price->value,$model->currency_id)); ?></span>
                                                    <sub><?= Yii::app()->currency->active->symbol; ?>/<?= $model->units[$model->unit]; ?></sub>
                                                    </span>
                                                при заказе от <?= $price->order_from; ?> <?= $model->units[$model->unit]; ?>
                                            </div>
                                       <?php } ?>
                                       </div>
                                   <?php } ?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="favorite-button">
                                    <?php
                                    if (Yii::app()->hasModule('compare')) {
                                        $this->widget('mod.compare.widgets.CompareWidget', array('pk' => $model->id));
                                    }
                                    echo '<br/>';
                                    if (Yii::app()->hasModule('wishlist') && !Yii::app()->user->isGuest) {
                                        $this->widget('mod.wishlist.widgets.WishlistWidget', array('pk' => $model->id));
                                    }
                                    ?>
                                </div>
                            </div>

                        </div><!-- /.row -->
                    </div><!-- /.price-container -->

                    <div class="quantity-container info-container">
                        <div class="row">

                            <div class="col-sm-4">
                                <?= Html::label('Quantity','quantity',array('class'=>'sr-only')); ?>
                                <?= Html::textField('quantity', 1, array('class' => 'spinner text-center  btn-group')); ?>
                            </div>

                            <div class="col-sm-8">


                                <?php
                                if ($model->isAvailable) {
                                    $this->widget('mod.cart.widgets.buyOneClick.BuyOneClickWidget', array('pk' => $model->id));
                                    Yii::import('mod.cart.CartModule');
                                    CartModule::registerAssets();
                                    echo Html::link('<i class="icon-shopcart inner-right-vs"></i>' . Yii::t('common', 'BUY'), 'javascript:cart.add(' . $model->id . ')', array('class' => 'btn btn-primary'));
                                } else {
                                    echo Html::link(Yii::t('common', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $model->id . ');', array('class' => 'btn btn-link'));
                                }
                                ?>
                            </div>
                        </div>
                    </div>


                    <?php
                    $this->widget('ext.share.ShareWidget', array(
                        'model' => $model,
                        'image' => $model->getMainImageUrl('original'),
                        'title' => $model->name
                    ));
                    ?>
                    <?php echo $model->endCartForm(); ?>
                <?php } else { ?>
                    <?= Yii::app()->tpl->alert('info', Yii::t('common', 'PRODUCT_ARCHIVE')); ?>
                <?php } ?>


                <div class="row product-info-ext-title">
                    <div class="col-12 col-md-4">
                        <div class="product-info-ext product-info-ext__payment">Удобные варианты оплаты</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="product-info-ext product-info-ext__delivery">Отправка по всей стране</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="product-info-ext product-info-ext__guarantee">Гарантия от магазина</div>
                    </div>
                </div>

            </div><!-- /.product-info -->

        </div><!-- /.col-sm-7 -->

    </div><!-- /.row -->
</div>
<div class="line-title"></div>
<div class="container">
    <div class="product-tabs">
        <div class="row">
            <div class="col-sm-12">


                <?php
                $tabs = array();
                if ($model->full_description) {
                    $tabs[Yii::t('ShopModule.default', 'TAB_DESC')] = array(
                        'id' => 'description_tab',
                        'content' => $model->full_description);
                }
                // EAV tab
                if ($model->getEavAttributes()) {
                    $tabs[Yii::t('ShopModule.default', 'TAB_ATTRIBUTES')] = array(
                        'id' => 'attributes',
                        'content' => $this->renderPartial('_attributes', array('model' => $model), true
                        ));
                }

                // Comments tab
                if (Yii::app()->hasModule('comments')) {
                    $tabs[Yii::t('ShopModule.default', 'TAB_COMMENTS', array('{num}' => $model->commentsCount))] = array(
                        'id' => 'comments_tab',
                        //'content' => $this->renderPartial('_comments', array('model' => $model), true));
                        'content' => $this->widget('mod.comments.widgets.comment.CommentWidget', array(
                                // 'skin'=>'current_theme.views.layouts.inc._comments',
                                'model' => $model)
                            , true)
                    );
                }
                if (!empty($model->video)) {
                    $tabs[$model::t('TAB_VIDEO')] = array(
                        'id' => 'video',
                        'content' => $this->renderPartial('_video', array('model' => $model), true
                        ));
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
                    'ulClass' => 'nav2 nav-tabs2 nav-tab-cell2',
                    'tabs' => $tabs,
                    'options' => array()
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


</div>



<?php
$this->widget('mod.shop.widgets.sessionView.SessionViewWidget',array('current_id'=>$model->id));
?>


