<?php
/**
 * Модуль импорта товаров из файла csv
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage commerce.csv
 * @uses WebModule
 * @version 1.0
 */
class CsvModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
         $this->setIcon('icon-file-csv');
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.','error');
            return false;
        }
    }

    public function getAdminMenu() {
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => $this->getIsActive('csv/default'),
                        'visible'=>Yii::app()->user->checkAccess('Csv.Default.*'),
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
