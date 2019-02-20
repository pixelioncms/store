<?php

return array(
    'class' => 'ext.eauth.EAuth',
    'popup' => true, // Use the popup window instead of redirecting.
    'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
    'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
    'services' => array(// You can change the providers and their classes.
       // 'vkontakte' => array('class' => 'VKontakteOAuthService'),
        'facebook' => array(
            'class' => 'FacebookOAuthService',
            'client_id'=>'668399753544729',
            'client_secret'=>'1edf663896840016c399a22a4673bd1e'

        ),
       // 'github' => array('class' => 'GitHubOAuthService'),
       // 'live' => array('class' => 'LiveOAuthService'),
       // 'linkedin' => array('class' => 'LinkedinOAuthService'),
       // 'yandex_oauth' => array('class' => 'YandexOAuthService'),
        'google_oauth' => array(
            'class' => 'GoogleOAuthService',
            'client_id' => '645422072599-u64afffqeeger6mdhnp9esd7ai1km9a3.apps.googleusercontent.com',
            'client_secret' => 'hEAOQHe-qcKuSFOkdXKpMIm2',
        ),
       // 'twitter' => array('class' => 'TwitterOAuthService'),
        // 'yandex' => array('class' => 'YandexOpenIDService'),
        //'google' => array('class' => 'GoogleOpenIDService'),
        //'mailru' => array('class' => 'MailruOAuthService'),
        //'moikrug' => array('class' => 'MoikrugOAuthService'),
       // 'odnoklassniki' => array('class' => 'OdnoklassnikiOAuthService'),
       // 'yahoo' => array('class' => 'YahooOpenIDService'),
       // 'steam' => array('class' => 'SteamOpenIDService'),
        /*'dropbox' => array(
            'class' => 'DropboxOAuthService',
            'client_id' => 'czeb4lcpnanbg74',
            'client_secret' => 'a7zgoqsgh2zvabo',
        ),*/

        /*'instagram' => array(
            'class' => 'InstagramOAuthService',
            'client_id' => 'fbf16e998a4d41759f106e7259d58497',
            'client_secret' => '6975b03e773846e2ac4ec5fff9542c52',
        ),*/
    ),
);