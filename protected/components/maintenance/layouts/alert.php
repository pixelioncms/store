<?php
$min = YII_DEBUG ? '' : '.min';

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('bootstrap');
$cs->registerCss('maintenance-alert',"
        body {
            background-color: #eee;
            margin: 0;
            padding: 0;
        }

        .alert {
            max-width: 500px;
            margin: 0 auto;
            padding-left: 3rem;
            position: relative;
            border-radius: 0;
        }

        .alert-info:before,
        .alert-success:before,
        .alert-danger:before,
        .alert-warning:before {
            font-family: Pixelion;
            font-style: normal;
            line-height: 1;
            left: 1rem;
            top: 1rem;
            position: absolute;
            font-size: 22px;
        }

        .alert-danger:before,
        .alert-warning:before {
            content: '".Html::decode('\f053')."';
        }

        .alert-success:before {
            content:'".Html::decode('\f053')."';
        }

        .alert-info:before {
            content: '".Html::decode('\f054')."';
        }
");
$cs->registerScript('maintenance-alert',"
    function height() {
        var height = $(window).height();
        var aheight = $('.alert').height();
        $('.alert').css({'margin-top': height / 2 - aheight});
    }
    $(window).ready(function () {
        height();
    });
    $(window).resize(function () {
        height();
    });
");
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title><?= Html::encode(Yii::app()->settings->get('app', 'site_name')) ?></title>
</head>
<body>
<div class="container">
    <div class="alert alert-danger">
        <?= $content; ?>
    </div>
</div>
</body>
</html>