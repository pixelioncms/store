<?php
$assetsUrl = Yii::app()->getModule('admin')->assetsUrl;
$asm = $this->module->adminSidebarMenu;

$this->renderPartial('mod.admin.views.layouts.inc._cs', array(
    'assetsUrl' => $assetsUrl,
    'baseAssetsUrl' => $this->baseAssetsUrl
));
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
<head>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="img/png" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">


    <meta charset="<?= Yii::app()->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('app', 'site_name'))) ?></title>
    <link rel="shortcut icon" href="<?= $assetsUrl; ?>/images/favicon.ico" type="image/x-icon">
</head>
<body class="no-radius lofiversion">

<div id="wrapper-tpl">
    <div id="wrapper" class="full-page">
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-sm-12">
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

        </div>

    </div>
    <?php
    if (($messages = Yii::app()->user->getFlash('messages'))) {
        echo '<script type="text/javascript">';
        foreach ($messages as $m) {
            echo "common.notify('" . $m . "', 'success');";
        }
        echo '</script>';
    }

    if (($messages = Yii::app()->user->getFlash('notify'))) {

        echo '<script type="text/javascript">';
        foreach ($messages as $type => $errors) {
            if (is_array($errors)) {
                foreach ($errors as $err) {
                    echo "common.notify('{$err}', '{$type}');";
                }
            } else {
                echo "common.notify('{$errors}', '{$type}');";
            }
        }
        echo '</script>';
    }
    ?>
    <footer class="footer">
            {copyright}
    </footer>
</div>
</body>
</html>
