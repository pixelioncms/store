<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if (Yii::app()->hasModule('seo')) { ?>
        <?php //Yii::app()->seo->run(); ?>
    <?php } else { ?>
        <title><?= Html::encode($this->pageTitle) ?></title>
    <?php } ?>
</head>
<body class="no-radius">

<?php $this->renderPartial('//layouts/inc/_header'); ?>


<div class="container">

    <?php
    $this->widget('Breadcrumbs', array(
        'links' => $this->breadcrumbs,
        'htmlOptions' => array('class' => 'breadcrumb'),
        'separator' => false
    ));
    ?>

</div>


<div class="container-fluid">
    <div class="row">
        <?= $content ?>


        <?php $this->widget('mod.shop.widgets.brands.BrandsWidget', array('skin' => 'current_theme.views.layouts.inc._brands')); ?>
    </div>
</div>


<?php if (!empty(Yii::app()->seo->data->text)) { ?>
    <h1><?= Yii::app()->seo->data->h1; ?></h1>

    <?= Yii::app()->seo->data->text; ?>


<?php } ?>

<?php $this->renderPartial('//layouts/inc/_footer'); ?>

</body>
</html>