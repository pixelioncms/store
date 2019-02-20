<?php

class Exchange1cModule extends WebModule
{
    public $configFiles = array(
        'exchange1c' => 'SettingsExchange1cForm'
    );

    public function init()
    {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-1c');
    }

    public function afterInstall()
    {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.', 'error');
            return false;
        }
    }

    public function afterUninstall()
    {
        $db = Yii::app()->db;
        $db->createCommand()->dropTable('{{exchange1c}}');
        return parent::afterUninstall();
    }

    public function getRules()
    {
        return array(
            'exchange1c/<password>' => 'exchange1c/default/index',
            'exchange1c/<password>/*' => 'exchange1c/default/index',
        );
    }

    public function getAdminMenu()
    {
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'active' => $this->getIsActive('exchange1c/default'),
                        'icon' => Html::icon($this->icon),
                        'visible' => Yii::app()->user->openAccess(array('Exchange1c.Default.*', 'Exchange1c.Default.Index')),
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu()
    {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

}
