<!doctype html>
<html>
<head>
    <meta charset="<?= Yii::app()->charset ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::app()->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title><?= Yii::t('InstallModule.default', 'TITLE_PAGE'); ?> <?= Yii::app()->name; ?></title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>
<body class="no-radius">
<script>
    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text.toLowerCase() + '_';
    }
</script>

<div class="content">
    <div class="text-center auth-logo">
        <a href="//pixelion.com.ua" target="_blank">PIXELION</a>
        <div class="auth-logo-hint"><?= Yii::t('app', 'CMS') ?></div>
    </div>
    <div class="card bg-light">

            <h5 class="card-header">
                <?= $this->title ?> <?= $this->process ?>
            </h5>

        <div class="card-body2 clearfix"><?= $content ?></div>
    </div>

    <div class="text-center"><?= $this->getCopyright(); ?></div>
</div>
</body>
</html>