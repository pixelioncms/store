<?php

class FilterHelper {

    public $owner;

    public function __construct($owner) {
        $this->owner = $owner;
    }

    public function getActiveFilters() {
        $request = Yii::app()->request;
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();
        $manufacturers = array_filter(explode(',', $request->getQuery('manufacturer')));
        $manufacturers = ShopManufacturer::model()
                //->cache($this->controller->cacheTime)
                ->findAllByPk($manufacturers);

        if ($request->getQuery('min_price')) {
            array_push($menuItems, array(
                'linkOptions' => array('class' => 'remove'),
                'label' => Yii::t('ShopModule.defauli', 'от {minPrice} {c}', array('{minPrice}' => (int) $this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'url' => $request->removeUrlParam('/shop/category/view', 'min_price')
            ));
        }

        if ($request->getQuery('max_price')) {
            array_push($menuItems, array(
                'label' => Yii::t('ShopModule.defauli', 'до {maxPrice} {c}', array('{maxPrice}' => (int) $this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove'),
                'url' => $request->removeUrlParam('/shop/category/view', 'max_price')
            ));
        }

        if (!empty($manufacturers)) {
            foreach ($manufacturers as $manufacturer) {
                array_push($menuItems, array(
                    'label' => $manufacturer->name,
                    'linkOptions' => array('class' => 'remove'),
                    'url' => $request->removeUrlParam('/shop/category/view', 'manufacturer', $manufacturer->id)
                ));
            }
        }

        // Process eav attributes
        $activeAttributes = $this->owner->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset($this->owner->eavAttributes[$attributeName])) {
                    $attribute = $this->owner->eavAttributes[$attributeName];
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            array_push($menuItems, array(
                                'label' => $option->value,
                                'linkOptions' => array('class' => 'remove'),
                                'url' => $request->removeUrlParam('/shop/category/view', $attribute->name, $option->id)
                            ));
                        }
                    }
                }
            }
        }

        return $menuItems;
    }

}
