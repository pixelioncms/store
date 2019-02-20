<?php

/**
 * Модуль управление баннером
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage banner
 * @uses WebModule
 * @version 1.0
 */
class BannerModule extends WebModule {

    public $icon = 'icon-images';

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function afterInstall() {
        if (!file_exists(Yii::getPathOfAlias('webroot.uploads.banner'))) {
            CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.banner'), 0777);
        }
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(Banner::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(BannerTranslate::model()->tableName());
        if (file_exists(Yii::getPathOfAlias('webroot.uploads.banner'))) {
            CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.banner'), array('traverseSymlinks' => true));
        }
        return parent::afterUninstall();
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'visible' => Yii::app()->user->openAccess(array('Banner.Default.*', 'Banner.Default.Index')),
                    // 'active' => self::activeMenu('banner', 'admin/banner'),
                    ),
                ),
            ),
        );
    }

}
