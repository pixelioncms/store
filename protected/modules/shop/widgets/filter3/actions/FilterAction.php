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
        $manufacturersIds = array_filter(explode(',', $request->getQuery('manufacturer')));

        if ($manufacturersIds) {
            $manufacturers = ShopManufacturer::model()
                //->cache($this->cache_time)
                ->findAllByPk($manufacturersIds);
        }
         if ($request->getQuery('min_price')) {
             array_push($menuItems, array(
                 'linkOptions' => array('class' => 'remove'),
                 'label' => Yii::t('ShopModule.default', 'от {minPrice} {c}', array('{minPrice}' => (int)$this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                 'url' => $request->removePostUrlParam('shop/category/view', 'min_price')
             ));
         }

         if ($request->getQuery('max_price')) {
             array_push($menuItems, array(
                 'label' => Yii::t('ShopModule.default', 'до {maxPrice} {c}', array('{maxPrice}' => (int)$this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                 'linkOptions' => array('class' => 'remove'),
                 'url' => $request->removePostUrlParam('shop/category/view', 'max_price')
             ));
         }

        if (!empty($manufacturersIds)) {
            foreach ($manufacturers as $manufacturer) {
                array_push($menuItems, array(
                    'label' => $manufacturer->name,
                    'linkOptions' => array('class' => 'remove'),
                    'url' => $request->removePostUrlParam('/hop/category/view', 'manufacturer', $manufacturer->id)
                ));
            }
        }

        // Process eav attributes
        $activeAttributes = Yii::app()->controller->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset(Yii::app()->controller->eavAttributes[$attributeName])) {
                    $attribute = Yii::app()->controller->eavAttributes[$attributeName];
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            array_push($menuItems, array(
                                'label' => $option->value,
                                'linkOptions' => array('class' => 'remove'),
                                'url' => $request->removePostUrlParam('shop/category/view', $attribute->name, $option->id)
                            ));
                        }
                    }
                }
            }
        }

        return $menuItems;
    }

}
