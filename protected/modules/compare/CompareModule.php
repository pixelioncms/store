<?php

Yii::import('mod.shop.ShopModule');

/**
 * Модуль сравнение товаров
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules
 * @subpackage commerce.compare
 * @uses WebModule
 */
class CompareModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.components.*',
            $this->id . '.forms.*',
        ));
        $this->setIcon('icon-compare');
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.', 'error');
            return false;
        }
    }

    public function getRules() {
        return array(
            'compare' => array('compare/default/index'),
            'compare/category/<cat_id>' => array('compare/default/index'),
            'compare/add/<id>' => array('compare/default/add'),
            'compare/remove/<id>' => array('compare/default/remove'),
        );
    }

    public function getVersion() {
        return '1.0 PRO';
    }

}
