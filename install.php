<?php

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    $yii = dirname(__FILE__) . '/../../framework/Yii.php';
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    $yii = dirname(__FILE__) . '/../../framework/yiilite.php';
}
if (!file_exists($yii))
    die('Please install the framework in the root directory.');


/**
 * Файл установки
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

$config = array(
    'basePath' => dirname(__FILE__) . DS . 'protected',
    'language' => 'ru',
    'name' => 'PIXELION CMS',
    'modules' => array(
        'install',
        'rights',
        'main',
        'seo',
        'users',
        'shop',
        'cart',
        'exchange1c',
        'delivery',
        'yandexMarket',
        'compare',
        'wishlist',
        'sitemap',
        'discounts',
        'markup',
        'pages',
        'news'
    ),
    'import' => array(
        'application.components.*',
        'application.components.forms.*',
        'application.components.managers.*',
        'application.components.validators.*',
        'application.modules.admin.models.*',
    ),
    'aliases' => array(
        'mod' => 'application.modules',
        'app' => 'application.components',
    ),
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => true,
            'rules' => array(
                '/' => 'install/default',
            )
        ),
        'cache' => array('class' => 'CFileCache'),
        'db' => require(dirname(__FILE__) . DS . 'protected' . DS . 'config' . DS . '_db.php'),
        'settings' => array('class' => 'app.managers.CManagerSettings'),
        'database' => array('class' => 'app.managers.CManagerDatabase'),
        'widgets' => array('class' => 'app.managers.CManagerFinderWidgets'),
        'tpl' => array('class' => 'app.managers.CManagerTemplate'),
        'languageManager' => array('class' => 'app.managers.CManagerLanguage'),
        'curl' => array('class' => 'app.addons.Curl'),
        'log' => array(
            'class' => 'CLogRouter',
            'enabled' => true,
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info',
                    'logFile' => 'info.log',
                    'logPath' => 'log',
                    'enabled' => true,
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error',
                    'categories' => 'system.db.*',
                    'logFile' => 'sql.log',
                    'logPath' => 'log',
                    'enabled' => true,
                ),
            ),
        ),
    ),
);




require_once($yii);

Yii::createWebApplication($config)->run();
