<?php

return array(
    'class' => 'app.addons.DbConnection',
    'connectionString' => 'mysql:host=localhost;dbname=database',
    'username' => 'root',
    'password' => '',
    'tablePrefix' => 'cms_',
    'initSQLs'=>array('set names utf8'),
    'charset' => 'utf8',
    'enableProfiling' => YII_DEBUG,
    'enableParamLogging' => YII_DEBUG,
    'schemaCachingDuration' => YII_DEBUG ? 0 : 3600*12
);
