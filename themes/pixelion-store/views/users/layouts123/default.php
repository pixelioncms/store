<?php $this->renderPartial('//layouts/inc/_cs'); ?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->pageTitle) ?></title>
    </head>
    <body>
        <?php $this->renderPartial('//layouts/inc/_header'); ?>
        <div class="body-content outer-top-bd">
            <div class="container">
                <div class="inner-bottom-sm">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->renderPartial('//layouts/inc/_footer'); ?>
    </body>
</html>
