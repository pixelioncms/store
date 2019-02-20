<?php

/**
 * Configure file console commands.
 */
return array(
    'basePath' => dirname(__FILE__) . DS . '..',
    'name' => 'PIXELION Console',
    'preload' => array('log'),
    'import' => array(
        'app.*',
        'application.models.*',
        'application.modules.admin.models.*',
        'app.helpers.*',
        'app.validators.*',
        'app.integration.forums.*',
        'app.forms.*',
    ),
    'aliases' => array(
        'mod' => 'application.modules',
        'app' => 'application.components',
        'webroot' => dirname(__DIR__) . DS . '..',
        //   'application.helpers.*',
    ),
    'components' => array(
        'settings' => array('class' => 'app.managers.CManagerSettings'),
        //'database' => array('class' => 'app.managers.CManagerDatabase'),
        'db' => require_once(YII_DEBUG ? '_db_dev.php' : '_db.php'),
        'curl' => array('class' => 'app.addons.Curl'),
        'zip' => array('class' => 'app.addons.Zip'),
        'cache' => array('class' => 'CFileCache'),
        'log' => require_once(YII_DEBUG ? '_log.php' : '_log.php'),
    ),
    'params' => require_once('_params.php')
);