<?php
$min = YII_DEBUG ? '' : '.min';
$adminAssetsUrl = Yii::app()->getModule('admin')->assetsUrl;
$cs = Yii::app()->clientScript;

$cs->registerCoreScript('bootstrap');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($this->baseAssetsUrl . "/js/common.js", CClientScript::POS_END);
$cs->registerCssFile($this->baseAssetsUrl . "/css/pixelion-icons{$min}.css");
$cs->registerCssFile($adminAssetsUrl . '/css/dashboard.css');
$cs->registerCssFile($adminAssetsUrl . '/css/login.css');


?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= $this->pageTitle ?></title>
</head>

<script>

   /* $(function () {
        var loginContainer = $('#login-container');
        var logoHeight = $('.auth-logo a').height();
        var height = loginContainer.height();
        var win_height = $(window).height();
        if (win_height >= height) {
            //console.log('ss');
            loginContainer.css({'margin-top': (win_height / 2) - (height / 2) - logoHeight});
        }
        $(window).resize(function () {
            if($(window).height() > height+$('.auth-logo a').height()){
            //console.log('ss');
            $('#login-container').css({'margin-top': ($(window).height() / 2) - ($('#login-container').height() / 2) - $('.auth-logo a').height()});

            console.log(win_height);
            console.log(height);

             }else{
                $('#login-container').css({'margin-top': 80});

            }
        });

    });*/

</script>
<body class="no-radius">
<?php echo $content; ?>
</body>
</html>
