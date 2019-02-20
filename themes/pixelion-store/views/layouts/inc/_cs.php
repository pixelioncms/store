<?php
//https://uxwing.com/webslide/ nice menu
$posJsFile = CClientScript::POS_END;

$min = YII_DEBUG ? '' : '.min';

$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('history');
$cs->registerCoreScript('bootstrap');

$coreUrl = $cs->getCoreScriptUrl();
$cs->scriptMap = array('jquery-ui.min.css' => false,'jquery-ui.css' => false);
// now that we know the core folder, register 
$cs->registerCssFile($this->assetsUrl . '/css/ui.css', CClientScript::POS_HEAD);
//$cs->registerCssFile($coreUrl . '/jui/css/base/jquery-ui.min.css', CClientScript::POS_HEAD, array('id' => 'async'));



$cs->registerScriptFile(Yii::app()->getModule('cart')->assetsUrl . "/cart.js", $posJsFile, array('id' => 'async'));
$cs->registerScriptFile($this->assetsUrl . "/js/scripts.js", $posJsFile);
$cs->registerScriptFile($this->assetsUrl . "/js/jquery.serializejson.min.js", $posJsFile);
//$cs->registerScriptFile($this->assetsUrl . "/js/ttmenu.js", $posJsFile);
//$cs->registerScriptFile($this->assetsUrl . "/js/jquery.fitvids.js", $posJsFile);
//$cs->registerCssFile($this->assetsUrl . "/css/ttmenu.css");
$cs->registerCssFile($this->assetsUrl . "/css/style.css");
$cs->registerCssFile($this->assetsUrl . "/css/ui.css");
$cs->registerCssFile("https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Roboto+Slab:400,700&amp;subset=cyrillic");



Yii::import('ext.notify.Notify');
Notify::register();
