
<div class="row">
    <?php
    $totalProducts = 0;
    foreach (ShopCategory::model()->findByPk(1)->children()->published()->findAll() as $cat) {
        $totalProducts = $cat->countProducts;
        ?>
        <div class="col-sm-3 col-md-4">
            <div class="row">
                <div class="col-md-6 col-sm-6 text-left">
                    <?php
                    if ($cat->getImageUrl('image', 'categories', '235x320')) {
                        $imgSource = $cat->getImageUrl('image', 'categories', '235x320'); //
                    } else {
                        $imgSource = CMS::placeholderUrl(array('size'=>'235x320'));
                    }
                    echo Html::link(Html::image($imgSource, $cat->name, array('class' => 'img-responsive', 'height' => 240)), $cat->getUrl(), array('class' => 'thumbnail'));
                    ?>
                </div>
                <div class="col-md-6 col-sm-6 text-left">
                    <b><?= Html::link($cat->name, $cat->getUrl()) ?></b>
                    <ul class="list-unstyled">
                        <?php
                        foreach ($cat->children()->published()->findAll() as $subcat) {
                            $totalProducts +=$subcat->countProducts;
                            ?>
                            <li><?= Html::link($subcat->name . ' (' . $subcat->countProducts . ')', $subcat->getUrl()); ?></li>
                        <?php } ?>
                    </ul>

                </div>
            </div>
        </div>
    <?php } ?>

</div>
