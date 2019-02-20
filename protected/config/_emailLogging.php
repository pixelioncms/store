<?php

return array(
    'class' => 'CLogRouter',
    'routes' => array(
        array(
            //Отправка логов на почту
            'class' => 'EmailLogRoute',
            'emails' => array('andrew.panix@gmail.com'),
            'subject' => 'Detect error in PIXELION CMS',
            'sentFrom' => 'PIXELION CMS report <noreply@pixelion.com.ua> ',
            'levels' => 'error, warning, notice',
            'enabled'=>false
        ),
        array(
            'class' => 'CFileLogRoute',
            'enabled' => true, //лог, включен
            'levels' => 'error', // лог ошибок
            'categories' => '', //лог все категории
            'logFile' => 'error.log', // файл лога
            'logPath' => 'log'    //директория логов
        ),
        array(
            'class' => 'CFileLogRoute',
            'enabled' => true, //  лог, включен
            'levels' => 'warning', // лог ошибок
            'categories' => '', //лог все категории
            'logFile' => 'warning.log', // файл лога
            'logPath' => 'log'    //директория логов
        ),
        array(
            'class' => 'CFileLogRoute',
            'levels' => 'trace', // лог ошибок
            'categories' => '', //лог все категории
            'logFile' => 'trace.log', // файл лога
            'logPath' => 'log', //директория логов
            'enabled' => true, //  лог, включен
        ),
        array(
            'class' => 'CFileLogRoute',
            'levels' => 'error',
            'categories' => 'system.db.*',
            'logFile' => 'sql.log',
            'logPath' => 'log', //директория логов
            'enabled' => true, //  лог, включен
        ),
        array(
            'class' => 'CFileLogRoute',
            'levels' => 'notice',
            'categories' => '',
            'logFile' => 'notice.log',
            'logPath' => 'log', //директория логов
            'enabled' => true, //  лог, включен
        ),
	array(
            'class' => 'CFileLogRoute',
            'levels' => 'info',
            'categories' => '',
            'logFile' => 'info.log',
            'logPath' => 'log', //директория логов
            'enabled' => true, //  лог, включен
        ),
        array(
            'class' => 'CFileLogRoute',
            'levels' => 'Engine',
            'categories' => '',
            'logFile' => 'log.log',
            'logPath' => 'log', //директория логов
            'enabled' => true, //  лог, включен
        ),
        array(
            'class' => 'CWebLogRoute',
            'enabled' => false,
            'levels' => 'error, warning, trace, notice',
            'categories' => 'application',
            'showInFireBug' => false,
        ),
    ),
);