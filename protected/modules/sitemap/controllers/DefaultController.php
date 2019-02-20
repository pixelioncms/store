<?php

class DefaultController extends Controller
{
    /**
     * Html
     * Render sitemap
     */
    public function actionIndex() {
        $cacheKey = 'sitemap.html.data';
        $data = Yii::app()->cache->get($cacheKey);
        $this->pageName = Yii::t('SitemapModule.default','MODULE_NAME');


        // if (!$data) {
        $data =$this->render('index', array(
            'categories' => $this->module->loadCategories(),
            'pages' => $this->module->loadPages(),
            'manufacturers' => $this->module->loadManufacturers(),
        ));

        // Yii::app()->cache->set($cacheKey, $data, Yii::app()->settings->get('app','cache_time'));
        // }

        // if (!headers_sent())
        // header('Content-Type: text/xml');

        echo $data;
    }


    /**
     * Render sitemap.xml
     */
    public function actionIndexXml()
    {
        $cacheKey = Yii::app()->getModule('sitemap')->cacheKey;
        $data = Yii::app()->cache->get($cacheKey);

        if (!$data) {
            $data = $this->renderPartial('xml', array(
                'urls' => $this->getModule()->getUrls()
            ), true);

            Yii::app()->cache->set($cacheKey, $data, Yii::app()->settings->get('app', 'cache_time'));
        }

        if (!headers_sent())
            header('Content-Type: text/xml');

        echo $data;
    }

}