<?php $this->renderPartial('current_theme.views.layouts.inc._cs'); ?>
<?php
Yii::app()->clientScript->registerScript('app_shop', "
cart.spinnerRecount = false;
", CClientScript::POS_HEAD);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php if (Yii::app()->hasModule('seo')) { ?>
            <?php Yii::app()->seo->run(Yii::app()->language); ?>
        <?php } else { ?>
            <title><?= Html::encode($this->pageTitle) ?></title>
        <?php } ?>
    </head>
    <body class="page-<?= Yii::app()->controller->module->id ?>">
        <?php $this->renderPartial('//layouts/inc/_header'); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    $this->widget('Breadcrumbs', array(
                        'homeLink' => '<li>' . Html::link(Yii::t('zii', 'Home'), array('/main/default/index')) . '</li>',
                        'links' => $this->breadcrumbs,
                        'htmlOptions' => array('class' => 'breadcrumb'),
                        'tagName' => 'ul',
                        'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
                        'inactiveLinkTemplate' => '<li class="active"><span>{label}</span></li>',
                        'separator' => false
                    ));
                    ?>
                    </div>
                    <?= $content ?>

            </div>
        </div>


        <?php $this->renderPartial('//layouts/inc/_footer',array()); ?>

    </body>
</html>
