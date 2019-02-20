<?php

Yii::import('mod.shop.ShopModule');

/**
 * Модуль списка желаний
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage commerce.wishlist
 * @uses WebModule
 */
class WishlistModule extends WebModule {
    public $enable_guest = true;
    /**
     * инициализация модуля
     */
    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-heart');
    }

    /**
     * Установка модуля
     * @return boolean
     */
    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.', 'error');
            return false;
        }
    }

    /**
     * Удаление модуля
     * @return boolean
     */
    public function afterUninstall() {
        $db = Yii::app()->db;
        $tablesArray = array(
            Wishlist::model()->tableName(),
            WishlistProducts::model()->tableName(),
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        return parent::afterUninstall();
    }

    /**
     * UrlManager rules
     * @return array
     */
    public function getRules() {
        return array(
            'wishlist' => array('wishlist/default/index'),
            'wishlist/add/<id>' => array('wishlist/default/add'),
            'wishlist/remove/<id>' => array('wishlist/default/remove'),
            'wishlist/view/<key>' => array('wishlist/default/view'),
        );
    }

}
