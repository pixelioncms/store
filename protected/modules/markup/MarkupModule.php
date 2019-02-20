<?php

Yii::import('mod.shop.ShopModule');

/**
 * Модуль наценок товаров
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules
 * @subpackage commerce.markup
 * @uses WebModule
 */
class MarkupModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-price-house');
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
            ShopMarkup::model()->tableName(),
            $db->tablePrefix . 'shop_markup_category',
            $db->tablePrefix . 'shop_markup_manufacturer'
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        return parent::afterUninstall();
    }

    public function getAdminMenu() {
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'active' => $this->getIsActive('markup/default'),
                        'icon' => Html::icon($this->icon),
                        'visible'=>Yii::app()->user->openAccess(array('Markup.Default.*','Markup.Default.Index'))
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
