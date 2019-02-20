<?php

return array(
    'urlFormat' => 'path',
    'class' => 'app.managers.CManagerUrl',
    'showScriptName' => false,
    'useStrictParsing' => true,
    //'caseSensitive'=>false,
    //'urlSuffix' => '.html',
    'rules' => array(
        '/' => 'main/default/index',
        'manifest.json' => 'main/default/manifest',
        'placeholder/*' => 'core/placeholder',
        //'attachment/<id:\w+>/<size:\w+>' => 'site/attachment',
        'attachment/*' => 'core/attachment',
        'ajax/<action:[.\w]+>' => 'main/ajax/<action>', // dotted for actions widget.<name>
        'ajax/<action:[.\w]>/*' => 'main/ajax/<action>',

        'admin/ajax/<action:[.\w]+>' => 'admin/admin/ajax/<action>', // dotted for actions widget.<name>
        'admin/ajax/<action:[.\w]>/*' => 'admin/admin/ajax/<action>',
        'admin/auth' => 'admin/auth',
        'admin/desktop/<action:\w+>' => 'admin/desktop/<action>',
        'admin/desktop/<action:\w+>/*' => 'admin/desktop/<action>',

        'admin/app/<controller:\w+>' => 'admin/admin/<controller>',
        'admin/app/<controller:\w+>/<action:\w+>/*' => 'admin/admin/<controller>/<action>',

        'admin/<module:\w+>' => '<module>/admin/default',
        'admin/default/index/*' => 'admin/default/index', // for ajax pagination main page


        'admin/<module:\w+>/<controller:\w+>' => '<module>/admin/<controller>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/admin/<controller>/<action>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>/*' => '<module>/admin/<controller>/<action>',
        '<module:\w+>/<controller:\w+>' => '<module>/<controller>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
        'admin' => 'admin/default/index',
        'admin/gii' => 'gii',
        'admin/gii/<controller:\w+>' => 'gii/<controller>',
        'admin/gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',

    ),
);