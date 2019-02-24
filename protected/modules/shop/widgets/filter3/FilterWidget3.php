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
class FilterWidget3 extends CWidget
{

    /**
     * 1 = общие кол.
     * 0 = отображает количество в зависимости от выбранных фильтров
     *
     * Need chnage EEavBehavier in function withEavAttributes (getFindByEavAttributesCriteria to getFindByEavAttributesCriteria2)
     *
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
    private $cache_time = 3600 / 2;//3600 / 2;

    /**
     * @var ShopCategory
     */
    public $model;


    public static function actions()
    {
        return array(
            'action' => 'mod.shop.widgets.filter.actions.FilterAction',
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

    /**
     * Render filters
     */
    public function run()
    {

        $this->render($this->skin, array(
            'manufacturers' => ($this->typeFilter) ? $this->getCategoryManufacturers() : $this->getCategoryManufacturers__old(),
            'attributes' => $this->getCategoryAttributes(),
            'prices' => $this->prices
        ));
    }

    /**
     * Get active/applied filters to make easier to cancel them.
     */


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
                'filters' => array(),
                'queryKey' => $attribute->name //NEW by ajax
            );
            foreach ($attribute->options as $option) {
                $count = $this->countAttributeProducts($attribute, $option);
                if ($count) {
                    $data[$attribute->name]['filters'][] = array(
                        'title' => $option->value,
                        'count' => $count,
                        'spec' => $option->spec,
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
        $sql = 'SELECT MAX(date_update) FROM `{{shop_product}}` 
        LEFT JOIN `{{shop_product_attribute_eav}}` ON `{{shop_product_attribute_eav}}`.`entity`=`{{shop_product}}`.`id` 
        LEFT JOIN `{{shop_product_category_ref}}` ON `{{shop_product_category_ref}}`.`product`=`{{shop_product}}`.`id` 
        WHERE `{{shop_product_attribute_eav}}`.`attribute`="' . $attribute->name . '" AND `{{shop_product_attribute_eav}}`.`value`="' . $option->id . '" AND `{{shop_product_category_ref}}`.`category`="' . $this->model->id . '" AND `{{shop_product}}`.`switch`="1"';
        $dependency = new CDbCacheDependency($sql);


        $model = new ShopProduct(null);
        $model->attachBehaviors($model->behaviors());
        //$model->cache($this->cache_time, $dependency);
        $model->published();
        $model->applyCategories($this->model);


        if ($this->typeFilter == 0) {
            $model->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')));
            $model->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));
            if (Yii::app()->request->getParam('manufacturer'))
                $model->applyManufacturers(explode(',', Yii::app()->request->getParam('manufacturer')));


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

        }

        $newData = array();
        $newData[$attribute->name][] = $option->id;

        return $model->withEavAttributes($newData)->count();

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


        $sql = 'SELECT MAX(date_update) FROM `{{shop_product}}`
        LEFT JOIN `{{shop_product_category_ref}}` ON `{{shop_product_category_ref}}`.`product`={{shop_product}}.`id` 
        WHERE `{{shop_product_category_ref}}`.`category`="' . $this->model->id . '" AND `{{shop_product}}`.`switch`="1"';
        $dependency = new CDbCacheDependency($sql);


        //@todo: Fix manufacturer translation
        $mdl = $this->model;
        //$dependency = new CDbCacheDependency('SELECT MAX(date_update) FROM {{shop_product}}');
        //$dependency = new CChainedCacheDependency();
        $manufacturers = ShopProduct::model()
            //->cache($this->cache_time, $dependency)
            ->published()
            ->applyCategories($mdl, null)
            ->with(array(
                'manufacturer' => array(
                    'condition' => '`manufacturer`.`switch`=1',
                    'with' => array(
                        'productsCount' => array(
                            'scopes' => array(
                                'published',
                                'applyCategories' => array($mdl, null),
                                // 'applyAttributes' => array($this->getOwner()->activeAttributes),
                                // 'applyMinPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                // 'applyMaxPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),
                            ))
                    ),
                )))
            ->findAll($cr);

        $data = array(
            'title' => Yii::t('ShopModule.default', 'FILTER_MANUFACTURER'),
            'selectMany' => true,
            'filters' => array()
        );

        if ($manufacturers) {

            foreach ($manufacturers as $m) {
                // var_dump($m->manufacturer->switch);
                //$m = $m->manufacturerActive;
                if ($m->manufacturer->switch) {
                    if ($this->countManufacturer) {
                        $model = new ShopProduct(null);
                        $model->cache($this->cache_time, $dependency);
                        $model->attachBehaviors($model->behaviors());
                        $model->published();

                        $model->applyCategories($this->model);
                        //@todo configure for price filter On/Off
                        if ($this->typeFilter == 0) {
                            $model->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')));
                            $model->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));
                            $model->applyAttributes($this->getOwner()->activeAttributes);
                        }

                        $model->applyManufacturers($m->manufacturer->id);
                        $count = $model->count();
                    } else {
                        $count = 0;
                    }


                    $data['filters'][] = array(
                        'title' => $m->manufacturer->name,
                        'count' => $count,
                        'queryKey' => 'manufacturer',
                        'queryParam' => $m->manufacturer->id,
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
                'manufacturer' => array(
                    'condition' => '`manufacturer`.`switch`=1',
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
                        ->cache($this->cache_time)
                        ->applyCategories($this->model)
                        ->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
                        ->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')))
                        ->applyAttributes($this->getOwner()->activeAttributes)
                        ->applyManufacturers($m->id);

                    $count = $model->count();
                    if ($count) {
                        $data['filters'][] = array(
                            'title' => $m->name,
                            'count' => $count,
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

    //@todo $plus не работает если AJAX
    public function getCount($filter, $checked = false)
    {
        if ($this->countAttr) {
            $active = $this->getOwner()->getActiveFilters();
            $plus = '';
            if (Yii::app()->request->getParam($filter['queryKey']) && $active) {
                $mass = array();
                foreach ($active as $act) {
                    if(isset($act['label']))
                        $mass[] = $act['label'];
                }
                if (!in_array($filter['title'], $mass) && !$checked) {
                        $plus = '+';
                }
            }
            if($checked){
                $result = '';
            }else{
                $result = ($filter['count'] > 0) ? $filter['count'] : 0;
            }

            return Html::tag($this->tagCount, array(), $plus . $result, true);
        }
    }

}
