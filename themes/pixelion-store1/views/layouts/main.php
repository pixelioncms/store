<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <?php if (Yii::app()->hasModule('seo')) { ?>
        <?php Yii::app()->seo->run(); ?>
    <?php } else { ?>
        <title><?= Html::encode($this->pageTitle) ?></title>
    <?php } ?>
</head>
<body>

<?php $this->renderPartial('//layouts/inc/_header'); ?>
<?php

$this->widget('mod.banner.widgets.slider.SliderWidget');

if (Yii::app()->params['demo']) {
    Yii::app()->tpl->alert('info', Yii::t('app', 'DEMO_MESSAGE'), true);
}
?>
<?php $this->widget('mod.shop.widgets.brands.BrandsWidget'); ?>
<?php $this->renderPartial('//layouts/inc/_footer'); ?>

</body>
</html>