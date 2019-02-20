<?php

/**
 * Модуль интернет мазагина
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage commerce.shop
 * @uses WebModule
 * @version 1.0
 */
class ShopModule extends WebModule
{
    public $configFiles = array(
        'shop' => 'SettingsShopForm'
    );

    public function init()
    {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        //$this->registerAssets();
        $this->setIcon('icon-shopcart');
    }

    public function registerAssets()
    {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;


        if (Yii::app()->controller->isAdminController) {
            $cs->registerCssFile($this->assetsUrl . '/shop-icon.css');
        }
        if (Yii::app()->settings->get('shop', 'ajax_mode')) {
            $cs->registerScriptFile($this->assetsUrl . '/ajax_mode.js', CClientScript::POS_HEAD);
        } else {
            $cs->registerScriptFile($this->assetsUrl . '/other.js', CClientScript::POS_HEAD);
        }
        // $cs->registerScriptFile($baseUrl . '/common.js', CClientScript::POS_HEAD);
    }

    public function afterInstall()
    {
        if (!file_exists(Yii::getPathOfAlias('webroot.uploads.product')))
            CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.product'), 0777);
        if (!file_exists(Yii::getPathOfAlias('webroot.uploads.manufacturer')))
            CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.manufacturer'), 0777);
        if (!file_exists(Yii::getPathOfAlias('webroot.uploads.categories')))
            CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.categories'), 0777);
        return parent::afterInstall();
    }

