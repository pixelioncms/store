<?php

class StatsModule extends WebModule {

    public function init() {

        // parent::init();
        /*
         * Баг, при ошибки на сайте, показывается админская ошибка!
         * Yii::app()->setComponents(array(
          'errorHandler' => array(
          'errorAction' => 'site/errorAdmin',
          ),
          )); */

        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->registerAssets();
        $this->setIcon('icon-stats');
    }

    public function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $this->_assetsUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

    public function afterInstall() {

        /* Yii::app()->settings->set('stats', array(
          'param' => 'param',
          )); */
        Yii::app()->installComponent('stats', 'mod.stats.components.Stats');
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('stats');
        Yii::app()->db->createCommand()->dropTable(StatsSurf::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(StatsMainp::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(StatsMainHistory::model()->tableName());
        Yii::app()->unintallComponent('stats');
        return parent::afterUninstall();
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('StatsModule.default', 'MODULE_NAME'),
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'visible' => Yii::app()->user->openAccess(array('Stats.Default.*', 'Stats.Default.Index')),
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        $a = Yii::app()->controller->action->id;
        return array(
            array(
                'label' => $this->name,
                'url' => $this->adminHomeUrl,
                'active' => ($c == 'admin/default') ? true : false,
                'icon' => Html::icon('icon-stats'),
                'visible' => Yii::app()->user->openAccess(array('Stats.Default.*', 'Stats.Default.Index')),
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'BROWSERS'),
                'url' => array('/admin/stats/browsers'),
                'active' => ($c == 'admin/browsers') ? true : false,
                'icon' => Html::icon('icon-firefox')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'TIMEVISIT'),
                'url' => array('/admin/stats/timevisit'),
                'active' => ($c == 'admin/timevisit') ? true : false,
                'icon' => Html::icon('icon-time')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'PAGEVISIT'),
                'url' => array('/admin/stats/pagevisit'),
                'active' => ($c == 'admin/pagevisit') ? true : false,
                'icon' => Html::icon('icon-monitor-stats')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'ROBOTS'),
                'url' => array('/admin/stats/robots'),
                'active' => ($c == 'admin/robots') ? true : false,
                'icon' => Html::icon('icon-android')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'REF_DOMAIN'),
                'url' => array('/admin/stats/refdomain'),
                'active' => ($c == 'admin/refdomain') ? true : false,
                'icon' => Html::icon('icon-http')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'IP_ADDRESS'),
                'url' => array('/admin/stats/ipaddress'),
                'active' => ($c == 'admin/ipaddress') ? true : false,
                'icon' => Html::icon('icon-ip')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'Поисковые запросы'),
                'url' => array('/admin/stats/searchquery'),
                'active' => ($c == 'admin/searchquery' && $a == 'index') ? true : false,
                'icon' => Html::icon('icon-search')
            ),
            array(
                'label' => Yii::t('StatsModule.default', 'Поисковые системы'),
                'url' => array('/admin/stats/searchquery/system'),
                'active' => ($c == 'admin/searchquery' && $a == 'system') ? true : false,
                'icon' => Html::icon('icon-search')
            ),
        );
    }

}
