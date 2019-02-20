<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?=Yii::app()->language?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body class="no-radius">
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






                    </div>

                </div>

            </div>
        <?= $content ?>

        <?php $this->widget('mod.shop.widgets.brands.BrandsWidget', array('skin' => 'current_theme.views.layouts.inc._brands')); ?>


        <?php $this->renderPartial('//layouts/inc/_footer'); ?>

    </body>
</html>