<?php

Yii::import('mod.seo.models.SeoUrl');
Yii::import('mod.seo.models.SeoMain');
Yii::import('mod.seo.models.SeoParams');

class SeoExt extends CApplicationComponent
{
    /* массив, который будет наполнятся тэгами, что бы исключать уже найденые теги в ссылках выше по иерархии */

    public $exist = array();
    // public $data;
    public $h1;
    public $text;

    public function init()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            $config = Yii::app()->settings->get('seo');
            if ($config->canonical) {
                if (Yii::app()->controller->canonical) {
                    $canonical = Yii::app()->controller->canonical;
                } else {
                    $canonical = Yii::app()->request->getHostInfo() . '/' . Yii::app()->request->getPathInfo();
                }
                Yii::app()->clientScript->registerLinkTag('canonical', null, $canonical);
            }
            if ($config->google_site_verification) {
                Yii::app()->clientScript->registerMetaTag($config->google_site_verification, 'google-site-verification');
            }
            if ($config->yandex_verification) {
                Yii::app()->clientScript->registerMetaTag($config->yandex_verification, 'yandex-verification');
            }
            /*
             * получаем все возможные сслыки по Иерархии
             * Пример: исходная ссылка "site/product/type/34"
             * Результат:
              - site/product/type/34/*
              - site/product/type/34
              - site/product/type/*
              - site/product/type
              - site/product/*
              - site/product
              - site/*
              - site
              - /*
              - /
             *
             * Изменена ****
             */

