<div id="hero">
    <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">
        <?php foreach ($model as $banner) { ?>
            <div class="item" style="background-image: url(<?= $banner->getImageUrl('image','870x370')?>);">
                
                <div class="container-fluid">
                    <div class="caption bg-color vertical-center text-left">
                        <?=$banner->content ?>

                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


