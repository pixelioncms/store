<?php

/**
 * Модуль стратичных страниц
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.pages
 * @uses WebModule
 */
class PagesModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
        $this->setIcon('icon-edit');
    }

    public function afterInstall() {
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(Page::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(PageTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'page/<url>' => 'pages/default/index',
        );
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => $this->getIsActive('admin/pages'),
                        'visible' => Yii::app()->user->openAccess(array('Pages.Default.*', 'Pages.Default.Index'))
                    ),
                ),
            ),
        );
    }

    public function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('PagesModule.default', 'CREATE'),
                    'url' => array('/admin/pages/default/create'),
                    'icon' => Html::icon(Yii::app()->getModule('pages')->icon, array('class' => 'icon-x4 display-block')),
                    'visible' => Yii::app()->user->openAccess(array('Pages.Default.*', 'Pages.Default.Update'))
                )
            )
        );
    }

}
