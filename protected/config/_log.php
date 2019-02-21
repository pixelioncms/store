<?php
/*
return array(
    'class' => 'CLogRouter',
    'enabled' => true,
    'routes' => array(
        array(
            'class' => 'ext.loganalyzer.LALogRoute',
            'levels' => 'info, error, warning, sql',
        ),
        array(
            'class' => 'ext.debug-toolbar.YiiDebugToolbarRoute',
            'ipFilters' => array('127.0.0.1'),
            'levels' => 'info, error, warning, sql, trace',
            'enabled' => false
        ),

    ),
);
*/
return array(
    'class' => 'CLogRouter',
    'routes' => array(
        array(
            'class'=>'CProfileLogRoute',
            'report'=>'summary',
            'enabled' => false
            // Показывает время выполнения каждого отмеченного блока кода.
            // Значение "report" также можно указать как "callstack".
        ),
        array(
            'class' => 'CFileLogRoute',
            'logFile' => 'log_errors.log',
            'levels' => 'error, warning',
        ),
        array(
            'class' => 'CFileLogRoute',
            'logFile' => 'log_trace.log',
            'levels' => 'trace',
        ),
        array(
            'class' => 'CFileLogRoute',
            'logFile' => 'log.log',
            'levels' => 'info',
        ),
        array(
            'class' => 'CFileLogRoute',
            'logFile' => 'log_sql.log',
           // 'levels' => 'profile',
            //'categories'=>'system.db.*',
            'categories'=>'system.db.CDbCommand.query',
            'except'=>'system.db.ar.*',
        ),
    ),
);