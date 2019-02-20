<?php
$theme = Yii::app()->theme->name;
$min = YII_DEBUG ? '' : '.min';
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('bootstrap');
$cs->registerCssFile($this->assetsUrl . "/css/theme.css");
if (file_exists(Yii::getPathOfAlias("webroot.themes.{$theme}.assets.css") . DS . 'print.css')) {
    $cs->registerCssFile($this->assetsUrl . '/css/print.css');
}
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    </head>
    <body class="print">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <a href="/" class="logo"><?= Yii::app()->settings->get('app', 'site_name') ?></a>
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        <div>+3 (012) 345 67 89</div>
                        <div>+3 (012) 345 67 89</div>
                        <div>г. Одесса, ул. М. Арнаутская 36</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="content">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
