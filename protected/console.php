<?php
error_reporting(E_ALL);

date_default_timezone_set("Europe/Kiev");
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$webRoot = dirname(__DIR__);

$yiic = $webRoot . '/../../framework/yiic.php';
defined('YII_DEBUG') or define('YII_DEBUG', true);

$config = $webRoot . '/protected/config/console.php';
require_once($yiic);


//Yii::createConsoleApplication($config)->run();

