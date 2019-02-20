<?php


return array(
    'basePath' => dirname(__FILE__) . DS . '..',
    'language' => 'ru',
    'name' => 'PIXELION',
    'preload' => array('log', 'siteclose', 'bannedip'), //, 'license'
    'import' => array(
        'application.models.*',
        'app.*',
        'app.helpers.*',
        'app.validators.*',
        'app.integration.forums.*',
        'app.forms.*',
        'application.modules.admin.models.*',
        'application.modules.users.models.User',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
        'ext.attachment.models.AttachmentModel',
        'mod.shop.models.*',
        'mod.cart.models.*',
        'mod.pages.models.*',
        'mod.compare.components.CompareProducts',
        'mod.wishlist.components.WishListComponent',
        'mod.admin.models.NotificationModel',
    ),
    'aliases' => array(
        'mod' => 'application.modules',
        'app' => 'application.components',
        'themes' => 'webroot.themes',
        'application.helpers.*',
        'vendor' => 'application.vendors'
    ),
    'modules' => array(
        'users',
        'admin',
        'main',
        'rights',
        'seo',
    ),
    'components' => array(
        'reCaptcha' => array(
            'name' => 'reCaptcha',
            'class' => 'ext.recaptcha.ReCaptcha',
            'key' => '6LeiV24UAAAAANsxGR9ocCgk4Bv-FMBwlF1ycJu4',
            'secret' => '6LeiV24UAAAAAE92qFgDJZ6hxJak5aut1npbQhfH',
        ),
        'clientScript' => array(
            'class' => 'app.ClientScript',
            'minify'=>false
        ),
        'messages' => array('class' => 'app.PhpMessageSource'),
        'loid' => array('class' => 'ext.lightopenid.loid'),
        'eauth' => require('_eauth.php'),
        'currency' => array('class' => 'mod.shop.components.CurrencyManager'),
        'cart' => array('class' => 'mod.cart.components.Cart'),
        'timeline' => array('class' => 'app.CTimeline'),
        'session' => array(
            'class' => 'app.addons.DbHttpSession',
            //'class' => 'CDbHttpSession',
            'connectionID' => 'db',
            'timeout' => 3600,
            'sessionTableName' => '{{session}}'
        ),
        'siteclose' => array('class' => 'app.maintenance.Siteclose'),
        'user' => array(
            'allowAutoLogin' => true,
            'class' => 'WebUser',
            'loginUrl' => '/users/login'
        ),
        'seo' => array(
            'class' => 'application.modules.seo.components.SeoExt',
        ),
        'db' => require(YII_DEBUG ? '_db_dev.php' : '_db_dev.php'),
        'request' => array(
            'class' => 'app.addons.HttpRequest',
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'token',
            'enableCookieValidation' => true,
            'noCsrfValidationRoutes' => array(
                '/admin/ajax/autocomplete',
                '/comments/edit',
                '/ajax/like',
                '/admin',
                '/users/profile/getAvatars',
                '/users/profile/saveAvatar',
                '/users/login',
                '/comments/create',
                '/ajax/rating',
                '/users/favorites',
                '/cart/payment',
                '/cart/recount',
                '/processPayment',
                '/exchange1c',
                '/notify',
                //  '/ajax/uploadify/upload'
            )
        ),
        'errorHandler' => array('errorAction' => 'site/error'),
        'authManager' => array(
            'class' => 'RDbAuthManager',
            'connectionID' => 'db',
            'defaultRoles' => array('Guest'),
        ),
        'geoip' => array('class' => 'app.geoip.CGeoIP'),
        //'geoipCity' => array('class' => 'app.geoipCity.CGeoIPCity'),
        'mail' => array(
            'class' => 'ext.mailer.EMailer',
            'CharSet' => 'utf-8',
        ),
        'themeManager' => array('class' => 'app.managers.CManagerTheme'),
        'languageManager' => array('class' => 'app.managers.CManagerLanguage'),
        //'database' => array('class' => 'app.managers.CManagerDatabase'),
        'access' => array('class' => 'app.managers.CManagerAccess'),
        'settings' => array('class' => 'app.managers.CManagerSettings'),
        'tpl' => array('class' => 'app.managers.CManagerTemplate'),
        'etpl' => array('class' => 'app.addons.email_template.CETemplate'),
        'blocks' => array('class' => 'app.managers.CManagerBlocks'),
        'widgets' => array('class' => 'app.managers.CManagerFinderWidgets'),
        'urlManager' => require('_urlManager.php'),
        'curl' => array('class' => 'app.addons.Curl'),
        'browser' => array('class' => 'app.addons.BrowserComponent'),
        'img' => array('class' => 'app.addons.CImageHandler'),
        'bannedip' => array('class' => 'app.maintenance.BannedIP'),
        'license' => array('class' => 'app.maintenance.LicenseCheck'),
        'cache' => array(
            'class' => 'CFileCache',//CDummyCache, CFileCache, CDbCache
            //'keyPrefix' => YII_DEBUG ? '' : null,
            //'hashKey' => YII_DEBUG ? false : true
             'hashKey' => false
        ),
        'cacheGeo' => array(
            'class' => 'CFileCache',//CDummyCache, CFileCache, CDbCache
        ),
        'log' => require_once(YII_DEBUG ? '_log.php' : '_log.php'),

    ),
    'params' => require_once('_params.php')
);
