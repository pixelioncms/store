<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body class="no-radius">

<?php $this->renderPartial('//layouts/inc/_header'); ?>




<div class="container">
    <div class="carousel-brands">
    <?php $this->widget('mod.shop.widgets.brands.BrandsWidget'); ?>
    </div>
</div>

<?php

$this->widget('mod.banner.widgets.slider.SliderWidget');

if (Yii::app()->params['demo']) {
    Yii::app()->tpl->alert('info', Yii::t('app', 'DEMO_MESSAGE'), true);
}
?>


<?php $this->renderPartial('//layouts/inc/_footer'); ?>

</body>
</html>