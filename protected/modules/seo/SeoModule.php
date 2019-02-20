<?php

/**
 * Модуль SEO
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules
 * @subpackage seo
 * @uses WebModule
 */
class SeoModule extends WebModule
{

    //public $access = 0;
    public $_assetsUrl;

    public $configFiles = array(
        'seo' => 'SettingsSeoForm'
    );

    public function init()
    {

        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-seo-monitor');
    }

    public function afterUninstall()
    {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(Redirects::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(SeoMain::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(SeoParams::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(SeoUrl::model()->tableName());
        return parent::afterUninstall();
    }

    public function getAdminMenu()
    {
        $c = Yii::app()->controller->id;
        return array(
            'system' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => ($c == 'admin/seo') ? true : false,
                        'visible' => Yii::app()->user->openAccess(array('Seo.Default.*', 'Seo.Default.Index')),
                    ),
                ),
            )
        );
    }

}