    public function afterUninstall()
    {
        Yii::app()->unintallComponent('currency');
        $db = Yii::app()->db;
        $tablesArray = array(
            ShopTypeAttribute::model()->tableName(),
            ShopAttribute::model()->tableName(),
            ShopAttributeOption::model()->tableName(),
            ShopAttributeOptionTranslate::model()->tableName(),
            ShopAttributeTranslate::model()->tableName(),
            ShopCategory::model()->tableName(),
            ShopCategoryTranslate::model()->tableName(),
            ShopCurrency::model()->tableName(),
            ShopManufacturer::model()->tableName(),
            ShopManufacturerTranslate::model()->tableName(),
            ShopProduct::model()->tableName(),
            ShopProductCategoryRef::model()->tableName(),
            ShopProductTranslate::model()->tableName(),
            ShopProductType::model()->tableName(),
            ShopProductVariant::model()->tableName(),
            ShopRelatedProduct::model()->tableName(),
            ShopSuppliers::model()->tableName(),
            $db->tablePrefix . 'shop_product_attribute_eav',
            $db->tablePrefix . 'shop_product_configurable_attributes',
            $db->tablePrefix . 'shop_product_configurations'
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.product'), array('traverseSymlinks' => true));
        CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.categories'), array('traverseSymlinks' => true));
        CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.manufacturer'), array('traverseSymlinks' => true));


        //@todo PANIX сделать удаление модулей.
        /*$modulesList = array('cart','yandexMarket','wishlist','markup','discounts','exchange1c','xml','csv','compare');

        foreach ($modulesList as $module) {
            if(Yii::app()->hasModule($module)){
                $mod = Yii::app()->getModule($module);
                $i = $mod->afterUninstall();
            }
        }*/
        return parent::afterUninstall();
    }

    public function getAddonsArray()
    {
        $a = array();
        $a[] = array(
            'label' => Yii::t('ShopModule.admin', 'CREATE_PRODUCT'),
            'url' => '/admin/shop/products/create',
            'icon' => Html::icon('icon-add', array('class' => 'icon-x4 display-block')),
            'visible' => Yii::app()->user->openAccess(array('Shop.Products.*', 'Shop.Products.Create'))
        );
        return array(
            'mainButtons' => $a
        );
    }

    public function getRules()
    {
        return array(
            '/shop' => array('shop/index/index'),
            'product/<seo_alias>' => array('shop/product/view'),
            'product/captcha' => array('shop/product/captcha'),
            'shop/ajax/activateCurrency/<id>' => array('shop/ajax/activateCurrency'),
            //'shop/ajax/filter' => array('shop/category/ajaxFilter'),
            'shop/ajax/rating/<id>' => array('shop/ajax/rating'),
            'shop/index/renderProductsBlock/<scope>' => array('shop/index/renderProductsBlock'),
            'shop/updateSorting' => array('shop/category/updateSorting'),
            //'shop/test' => array('shop/category/test'),


            // 'discount' => array('shop/category/discount'),
            'discount' => array('shop/category/discount2'),
            // 'shop/ajax/<action>' => 'shop/category/<action>', // dotted for actions widget.<name>
            // 'shop/ajax/<action>/*' => 'shop/category/<action>',
            'products/search/*' => array('shop/category/search'),
            //'manufacturer' => array('shop/manufacturer/index'),
            //'manufacturer/<seo_alias>' => array('shop/manufacturer/view'),

            array(
                'class' => 'mod.shop.components.ShopCategoryUrlRule'
            ),
            array(
                'class' => 'mod.shop.components.ShopBrandsUrlRule'
            ),
            '/shop/currentFilter' => array('shop/category/currentFilter'),
            '/shop/currentFilter/*' => array('shop/category/currentFilter'),

            '/shop/<action:[\w]+>' => 'shop/category/<action>',
            '/shop/<action:[\w]>/*' => 'shop/category/<action>',

        );
    }

    public function getAdminMenu()
    {
        return array(
            'shop' => array(
                'label' => $this->name,
                'visible' => Yii::app()->user->openAccess(array('Shop.Products.*', 'Shop.Products.Index')),
                'icon' => Html::icon($this->icon),
                'items' => array(
                    array(
                        'label' => Yii::t('ShopModule.admin', 'PRODUCTS'),
                        'url' => array('/admin/shop/products/index'),
                        'active' => $this->getIsActive('shop/products'),
                        'icon' => Html::icon('icon-shopcart'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Products.*', 'Shop.Products.Index'))
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'CATEGORIES'),
                        'url' => array('/shop/admin/category/create'),
                        'active' => $this->getIsActive('shop/category'),
                        'icon' => Html::icon('icon-folder-open'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.Index'))
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'BRANDS'),
                        'url' => array('/shop/admin/manufacturer'),
                        'active' => $this->getIsActive('shop/manufacturer'),
                        'icon' => Html::icon('icon-apple'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Manufacturer.*', 'Shop.Manufacturer.Index'))
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'ATTRIBUTES'),
                        'url' => array('/shop/admin/attribute'),
                        'active' => $this->getIsActive('shop/attribute'),
                        'icon' => Html::icon('icon-filter'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Attribute.*', 'Shop.Attribute.Index'))
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'TYPE_PRODUCTS'),
                        'url' => array('/shop/admin/productType'),
                        'active' => $this->getIsActive('shop/productType'),
                        'icon' => Html::icon('icon-t'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.ProductType.*', 'Shop.ProductType.Index'))
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'CURRENCY'),
                        'url' => array('/shop/admin/currency'),
                        'active' => $this->getIsActive('shop/currency'),
                        'icon' => Html::icon('icon-currencies'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Currency.*', 'Shop.Currency.Index'))
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'SUPPLIERS'),
                        'url' => array('/shop/admin/suppliers'),
                        'active' => $this->getIsActive('shop/suppliers'),
                        'icon' => Html::icon('icon-supplier'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Suppliers.*', 'Shop.Suppliers.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => array('/shop/admin/settings'),
                        'active' => $this->getIsActive('shop/settings'),
                        'icon' => Html::icon('icon-settings'),
                        'visible' => Yii::app()->user->openAccess(array('Shop.Settings.*', 'Shop.Settings.Index'))
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu()
    {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu($this->id);
        return $items['items'];
    }

    public function getVersion()
    {
        return '1.0 (shell) PRO';
    }

}
