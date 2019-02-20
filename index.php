<?php
error_reporting(E_ALL & ~E_NOTICE); //bug with android module;

//Timezone
date_default_timezone_set("UTC");


defined('DS') or define('DS', DIRECTORY_SEPARATOR);


$webRoot = dirname(__FILE__);
//defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', $webRoot . '/../../framework');


if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    error_reporting(E_ALL);
    $yii = $webRoot . '/../../framework/yii.php';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    $config = $webRoot . '/protected/config/dev.php';
} else {
    error_reporting(E_ALL & ~E_NOTICE); //or set "0"
    /**
     * See: yiilite
     * https://www.yiiframework.com/doc/guide/1.1/ru/topics.performance#sec-4
     */
    $yii = $webRoot . '/../../framework/yiilite.php';
    $config = $webRoot . '/protected/config/main.php';
}
//$yii =COMPONENT_PATH.DS.'/yii.php';

if (!file_exists($yii))
    die('Please install the framework in the root directory.');


require_once $yii;

//Yii::$classMap=array(
//    'Minify' => $webRoot.'/protected/vendors/minify/Minify.php',
//);
// Uncomment if the full release
$path_app = 'protected' . DS . 'components';

require_once $path_app . '/Application.php';

// Create application
Yii::createApplication('Application', $config)->run();