            $titleFlag = false;
            $urls = $this->getUrls();
            foreach ($urls as $url) {
                $urlF = SeoUrl::model()->findByAttributes(array('url' => $url, 'domain' => SeoUrl::getDomainId()));

                if ($urlF !== null) {
                    //  $this->data = $urlF;
                    if (!empty($urlF->h1))
                        $this->h1 = $urlF->h1;
                    if (!empty($urlF->text))
                        $this->text = $urlF->text;
                    $this->seoName($urlF);
                    $titleFlag = false;
                    break;
                } else {
                    $titleFlag = true;
                }
            }
            if ($titleFlag)
                $this->printMeta('title', Html::encode(Yii::app()->controller->pageTitle));
        }

    }

    public function run2()
    {
        $config = Yii::app()->settings->get('seo');
        if ($config->canonical) {
            if (Yii::app()->controller->canonical) {
                $canonical = Yii::app()->controller->canonical;
            } else {
                $canonical = Yii::app()->request->getHostInfo() . '/' . Yii::app()->request->getPathInfo();
            }
            Yii::app()->clientScript->registerLinkTag('canonical', null, $canonical);
        }
        if ($config->google_site_verification) {
            Yii::app()->clientScript->registerMetaTag($config->google_site_verification, 'google-site-verification');
        }
        if ($config->yandex_verification) {
            Yii::app()->clientScript->registerMetaTag($config->yandex_verification, 'yandex-verification');
        }
        /*
         * получаем все возможные сслыки по Иерархии
         * Пример: исходная ссылка "site/product/type/34"
         * Результат:
          - site/product/type/34/*
          - site/product/type/34
          - site/product/type/*
          - site/product/type
          - site/product/*
          - site/product
          - site/*
          - site
          - /*
          - /
         * 
         * Изменена ****
         */

        $titleFlag = false;
        $urls = $this->getUrls();
        foreach ($urls as $url) {
            $urlF = SeoUrl::model()->findByAttributes(array('url' => $url));

            if ($urlF !== null) {
                $this->data = $urlF;
                $this->seoName($urlF);
                $titleFlag = false;
                break;
            } else {
                $titleFlag = true;
            }
        }
        if ($titleFlag)
            $this->printMeta('title', Html::encode(Yii::app()->controller->pageTitle));

    }

    /**
     * Данная функция находит все MetaName, по ссылке
     * @param $url ссылка по которой будут искаться теги
     */
    private function seoName($url)
    {
        $controller = Yii::app()->controller;

        if ($url->meta_robots) {
            $this->printMeta('robots', $url->meta_robots);
        } else {
            $this->printMeta('robots', 'all');
        }

        if ($url->title) {

            foreach ($url->params as $paramData) {
                $param = $this->getSeoparam($paramData);
                if ($param) {
                    $url->title = str_replace($param['tpl'], $param['item'], $url->title);
                }
            }
            if (Yii::app()->request->getParam('page')) {
                $url->title .= Yii::t('SeoModule.default', 'SEO_PAGE_NUM', array('{n}' => Yii::app()->request->getParam('page')));
            }
            $this->printMeta('title', $url->title . ' ' . Yii::app()->settings->get('seo', 'separation') . ' ' . $controller->pageTitle);

        } else {
            if (isset($controller->pageTitle)) {
                if (Yii::app()->request->getParam('page')) {
                    $controller->pageTitle .= Yii::t('SeoModule.default', 'SEO_PAGE_NUM', array('{n}' => Yii::app()->request->getParam('page')));
                }
                $this->printMeta('title', $controller->pageTitle);
            } else {
                $this->printMeta('title', Yii::app()->settings->get('app', 'site_name'));
            }
        }
        if ($url->keywords) {
            foreach ($url->params as $paramData) {
                $param = $this->getSeoparam($paramData);
                if ($param) {
                    $url->keywords = str_replace($param['tpl'], $param['item'], $url->keywords);
                }
            }
            $this->printMeta('keywords', $url->keywords);
        } else {
            if (isset($controller->pageKeywords))
                $this->printMeta('keywords', $controller->pageKeywords);
        }
        if ($url->description) {
            foreach ($url->params as $paramData) {
                $param = $this->getSeoparam($paramData);
                if ($param) {
                    $url->description = str_replace($param['tpl'], $param['item'], $url->description);
                }
            }
            if (Yii::app()->request->getParam('page')) {
                $url->description .= Yii::t('SeoModule.default', 'SEO_PAGE_NUM', array('{n}' => Yii::app()->request->getParam('page')));
            }
            $this->printMeta('description', $url->description);
        } else {
            if (isset($controller->pageDescription)) {
                if (Yii::app()->request->getParam('page')) {
                    $controller->pageDescription .= Yii::t('SeoModule.default', 'SEO_PAGE_NUM', array('{n}' => Yii::app()->request->getParam('page')));
                }
                $this->printMeta('description', $controller->pageDescription);
            }
        }
    }

    /**
     * функция вывода Мета Тега на страницу
     *
     * @param $name название мета-тега
     * @param $content значение
     */
    private function printMeta($name, $content)
    {
        if ($name == "robots") {
            $content = (is_string($content)) ? $content : implode(',', $content);
        } else {
            $content = strip_tags($content);
        }
        if ($name == "keywords")
            $content = str_replace(',', ", ", $content);
        if ($name == "title") {
            // Yii::app()->controller->pageTitle = $content;
            Yii::app()->clientScript->registerTitleTag($content);
            //  echo "<title>{$content}</title>\n";
        } else {
            Yii::app()->clientScript->registerMetaTag($content, $name);
            // echo "<meta name=\"{$name}\" content=\"{$content}\" />\n";
        }
    }

    private function getUrls()
    {
        $result = null;
        $urls = Yii::app()->request->url;
        if (Yii::app()->languageManager->active->code != Yii::app()->language) {
            $urls = str_replace('/' . Yii::app()->language, '', $urls);
        }

        $data = explode("/", $urls);
        $count = count($data);

        while (count($data)) {
            $_url = "";
            $i = 0;
            foreach ($data as $key => $d) {
                $_url .= $i++ ? "/" . $d : $d;
            }
            //todo: need tested for big links
            if ($count > 2) {
                $result[] = $_url . "/*";
                $result[] = $_url;
            } else {
                $result[] = $_url;
                $result[] = $_url . "/*";
            }

            unset($data[$key]);
        }
        //$result[] = "/*";
        //$result[] = "/";
        $result22 = array_unique($result);
        return $result22;
    }

    /**
     * функция возвращающая значение параметра если он указан
     * Существуют два типа параметров прямой (ModelName/attribyte) или по связи (ModelName/relation.attribyte)
     */
    private function getSeoparam2($pdata)
    {

        $urls = Yii::app()->request->url;
        $data = explode("/", $urls);
        $id = $data[count($data) - 1];
        /* если есть символ ">>" значит параметр по связи */
        //  $param = $pdata->obj;

        //new
        list($object, $parameter) = explode('/', $pdata->obj);
        $tpl = $parameter;
        if (strstr($parameter, ".")) {
            $paramType = true;
            $data = explode(".", $parameter);
            $param = explode("/", $data[0]);
        } else {
            $paramType = false;
            $param = explode("/", $parameter);
        }

        if (class_exists($object, false)) {
            $item = new $object;
            if (is_string($id)) {
                $item = $item->findByAttributes(array('seo_alias' => $id));
            } else {
                $item = $item->findByPk($id);
            }
//var_dump($parameter);die;
            if (count($item)) {

                return array(
                    'tpl' => '{' . $parameter . '}',
                    'item' => ($paramType) ? $item[$param[1]][$data[1]] : $item[$param[1]],
                );
            }
        } else {
            return false;
        }
    }


    private function getSeoparam($pdata)
    {

        $urls = Yii::app()->request->url;
        $data = explode("/", $urls);
        $id = $data[count($data) - 1];
        /* если есть символ ">>" значит параметр по связи */
        $param = $pdata->obj;

        //new
        list($object, $tpl) = explode('/', $pdata->obj);
        //$tpl = $exp[1];
        //$tpl = $pdata->param;
        if (strstr($param, ".")) {
            $paramType = true;
            $data = explode(".", $param);
            $param = explode("/", $data[0]);
        } else {
            $paramType = false;
            $param = explode("/", $param);
        }

        if (class_exists($param[0], false)) {
            $item = new $param[0];
            if (is_string($id)) {
                $item = $item->findByAttributes(array('seo_alias' => $id));
            } else {
                $item = $item->findByPk($id);
            }

            if (count($item)) {

                return array(
                    'tpl' => '{' . $tpl . '}',
                    'item' => ($paramType) ? $item[$param[1]][$data[1]] : $item[$param[1]],
                );
            }
        } else {
            return false;
        }
    }


    public function yandexMetrika()
    {
        $config = Yii::app()->settings->get('seo');
        if (isset($config->yandexmetrika_id) && !empty($config->yandexmetrika_id)) {
            Yii::app()->clientScript->registerScriptFile("//mc.yandex.ru/metrika/watch.js", CClientScript::POS_END);
            Yii::app()->clientScript->registerScript(__FUNCTION__, "
            try {
                var yaCounter" . $config->yandexmetrika_id . " = new Ya.Metrika({
                    id:" . $config->yandexmetrika_id . ",
                    clickmap:" . $config->yandexmetrika_clickmap . ",
                    trackLinks:" . $config->yandexmetrika_trackLinks . ",
                    webvisor:" . $config->yandexmetrika_webvisor . "
                });
            } catch(e) {
            
            }
        ", CClientScript::POS_END);
        }
    }

    public function googleAnalytics()
    {
        $config = Yii::app()->settings->get('seo');
        if (isset($config->googleanalytics_id) && !empty($config->googleanalytics_id)) {
            Yii::app()->clientScript->registerScriptFile("https://www.googletagmanager.com/gtag/js?id={$config->googleanalytics_id}", CClientScript::POS_HEAD, array('async' => true));
            /*Yii::app()->clientScript->registerScript(__FUNCTION__, "
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '" . $config->googleanalytics_id . "']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        ", CClientScript::POS_HEAD);*/

            Yii::app()->clientScript->registerScript(__FUNCTION__, "
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '$config->googleanalytics_id');
        ", CClientScript::POS_HEAD);

        }
    }

    /**
     *
     * @return string
     */
    public function googleTagManager()
    {
        $config = Yii::app()->settings->get('seo');
        if ($config->googletag_id) {


            Yii::app()->clientScript->registerScript(__FUNCTION__, "
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','$config->googletag_id');
        ", CClientScript::POS_HEAD);


        }
    }
}
