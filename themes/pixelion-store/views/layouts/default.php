<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    </head>
    <body class="no-radius">
        <?php $this->renderPartial('//layouts/inc/_header'); ?>
        <div class="breadcrumb">
            <div class="container">
                <div class="breadcrumb-inner">
                    <?php
                    $this->widget('Breadcrumbs', array(
                        'homeLink' => '<li>' . Html::link(Yii::t('zii', 'Home'), array('/main/default/index')) . '</li>',
                        'links' => $this->breadcrumbs,
                        'htmlOptions' => array('class' => 'list-inline list-unstyled'),
                        'tagName' => 'ul',
                        'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
                        'inactiveLinkTemplate' => '<li class="active">{label}</li>',
                        'separator' => false
                    ));
                    ?>

                </div>
            </div><!-- /.container -->
        </div><!-- /.breadcrumb -->

            <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (Yii::app()->user->hasFlash('error')) {
                                Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'), false);
                            }
                            if (Yii::app()->user->hasFlash('success')) {
                                Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'), false);
                            }

                            ?>


                            <?= $content ?>
                        </div>
                    </div>

        </div>
        <?php $this->renderPartial('//layouts/inc/_footer'); ?>
    </body>
</html>
