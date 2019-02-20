<?php
$min = YII_DEBUG ? '' : '.min';

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('bootstrap');
$cs->registerCss('maintenance-default',"
            body {
                background-color: #eee;
            }
            .panel {
                max-width: 500px;
                margin: 0 auto;
            }
");
$cs->registerScript('maintenance-default',"
            function panel_height() {
                var height = $(window).height();
                var panel_height = $('.panel').height();
                $('.panel').css({'margin-top': height / 2 - panel_height / 2});
            }
            $(window).ready(function () {
                panel_height();
            });
            $(window).resize(function () {
                panel_height();
            });
");
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <title><?= Html::encode($title) ?></title>
    </head>
    <body>
        <div class="card panel-default">
            <div class="card-header">
                <div class="card-title text-center"><?= Html::encode(mb_strtoupper($title, Yii::app()->charset)) ?></div>
            </div>
            <div class="card-body">
                <?= $content; ?>
            </div>
        </div>
    </body>
</html>