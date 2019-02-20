<?php

Yii::import('mod.shop.ShopModule');

/**
 * Модуль скидок товаров
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules
 * @subpackage commerce.discounts
 * @uses WebModule
 */
class DiscountsModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-discount');
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.', 'error');
            return false;
        }
    }

    public function afterUninstall() {
        $db = Yii::app()->db;
        $tablesArray = array(
            ShopDiscount::model()->tableName(),
            $db->tablePrefix . 'shop_discount_category',
            $db->tablePrefix . 'shop_discount_manufacturer'
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        GridColumns::model()->deleteAll("grid_id='shopdiscount-grid'");
        return parent::afterUninstall();
    }

    public function getAdminMenu() {
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'active' => $this->getIsActive('discounts/default'),
                        'icon' => Html::icon($this->icon),
                        'visible'=>Yii::app()->user->openAccess(array('Discounts.Default.*','Discounts.Default.Index')),
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

}
