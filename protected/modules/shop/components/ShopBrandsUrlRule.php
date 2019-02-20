<?php

class ShopBrandsUrlRule extends CBaseUrlRule
{

    public $connectionID = 'db';
    public $urlSuffix = '';

    public function createUrl($manager, $route, $params, $ampersand)
    {

        if ($route === 'shop/manufacturer/view') {
            $url = trim($params['seo_alias'], '/');
                unset($params['seo_alias']);

            $parts = array();
            if (!empty($params)) {
                foreach ($params as $key => $val)
                    $parts[] = $key . '/' . $val;

                // for ajax mode
                if (Yii::app()->request->isAjaxRequest && Yii::app()->settings->get('shop', 'ajax_mode')) {
                    if (!Yii::app()->request->getParam('ajax')) {
                        $url .= '/' . implode('/', $parts);
                    } else {
                        // $url .= implode('/', $parts);
                    }
                } else {
                    $url .= '/' . implode('/', $parts);
                }
            }

            return $url . $this->urlSuffix;
        }
        return false;
    }


    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {

        if (empty($pathInfo))
            return false;

        if ($this->urlSuffix)
            $pathInfo = strtr($pathInfo, array($this->urlSuffix => ''));

            //$pathInfo = 'brand/'. $pathInfo;


        foreach ($this->getAllPaths() as $path) {
            if ($path !== '' && strpos($pathInfo, $path) === 0) {
                $_GET['seo_alias'] = $path;

                //print_r($_GET);

                // $_REQUEST['brands']=$_GET['seo_alias']=$path;
                $params = ltrim(substr($pathInfo, strlen($path)), '/');

                // $params['seo_alias']=$path;
               // $params['manufacturer'] = $_GET['seo_alias'];

                Yii::app()->urlManager->parsePathInfo($params);


                return 'shop/manufacturer/view';
            }
        }
        return false;
    }


    protected function getAllPaths()
    {
        $allPaths = Yii::app()->cache->get('ShopBrandsUrlRule');

        if ($allPaths === false) {
            $allPaths = Yii::app()->db->createCommand()
                ->from('{{shop_manufacturer}}')
                ->select('seo_alias')
                ->queryColumn();

            $urls = array();
            foreach($allPaths as $url){
                $urls[]='manufacturer/'.$url;
            }

            // Sort paths by length.
            usort($urls, function ($a, $b) {
                return strlen($b) - strlen($a);
            });

            Yii::app()->cache->set('ShopBrandsUrlRule', $urls, Yii::app()->settings->get('app', 'cache_time')); //Yii::app()->settings->get('app','cache_time')
        }

        return $allPaths;
    }

}
