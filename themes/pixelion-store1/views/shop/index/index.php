
<div class="row">
    <?php
    $totalProducts = 0;
    foreach (ShopCategory::model()->findByPk(1)->children()->published()->findAll() as $cat) {
        $totalProducts = $cat->countProducts;
        ?>
        <div class="col-md-4 col-sm-6">
            <div class="row">
                <div class="col-md-6 col-sm-6 text-left">
                    <?php

                       $imgSource = $cat->getImageUrl('235x320');

                    echo Html::link(Html::image($imgSource, $cat->name, array('class' => 'img-fluid')), $cat->getUrl(), array('class' => 'thumbnail'));
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