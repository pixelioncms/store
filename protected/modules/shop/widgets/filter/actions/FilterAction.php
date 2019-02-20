<?php

/**
 * FilterAction class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage mod.shop.actions
 * @uses CAction
 *
 */
class FilterAction extends CAction
{

    public $eavAttributes;
    public $model;
    /**
     * @var string min price in the query
     */
    private $_currentMinPrice = null;

    /**
     * @var string max price in the query
     */
    private $_currentMaxPrice = null;
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            echo Yii::app()->controller->widget('zii.widgets.CMenu', array(
                'htmlOptions' => array('class' => 'current-filter-list'),
                'items' => $this->getActiveFilters()
            ), true);

        } else {
            throw new CHttpException(403);
        }
    }

    /**
     * Get active/applied filters to make easier to cancel them.
     */
    public function getActiveFilters()
    {
        $request = Yii::app()->request;
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();
        $manufacturersIds=false;
        if($request->getQuery('manufacturer'))
            $manufacturersIds = array_filter($request->getQuery('manufacturer'));

        if ($manufacturersIds) {
            $manufacturers = ShopManufacturer::model()
                //->cache($this->cache_time)
                ->findAllByPk($manufacturersIds);
        }
         if ($request->getQuery('min_price')) {
             array_push($menuItems, array(
                 'linkOptions' => array('class' => 'remove'),
                 'label' => Yii::t('ShopModule.default', 'от {minPrice} {c}', array('{minPrice}' => (int)$this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                 'url' => $request->removeUrlParam('shop/category/view', 'min_price')
             ));
         }

         if ($request->getQuery('max_price')) {
             array_push($menuItems, array(
                 'label' => Yii::t('ShopModule.default', 'до {maxPrice} {c}', array('{maxPrice}' => (int)$this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                 'linkOptions' => array('class' => 'remove'),
                 'url' => $request->removeUrlParam('shop/category/view', 'max_price')
             ));
         }

        if (!empty($manufacturersIds)) {
            foreach ($manufacturers as $manufacturer) {
                array_push($menuItems, array(
                    'label' => $manufacturer->name,
                    'linkOptions' => array('class' => 'remove'),
                    'url' => $request->removeUrlParam('/hop/category/view', 'manufacturer', $manufacturer->id)
                ));
            }
        }

        // Process eav attributes
        $activeAttributes = Yii::app()->controller->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset($this->eavAttributes[$attributeName])) {
                    $attribute = $this->eavAttributes[$attributeName];
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            array_push($menuItems, array(
                                'label' => $option->value,
                                'linkOptions' => array('class' => 'remove'),
                                'url' => $request->removeUrlParam('shop/category/view', $attribute->name, $option->id)
                            ));
                        }
                    }
                }
            }
        }

        return $menuItems;
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
}
