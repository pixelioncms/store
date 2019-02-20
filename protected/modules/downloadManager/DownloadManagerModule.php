<?php

/**
 * Модуль менеджер загрузок.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules
 * @subpackage downloadManger
 * @uses WebModule
 * @version 1.0
 */
class DownloadManagerModule extends WebModule {

    public $edit_mode = true;
    public $_addonsArray;

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*'
        ));
        $this->setIcon('icon-download');
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' =>  $this->getIsActive('admin/downloadManager'),
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

}
