<?php

/**
 * Модуль карты сайта товаров sitemap.xml
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules
 * @subpackage commerce.sitemap
 * @uses WebModule
 */
class SitemapModule extends WebModule
{
    public $configFiles = array(
        'sitemap' => 'SettingsSitemapForm'
    );
    /**
     * Иконка модуля
     * @var string
     */
    public $icon = 'icon-sitemap';

    /**
     * @var string
     */
    public $changeFreq = 'daily';
    public  $cacheKey = 'sitemap.xml.data';
    /**
     * @var array
     */
    public $urls = array();

    public function init()
    {
        $this->setImport(array(
            $this->id . '.models.*',
        ));
        $this->configure((array)Yii::app()->settings->get('sitemap'));
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        //main page
        $this->urls[Yii::app()->createAbsoluteUrl('/')] = array(
            'changefreq' => $this->changeFreq,
            'priority' => '1.0'
        );
        $this->urls[Yii::app()->createAbsoluteUrl('/contacts')] = array(
            'changefreq' => $this->changeFreq,
            'priority' => '1.0'
        );
        $this->loadPages();
        //$config = Yii::app()->settings->get('sitemap');
        if ($this->product_enable)
            $this->loadProducts();
        if ($this->manufacturer_enable)
            $this->loadManufacturers();
        if ($this->category_enable)
            $this->loadCategories();

        return $this->urls;
    }

    public function getAdminMenu()
    {
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => $this->getIsActive('admin/sitemap'),
                        'visible' => true
                    ),
                ),
            ),
        );
    }


    public function getAdminSidebarMenu()
    {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

    public function afterInstall()
    {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.', 'error');
            return false;
        }
    }

    /**
     * Load products data
     */
    public function loadProducts()
    {
        $products = Yii::app()->db->createCommand()
            ->from('{{shop_product}}')
            ->select('seo_alias, date_create as date')
            ->queryAll();

        $this->populateUrls('shop/product/view', $products, $this->product_changefreq, $this->product_priority);
    }

    /**
     * Load manufacturers data
     */
    public function loadManufacturers()
    {
        $records = Yii::app()->db->createCommand()
            ->from('{{shop_manufacturer}} t')
            ->select('t.seo_alias, p.name as title')
            ->join('{{shop_manufacturer_translate}} p', 't.id=p.object_id')
            ->where('t.switch=1')
            ->order('ordern DESC')
            ->queryAll();
        if (Yii::app()->controller->action->id == 'xml') {
            $this->populateUrls('/shop/manufacturer/view', $records, 'weekly', 0.5);
        }else{
            return array('route'=>'/shop/manufacturer/view','data'=>$records);
        }
    }

    /**
     * Load categories data
     */
    public function loadCategories()
    {
        /*  $records = Yii::app()->db->createCommand()
              ->from('{{shop_category}} t')
              ->select('t.full_path as seo_alias, t.name')
              // ->join('{{shop_category_translate}} p', 't.id=p.object_id')
              ->where('t.id > 1 AND t.switch=1')
              ->queryAll();
          if (Yii::app()->controller->action->id == 'xml') {
              $this->populateUrls('/shop/category/view', $records, 'weekly', 0.9);
          } else {
              return $records;
          }*/

        $model = ShopCategory::model()
            ->findByPk(1);
        if ($model) {
            $data = $model->menuArray();
            if (Yii::app()->controller->action->id == 'xml') {
                $this->recursive($data['items']);
            } else {
                return $this->recursive($data['items']);
            }
        } else {
            throw new CHttpException(500, 'Error CategoriesWidget');
        }
    }
    /**
     * Load pages data
     */
    public function loadPages()
    {
        $data = Yii::app()->db->createCommand()
            ->from('{{page}} t')
            ->select('t.seo_alias as url, p.title as title')
            ->join('{{page_translate}} p', 't.id=p.object_id')
            //->select('seo_alias as url, date_create as date')
            ->where('switch=1')
            ->queryAll();
        if (Yii::app()->controller->action->id == 'xml') {
            $this->populateUrls('pages/default/index', $data, 'weekly', 0.5);
        }else{
            return array('route'=>'/pages/default/index','data'=>$data);
        }
    }
    /**
     * Populate urls data with store records.
     *
     * @param $route
     * @param $records
     * @param string $changefreq
     * @param string $priority
     */
    public function populateUrls($route, $records, $changefreq = 'daily', $priority = '1.0')
    {
        foreach ($records as $p) {
            $url = Yii::app()->createAbsoluteUrl($route, array('seo_alias' => $p['seo_alias']));

            $this->urls[$url] = array(
                'changefreq' => $changefreq,
                'priority' => $priority
            );

            if (isset($p['date']) && strtotime($p['date']))
                $this->urls[$url]['lastmod'] = date('Y-m-d', strtotime($p['date']));

            // print_r($this->urls);die;
        }
    }


    public function recursive($data, $i = 0)
    {
        $html = '';

        if (isset($data)) {
            $html .= Html::tag('ul',array('style'=>'list-style:none'));
            foreach ($data as $obj) {
                $i++;
                $html .= Html::tag('li');
                $html .= Html::link($obj['label'], $obj['url']);
                if (Yii::app()->controller->action->id == 'xml') {
                    $this->populateUrls('/shop/category/view', array(array('seo_alias' => $obj['url']['seo_alias'])), 'weekly', 0.9);
                }
                if (isset($obj['items'])) {
                    $html .= $this->recursive($obj['items'], $i);
                }
                $html .= Html::closeTag('li');
            }
            $html .= Html::closeTag('ul');
        }
        if (Yii::app()->controller->action->id != 'xml') {
            return $html;
        }

    }
    public function getRules()
    {
        return array(
            '/sitemap' => array('sitemap/default/index'),
            '/sitemap.xml' => array('sitemap/default/indexXml'),
        );
    }

}
