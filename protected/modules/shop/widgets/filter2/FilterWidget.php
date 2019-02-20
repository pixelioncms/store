<?php

/**
 * Base class to render filters by:
 *  Manufacturer
 *  Price
 *  Eav attributes
 *
 * Usage:
 * $this->widget('mod.shop.widgets.filter.FilterWidget', array(
 *      // ShopCategory model. Used to create url
 *      'model'=>$model,
 *  ));
 *
 * @method CategoryController getOwner()
 */
class FilterWidget extends CWidget
{

    /**
     * 1 = общие кол.
     * 0 = отображает количество в зависимости от выбранных фильтров
     * @var type
     */
    private $typeFilter = 1;

    /**
     * @var array of ShopAttribute models
     */
    public $attributes;
    public $countAttr = true;
    public $countManufacturer = true;
    public $prices = array();
    public $tagCount = 'sup';
    public $showEmpty = false;
    public $cache_time = 0; //3600;
    public $route = '/shop/category/view';
    /**
     * @var ShopCategory
     */
    public $model;

    /**
     * @var string min price in the query
     */
    private $_currentMinPrice = null;

    /**
     * @var string max price in the query
     */
    private $_currentMaxPrice = null;

    public static function actions()
    {
        return array(
            'action' => 'mod.shop.widgets.filter2.actions.FilterAction',
        );
    }

    public function getMinPrice()
    {
        return $this->controller->getMinPrice();
    }

