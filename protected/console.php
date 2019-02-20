<?php
error_reporting(E_ALL);

date_default_timezone_set("Europe/Kiev");
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$webRoot = dirname(__FILE__);


$yiic = $webRoot . '/../../../framework/yiic.php';

//stab
//$yiic = $webRoot . '/../../../framework/yiic.php';


$config = $webRoot . '/config/console.php';
require_once($yiic);


//Yii::createConsoleApplication($config)->run();

