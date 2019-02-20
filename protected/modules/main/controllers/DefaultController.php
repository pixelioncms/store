<?php

class DefaultController extends Controller
{

    public $layout = '//layouts/main';

    public function actionIndex()
    {
        $this->breadcrumbs = array(Yii::t('zii', 'Home'));
        $this->render('index', array());
    }

    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        $this->layout = '//layouts/error';
        if ($error) {
            $this->pageTitle = Yii::t('site', '_ERROR') . ' ' . $error['code'];
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', array('error' => $error));
            }
        }
    }

    public function actionManifest()
    {

        $favicons = array('64x64', '128x128', '144x144', '152x152', '192x192', '512x512');
        $icons = array();
        foreach ($favicons as $size) {
            if (file_exists(Yii::getPathOfAlias("current_theme.assets.images") . DS . "apple-touch-icon-{$size}.png")) {
                $icons[] = array(
                    "src" => $this->assetsUrl . "/images/apple-touch-icon-{$size}.png",
                    "sizes" => $size,
                    "type" => "image/png"
                );
            }
        }
        $icons[] = array(
            "src" => CMS::placeholderUrl(array('size' => '192x192')),
            "sizes" => '192x192',
            "type" => "image/png"
        );
        $icons[] = array(
            "src" => CMS::placeholderUrl(array('size' => '512x512')),
            "sizes" => '512x512',
            "type" => "image/png"
        );

        $config = Yii::app()->settings->get('app');
        $json = array();
        $json['name'] = $config->site_name;
        $json['short_name'] = 'Pixelion'; //max 12 characters
        $json['lang'] = Yii::app()->language;
        //  $json['orientation'] = 'landscape';

        $json['start_url'] = Yii::app()->baseUrl;
        $json['display'] = 'standalone';//fullscreen,standalone
        $json['background_color'] = '#3E4EB8';
        $json['theme_color'] = '#2F3BA2';
        $json['icons'] = $icons;

        $this->setJson($json);
    }
}