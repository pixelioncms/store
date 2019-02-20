<?php
$assetsUrl = Yii::app()->getModule('admin')->assetsUrl;

$posJsFile = CClientScript::POS_HEAD;

$cs = Yii::app()->clientScript;
//$packagesAsset = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('app.packages'), false, -1, YII_DEBUG);


//$cs->registerScriptFile($packagesAsset . "/bootstrap/js/bootstrap.min.js");

//$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');
//$cs->registerScriptFile($packagesAsset . "/bootstrap/js/bootstrap.bundle.min.js",CClientScript::POS_END);
//$cs->registerCssFile($packagesAsset . "/bootstrap/css/bootstrap.min.css");
$cs->registerCoreScript('bootstrap');
$cs->registerCoreScript('cookie');

$coreUrl = $cs->getCoreScriptUrl();
$cs->scriptMap = array('jquery-ui.css' => false);
// now that we know the core folder, register 
//$cs->registerCssFile($coreUrl . '/jui/css/base/jquery-ui.css');

$cs->registerCoreScript('maskedinput');




// Jquery UI
//$cs->registerScriptFile($assetsUrl . "/js/jquery-ui.min.js");
//$cs->registerCssFile($assetsUrl . "/css/jquery-ui.min.css");
//$cs->registerCssFile($assetsUrl . "/css/jquery-ui.theme.min.css");
//$cs->registerCssFile($assetsUrl . "/css/ui_yii.css");

$cs->registerScriptFile($this->baseAssetsUrl . "/js/clipboard.min.js",CClientScript::POS_END);
$cs->registerScriptFile($this->baseAssetsUrl . "/js/jquery.dialogOptions.js",CClientScript::POS_END);
$cs->registerScriptFile($assetsUrl . '/js/init.masks.js',CClientScript::POS_END);

//Enabled jquery plugins touch mobile devices
$cs->registerScriptFile($assetsUrl . '/js/jquery.ui.touch-punch.min.js',CClientScript::POS_END);
$cs->registerScriptFile($assetsUrl . '/js/jquery.collapsible.min.js',CClientScript::POS_END);

$cs->registerCssFile($assetsUrl . '/css/dashboard.css');
$cs->registerCssFile($assetsUrl . '/css/breadcrumbs.css');
if(Yii::app()->user->getAdminTheme()=='light'){
    $cs->registerCssFile($assetsUrl . '/css/light.css');
    $cs->registerMetaTag('#f9f9f9','theme-color');

}elseif(Yii::app()->user->getAdminTheme()=='dark'){
    $cs->registerCssFile($assetsUrl . '/css/dark.css');
    $cs->registerMetaTag('#343a40','theme-color');
}else{
    $cs->registerCssFile($assetsUrl . '/css/pixelion.css');
    $cs->registerMetaTag('#343a40','theme-color');
}





Yii::import('ext.notify.Notify');
Notify::register();



if (Yii::app()->language == Yii::app()->languageManager->default->code) {
    $cs->registerScriptFile($assetsUrl . '/js/translitter.js',CClientScript::POS_END);
    $cs->registerScriptFile($assetsUrl . '/js/init_translitter.js',CClientScript::POS_END);
}
$cs->registerScriptFile($assetsUrl . '/js/dashboard.js',CClientScript::POS_END);
if (!YII_DEBUG && Yii::app()->hasModule('cart'))
    $cs->registerScriptFile($assetsUrl . '/js/counters.js',CClientScript::POS_END);