<?php

$posJsFile = CClientScript::POS_HEAD;

$min = YII_DEBUG ? '' : '.min';

$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('history');
$cs->registerCoreScript('bootstrap');

$coreUrl = $cs->getCoreScriptUrl();
$cs->scriptMap = array('jquery-ui.css' => false);
// now that we know the core folder, register 
$cs->registerCssFile($this->assetsUrl . '/css/ui.css', CClientScript::POS_HEAD);
//$cs->registerCssFile($coreUrl . '/jui/css/base/jquery-ui.min.css', CClientScript::POS_HEAD, array('id' => 'async'));



$cs->registerScriptFile(Yii::app()->getModule('cart')->assetsUrl . "/cart.js", $posJsFile, array('id' => 'async'));
$cs->registerScriptFile($this->assetsUrl . "/js/jquery.serializejson.min.js", $posJsFile);
$cs->registerCssFile($this->assetsUrl . "/css/style.css");
$cs->registerCssFile($this->assetsUrl . "/css/ui.css");
$cs->registerCssFile("https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&amp;subset=cyrillic");



Yii::import('ext.notify.Notify');
Notify::register();