    public function getMaxPrice()
    {
        return $this->controller->getMaxPrice();
    }
    public function init()
    {
        if ($this->controller->route == 'shop/manufacturer/view') {
            $this->route = '/shop/manufacturer/view';
        }

        parent::init();
    }
    /**
     * Render filters
     */
    public function run2()
    {

        if ($this->typeFilter == 1) {
            $cacheId = 'filters' . $this->model->id;
        } else {
            $cacheId = 'filters' . $this->model->id . Yii::app()->request->removeUrlGetParam('/shop/category/view', array('page', 'per_page', 'sort', 'view'));
        }

        /*if ($this->beginCache($cacheId, array(
                    'duration' => $this->cache_time,
                    'dependency' => array(
                        'class' => 'CDbCacheDependency',
                        'sql' => 'SELECT MAX(date_update) FROM {{shop_product}}'
                    )
                        )
                )) {*/

        $this->render($this->skin, array(
            'manufacturers' => ($this->typeFilter) ? $this->getCategoryManufacturers() : $this->getCategoryManufacturers__old(),
            'attributes' => $this->getCategoryAttributes(),
            'prices' => $this->prices
        ));
        /*   $this->endCache();
       }*/
    }
    public function run()
    {
        die('filter2');
        $config = Yii::app()->settings->get('shop');

        echo Html::openTag('div', array('id' => 'filters'));

        echo $this->render('_current', array(), true);

        if ($this->controller->route == 'shop/manufacturer/view') {
            // Filter by prices
            if ($config->filter_enable_price)
                echo $this->render('_price', array('config' => $config, 'prices' => $this->prices), true);


            // Filters by attributes
            echo $this->render('_attributes', array(
                'config' => $config,
                'attributes' => $this->getCategoryAttributes(),
            ), true);
            // echo Html::endForm();

        } else {


            // Filter by prices
            if ($config->filter_enable_price)
                echo $this->render('_price', array('config' => $config, 'prices' => $this->prices), true);


            echo Html::beginForm(array($this->route, 'seo_alias' => $this->model->full_path), 'GET', array('id' => 'filter-form'));

            if ($config->filter_enable_brand) {
                echo $this->render('_manufacturer', array(
                    'config' => $config,
                    'manufacturers' => ($this->typeFilter) ? $this->getCategoryManufacturers() : $this->getCategoryManufacturers__old(),
                    'attributes' => $this->getCategoryAttributes(),
                ), true);
            }

            // Filters by attributes
            echo $this->render('_attributes', array(
                'config' => $config,
                'attributes' => $this->getCategoryAttributes(),
            ), true);
            // echo Html::submitButton('GO');
            echo Html::endForm();


        }
        echo Html::closeTag('div');

    }
    /**
     * Get active/applied filters to make easier to cancel them.
     */
    public function getActiveFilters()
    {
        $request = Yii::app()->request;
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();
        if ($this->controller->route == 'shop/category/view') {
            $manufacturers = array_filter(explode(',', $request->getQuery('manufacturer')));
            $manufacturers = ShopManufacturer::model()
                //->cache($this->controller->cacheTime)
                ->findAllByPk($manufacturers);
        }
        if ($request->getQuery('min_price') || $request->getQuery('min_price')) {
            $menuItems['price'] = array(
                'label' => Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER').':',
            );
        }


        if ($request->getQuery('min_price')) {
            $menuItems['price']['items'][] = array(
                'label' => Yii::t('ShopModule.default', 'от {minPrice} {c}', array('{minPrice}' => (int)$this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove'),
                'url' => $request->removeUrlParam('/shop/category/view', 'min_price')
            );
        }

        if ($request->getQuery('max_price')) {
            $menuItems['price']['items'][] = array(
                'label' => Yii::t('ShopModule.default', 'до {maxPrice} {c}', array('{maxPrice}' => (int)$this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove'),
                'url' => $request->removeUrlParam('/shop/category/view', 'max_price')
            );
        }
        if ($this->controller->route == 'shop/category/view') {
            if (!empty($manufacturers)) {
                $menuItems['manufacturer'] = array(
                    'label' => Yii::t('ShopModule.default', 'FILTER_MANUFACTURER') . ':',
                );

                foreach ($manufacturers as $manufacturer) {
                    $menuItems['manufacturer']['items'][] = array(
                        'label' => $manufacturer->name,
                        'linkOptions' => array('class' => 'remove'),
                        'url' => $request->removeUrlParam('/shop/category/view', 'manufacturer', $manufacturer->id)
                    );
                }
            }
        }
        // Process eav attributes
        $activeAttributes = $this->getOwner()->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset($this->getOwner()->eavAttributes[$attributeName])) {
                    $attribute = $this->getOwner()->eavAttributes[$attributeName];
                    $menuItems[$attributeName] = array(
                        'label' => $attribute->title.':',
                    );
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            $menuItems[$attributeName]['items'][] = array(
                                'label' => $option->value . '' . $attribute->abbreviation,
                                'linkOptions' => array('class' => 'remove'),
                                'url' => $request->removeUrlParam('/shop/category/view', $attribute->name, $option->id)
                            );
                            sort($menuItems[$attributeName]['items']);
                        }
                    }
                }
            }
        }
        return $menuItems;
    }

    /**
     * @return array of attributes used in category
     */
    public function getCategoryAttributes()
    {
        $data = array();

        foreach ($this->attributes as $attribute) {
            $data[$attribute->name] = array(
                'title' => $attribute->title,
                'selectMany' => (boolean)$attribute->select_many,
                'slider' => $attribute->slider,
                'filters' => array()
            );
            foreach ($attribute->options as $option) {
                $count = ($this->typeFilter) ? $this->countAttributeProducts($attribute, $option) : $this->countAttributeProducts__old($attribute, $option);
                if (!$this->showEmpty && $count) {
                    $data[$attribute->name]['filters'][] = array(
                        'title' => $option->value,
                        'count' => $count,
                        'abbreviation' => $attribute->abbreviation,
                        'queryKey' => $attribute->name,
                        'queryParam' => $option->id,

                    );
                }
            }
        }
        return $data;
    }

    /**
     * Count products by attribute and option
     * @param ShopAttribute $attribute
     * @param string $option option id to search
     * @todo Optimize attributes merging
     * @return string
     */
    public function countAttributeProducts($attribute, $option)
    {

        if ($this->countAttr) {
            $model = new ShopProduct(null);

            $sql = 'SELECT MAX(date_update) FROM {{shop_product}} 
        LEFT JOIN {{shop_product_attribute_eav}} ON {{shop_product_attribute_eav}}.`entity`={{shop_product}}.`id` 
        LEFT JOIN {{shop_product_category_ref}} ON {{shop_product_category_ref}}.`product`={{shop_product}}.`id` 
        WHERE {{shop_product_attribute_eav}}.`attribute`="' . $attribute->name . '" AND {{shop_product_attribute_eav}}.`value`="' . $option->id . '" AND  {{shop_product_category_ref}}.`category`="' . $this->model->id . '" AND {{shop_product}}.`switch`="1"';
            $dependency = new CDbCacheDependency($sql);

            $model->attachBehaviors($model->behaviors());
            $model->published();
            if ($this->controller->route == 'shop/manufacturer/view') {
                $model->applyManufacturers($this->getOwner()->dataModel->id);
            }
            if ($this->controller->route == 'shop/category/view') {
                $model->applyCategories($this->model);
            }
            //$model->cache($this->cache_time);
            // $model->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')));
            // $model->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));
            //if (Yii::app()->request->getParam('manufacturer'))
               //$model->applyManufacturers(explode(',', Yii::app()->request->getParam('manufacturer')));
            $newData = array();
            $newData[$attribute->name][] = $option->id;
            return $model->withEavAttributes($newData)->count();
        }
    }

    public function countAttributeProducts__old($attribute, $option)
    {
        // print_r($attribute);
        // echo $attribute->name.' - '.$option->id;
        echo '<br>';
        if ($this->countAttr) {
            // $dependency = new CDbCacheDependency('SELECT MAX(date_update) FROM {{shop_product}}');

            $model = new ShopProduct(null);
            if ($this->controller->route == 'shop/manufacturer/view') {
                $model->manufacturer_id = $this->getOwner()->dataModel->id;
            }
            $model->attachBehaviors($model->behaviors());
            $model->published();
            if ($this->controller->route == 'shop/category/view') {
                $model->applyCategories($this->model);
            }
            $model->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')));
            $model->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));
            if ($this->controller->route == 'shop/category/view') {
                if (Yii::app()->request->getParam('manufacturer'))
                    $model->applyManufacturers(explode(',', Yii::app()->request->getParam('manufacturer')));
            }
            //$data = array($attribute->name => $option->id);
            $current = $this->getOwner()->activeAttributes;

            $newData = array();

            foreach ($current as $key => $row) {
                if (!isset($newData[$key]))
                    $newData[$key] = array();
                if (is_array($row)) {
                    foreach ($row as $v)
                        $newData[$key][] = $v;
                } else
                    $newData[$key][] = $row;
            }
            //$model->cache($this->cache_time,$dependency);
            $newData[$attribute->name][] = $option->id;

            return $model->withEavAttributes($newData)->count();
        }
    }

    /**
     * @return array of category manufacturers
     */
    public function getCategoryManufacturers()
    {
        $cr = new CDbCriteria;
        $cr->select = 't.manufacturer_id, t.id';
        $cr->group = 't.manufacturer_id';
        $cr->addCondition('t.manufacturer_id IS NOT NULL');

        //@todo: Fix manufacturer translation
        $mdl = $this->model;
        //$dependency = new CDbCacheDependency('SELECT MAX(date_update) FROM {{shop_product}}');
        //$dependency = new CChainedCacheDependency();
        $manufacturers = ShopProduct::model()
            ->published()
            ->applyCategories($mdl, null)
            ->with(array(
                'manufacturerPublished' => array(
                    'with' => array(
                        'productsCount' => array(
                            'scopes' => array(
                                'published',
                                'applyCategories' => array($mdl, null),
                                'applyAttributes' => array($this->getOwner()->activeAttributes),
                                'applyMinPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                'applyMaxPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),
                            ))
                    ),
                )))
            //->cache($this->cache_time)
            ->findAll($cr);

        $data = array(
            'title' => Yii::t('ShopModule.default', 'FILTER_MANUFACTURER'),
            'selectMany' => true,
            'filters' => array()
        );

        if ($manufacturers) {

            foreach ($manufacturers as $m) {
                $m = $m->manufacturerPublished;
                if ($m) {
                    $model = new ShopProduct(null);
                    $model->attachBehaviors($model->behaviors());
                    $model->published();
                    //$model->cache($this->cache_time);
                    $model->applyCategories($this->model);
                    //$model->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
                    //$model->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')))

                    $model->applyManufacturers($m->id);


                    $data['filters'][] = array(
                        'title' => $m->name,
                        'count' => $model->count(),
                        'queryKey' => 'manufacturer',
                        'queryParam' => $m->id,
                    );
                }
            }
        }

        return $data;
    }

    public function getCategoryManufacturers__old()
    {
        $cr = new CDbCriteria;
        $cr->select = 't.manufacturer_id, t.id';
        $cr->group = 't.manufacturer_id';
        $cr->addCondition('t.manufacturer_id IS NOT NULL');

        //@todo: Fix manufacturer translation
        $mdl = $this->model;
        //$dependency = new CDbCacheDependency('SELECT MAX(date_update) FROM {{shop_product}}');

        $manufacturers = ShopProduct::model()
            //->cache($this->cache_time, $dependency)
            ->published()
            ->applyCategories($mdl, null)
            ->with(array(
                'manufacturerPublished' => array(
                    'with' => array(
                        'productsCount' => array(
                            'scopes' => array(
                                'published',
                                'applyCategories' => array($mdl, null),
                                'applyAttributes' => array($this->getOwner()->activeAttributes),
                                'applyMinPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                'applyMaxPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),
                            ))
                    ),
                )))
            ->findAll($cr);

        $data = array(
            'title' => Yii::t('default', 'Производитель'),
            'selectMany' => true,
            'filters' => array()
        );

        if ($manufacturers) {
            foreach ($manufacturers as $m) {
                $m = $m->manufacturerPublished;
                if ($m) {
                    $model = new ShopProduct(null);
                    $model->attachBehaviors($model->behaviors());
                    $model->published()
                        //->cache($this->cache_time, $dependency)
                        ->applyCategories($this->model)
                        ->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
                        ->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')))
                        ->applyAttributes($this->getOwner()->activeAttributes)
                        ->applyManufacturers($m->id);

                    $count = $model->count();
                    if (!$this->showEmpty && $count) {
                        $data['filters'][] = array(
                            'title' => $m->name,
                            'count' => $count + 999,
                            'queryKey' => 'manufacturer',
                            'queryParam' => $m->id,
                        );
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getCurrentMinPrice()
    {
        if ($this->_currentMinPrice !== null)
            return $this->_currentMinPrice;

        if (Yii::app()->request->getQuery('min_price'))
            $this->_currentMinPrice = Yii::app()->request->getQuery('min_price');
        else
            $this->_currentMinPrice = Yii::app()->currency->convert($this->controller->getMinPrice());

        return $this->_currentMinPrice;
    }

    /**
     * @return mixed
     */
    public function getCurrentMaxPrice()
    {
        if ($this->_currentMaxPrice !== null)
            return $this->_currentMaxPrice;

        if (Yii::app()->request->getQuery('max_price'))
            $this->_currentMaxPrice = Yii::app()->request->getQuery('max_price');
        else
            $this->_currentMaxPrice = Yii::app()->currency->convert($this->controller->getMaxPrice());

        return $this->_currentMaxPrice;
    }

    /**
     * Proxy to CurrencyManager::activeToMain
     * @param $sum
     */
    public function convertCurrency($sum)
    {
        $cm = Yii::app()->currency;
        if ($cm->active->id != $cm->main->id)
            return $cm->activeToMain($sum);
        return $sum;
    }

    public function getCount($filter)
    {
        if ($this->countAttr) {
            $active = $this->getActiveFilters();
            $plus = '';
            if (Yii::app()->request->getParam($filter['queryKey']) && $active) {
                $mass = array();
                /*foreach ($active as $act) {
                    $mass[] = $act['label'];
                }
                if (!in_array($filter['title'], $mass)) {
                    $plus = '+';
                }*/
            }
            $result = ($filter['count'] > 0) ? $filter['count'] : 0;
            return Html::tag($this->tagCount, array(), $plus . $result, true);
        }
    }

}
