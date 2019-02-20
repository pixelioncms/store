<?php
if ($model->appliedDiscount) {
    ?>
    <div class="label-product label-discount"><div>- <?= $model->discountSum ?></div></div>
<?php } ?>


<?php
if ($model->productLabel) {
    if ($model->productLabel['class'] == 'new') {
        $color = 'green';
    } elseif ($model->productLabel['class'] == 'hit') {
        $color = 'purple';
    } else {
        $color = 'blue';
    }
    ?>
    <div class="label-product label-new"><div><?php echo Yii::t('ShopModule.default', 'PRODUCT_LABEL',$model->label)?></div></div>
<?php
    
}


