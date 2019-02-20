<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?=Yii::app()->language?>">
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

            <div class="container">
                <div class="row">

                    <div class="col-sm-12">


                            <?php
                            $this->widget('Breadcrumbs', array(
                                'homeLink' => '<li>' . Html::link(Yii::t('zii', 'Home'), array('/main/default/index')) . '</li>',
                                'links' => $this->breadcrumbs,
                                'htmlOptions' => array('class' => 'breadcrumb'),
                                'tagName' => 'ul',
                                'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
                                'inactiveLinkTemplate' => '<li class="active">{label}</li>',
                                'separator' => false
                            ));
                            ?>




                        <?= $content ?>

                    </div>

                </div>
                <?php $this->widget('mod.shop.widgets.brands.BrandsWidget', array('skin' => 'current_theme.views.layouts.inc._brands')); ?>

            </div>




        <?php $this->renderPartial('//layouts/inc/_footer'); ?>

    </body>
</html>