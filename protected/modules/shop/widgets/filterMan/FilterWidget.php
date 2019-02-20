<?php

/**
 * Base class to render filters by:
 *  Manufacturer
 *  Price
 *  Eav attributes
 *
 * Usage:
 * $this->widget('application.modules.shop.widgets.SFilterRenderer', array(
 *      // ShopCategory model. Used to create url
 *      'model'=>$model,
 *  ));
 *
 * @method CategoryController getOwner()
 */
class FilterWidget extends CWidget
{

    private $typeFilter = 1;
    public $showEmpty = false;

    /**
     * @var array of ShopAttribute models
     */
    public $attributes;
    public $countAttr = true;

    public $prices = array();

    /**
     * @var ShopCategory
     */
    public $model;

    /**
     * @var string default view to render results
     */
    public $view = 'default';

    /**
     * @var string min price in the query
     */
    private $_currentMinPrice = null;

    /**
     * @var string max price in the query
     */
    private $_currentMaxPrice = null;

    public function getMinPrice()
    {
        return $this->controller->getMinPrice();
    }

    public function getMaxPrice()
    {
        return $this->controller->getMaxPrice();
    }

    public $dependency;

    public function init()
    {
        $this->dependency = new CDbCacheDependency('SELECT MAX(date_update) FROM {{shop_product}}');
    }

    /**
     * Render filters
     */
    public function run()
    {
        die('filterMain');
        $this->render($this->view, array(
            'attributes' => $this->getCategoryAttributes(),
            'prices' => $this->prices
        ));
    }

    /**
     * Get active/applied filters to make easier to cancel them.
     */
    public function getActiveFilters()
    {
        $request = Yii::app()->request;
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();

        if ($request->getQuery('min_price')) {
            array_push($menuItems, array(
                'linkOptions' => array('class' => 'remove'),
                'label' => Yii::t('ShopModule.default', 'от {minPrice} {c}', array('{minPrice}' => (int)$this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'url' => $request->removeUrlParam('/shop/manufacturer/view', 'min_price')
            ));
        }

        if ($request->getQuery('max_price')) {
            array_push($menuItems, array(
                'label' => Yii::t('ShopModule.default', 'до {maxPrice} {c}', array('{maxPrice}' => (int)$this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove'),
                'url' => $request->removeUrlParam('/shop/manufacturer/view', 'max_price')
            ));
        }


        // Process eav attributes
        $activeAttributes = $this->getOwner()->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset($this->getOwner()->eavAttributes[$attributeName])) {
                    $attribute = $this->getOwner()->eavAttributes[$attributeName];
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            array_push($menuItems, array(
                                'label' => $option->value,
                                'linkOptions' => array('class' => 'remove'),
                                'url' => $request->removeUrlParam('/shop/manufacturer/view', $attribute->name, $option->id)
                            ));
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
        //$data = array();
        //$cacheId = 'filters' . $this->model->id . Yii::app()->request->url; //@todo use this if filterType 2
        $cacheId = 'filters' . $this->model->id;
        $data = Yii::app()->cache->get($cacheId);

        if ($data === false) {
            foreach ($this->attributes as $attribute) {

                $data[$attribute->name] = array(
                    'title' => $attribute->title,
                    'selectMany' => (boolean)$attribute->select_many,
                    'filters' => array()
                );
                foreach ($attribute->options as $option) {
                    //@todo no used countAttr
                    //  $count=1;
                    $count = ($this->typeFilter) ? $this->countAttributeProducts($attribute, $option) : $this->countAttributeProducts__old($attribute, $option);
                    if ($count > 0) {
                        $data[$attribute->name]['filters'][] = array(
                            'title' => $option->value,
                            'count' => $count,
                            'queryKey' => $attribute->name,
                            'queryParam' => $option->id,
                        );
                    }
                }

            }

            Yii::app()->cache->set($cacheId, $data, 86400); //Yii::app()->settings->get('app', 'cache_time')
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
        $model = new ShopProduct(null);
        $model->attachBehaviors($model->behaviors());
        $model->published();
        $model->cache(86400, $this->dependency);
         $model->applyManufacturers($this->model->id);
        //$model->applyCategories($this->model);

        $newData = array();

        $newData[$attribute->name][] = $option->id;

        return $model->withEavAttributes($newData)->count();
    }

    public function countAttributeProducts__old($attribute, $option)
    {
        //if ($this->countAttr) {


        $model = new ShopProduct(null);
        $model->attachBehaviors($model->behaviors());
            $model->published()
            ->cache(86400, $this->dependency)
           // ->applyCategories($this->model)
           ->applyManufacturers($this->model->id);
            //->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
            //->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));



        $data = array($attribute->name => $option->id);
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

        $newData[$attribute->name][] = $option->id;


        return $model->withEavAttributes($newData)->count();
        //}
    }

    /**
     * @return array of category manufacturers
     */

    public function manufacturerSort($a, $b)
    {
        return strnatcmp($a['title'], $b['title']);
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
        $result = ($filter['count'] > 0) ? $filter['count'] : 0;
        return Html::tag('sup', array(), $result, true);
    }

}
