<?php

class FileEditorController extends AdminController
{

    public $icon = 'icon-edit';
    public $path_htaccess = false;
    public $path_robots = false;
    public $topButtons = false;

    public function init()
    {

        if (file_exists(Yii::getPathOfAlias('webroot') . DS . '.htaccess')) {
            $this->path_htaccess = Yii::getPathOfAlias('webroot') . DS . '.htaccess';
        }
        if (file_exists(Yii::getPathOfAlias('webroot') . DS . 'robots.txt')) {
            $this->path_robots = Yii::getPathOfAlias('webroot') . DS . 'robots.txt';
        }


        parent::init();
    }

    public function actionIndex()
    {
        $request = Yii::app()->request;
        $this->pageName = Yii::t('app', 'FILE_EDITOR');
        $this->breadcrumbs = array($this->pageName);



        $this->topButtons = array(
            array('label' => Yii::t('app', 'Resets robots.txt'),
                'url' => $this->createUrl('/admin/app/fileEditor', array(
                    'reset' => 'robots',
                )),
                'htmlOptions' => array('class' => 'btn btn-outline-secondary')
            ),
            array('label' => Yii::t('app', 'Resets .htaccess'),
                'url' => $this->createUrl('/admin/app/fileEditor', array(
                    'reset' => 'htaccess',
                )),
                'htmlOptions' => array('class' => 'btn btn-outline-secondary')
            ),
        );





        if ($this->path_htaccess) {
            $htaccess = file_get_contents($this->path_htaccess, false);
        }
        if ($this->path_robots) {
            $robots = file_get_contents($this->path_robots, false);
        }
echo $request->getParam('robots');
        if (in_array($request->getParam('reset'),array('robots','htaccess'))) {
            if ($request->getParam('reset') == 'robots') {
                $robots_h = fopen($this->path_robots, "wb");
                fwrite($robots_h, $this->defaultRobots());
                fclose($robots_h);
            }

            if ($request->getParam('reset') == 'htaccess') {
                $robots_h = fopen($this->path_robots, "wb");
                fwrite($robots_h, $this->defaultHtaccess());
                fclose($robots_h);
            }
            $this->setNotify('Success! reset', 'success');
            $this->redirect(array('/admin/app/fileEditor'));
        }
        if ($request->getPost('robots') && $request->getPost('htaccess')) {

            $robots_h = fopen($this->path_robots, "wb");
            fwrite($robots_h, $request->getPost('robots'));
            fclose($robots_h);

            $htaccess_h = fopen($this->path_htaccess, "wb");
            fwrite($htaccess_h, $request->getPost('htaccess'));
            fclose($htaccess_h);
            $this->setNotify(Yii::t('app','SUCCESS_UPDATE'), 'success');
            //  $this->redirect(array('/admin/app/fileEditor'));
            $this->refresh();
        }


        $this->render('index', array(
            'htaccess' => $htaccess,
            'robots' => $robots
        ));
    }

    protected function defaultHtaccess()
    {
        return '
# Required parameter
AddDefaultCharset ' . Yii::app()->charset . '

RewriteEngine on

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond $1 !^(favicon\.ico)

# Redirect HTTPS protocol.
# If you have the opportunity to establish a redirect on your hosting, we recommend doing this
#RewriteCond %{HTTPS} off
#RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]



# Redirect from "http://yoursite.com/page/" to "http://yoursite.com/page"
RewriteCond %{REQUEST_URI} ^(.+)/$
RewriteRule ^(.+)/$  /$1 [R=301,L]



# Redirect from "http://yoursite.com/index.php" to "http://yoursite.com"
RewriteCond %{THE_REQUEST} ^.*/index\.php 
RewriteRule ^(.*)index.php$ /$1 [R=301,L] 



# Redirect from "http://www.yoursite.com" to "http://yoursite.com"
#RewriteCond %{HTTP_HOST} ^www\.(.*) [NC]
#RewriteRule ^(.*)$ http://%1/$1 [L]
# Version 2
#RewriteCond %{HTTP_HOST} ^www\.(.*)$
#RewriteRule ^(.*)$ http://%1/$1 [L,R=301]
#####

# 1C auth (dev)
#RewriteRule .* - [E=REMOTE_USER:%{HTTP:Authorization},L]


RewriteCond %{REQUEST_URI} !^/(assets|install|uploads|api).*$
RewriteRule . index.php
';
    }

    protected function defaultRobots()
    {
        return '
User-Agent: *
Disallow: /placeholder
Disallow: /admin/auth
Disallow: /assets/
Disallow: /protected/
Disallow: /themes/
Disallow: /upgrade/

Host: ' . Yii::app()->request->serverName . '
Sitemap: ' . Yii::app()->request->hostInfo . '/sitemap.xml

        ';
    }

}
