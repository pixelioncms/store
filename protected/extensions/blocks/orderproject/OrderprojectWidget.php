<?php

/**
 * CallbackWidget class file.
 *
 * @author PIXELION CMS development team <info@pixelion-cms.com>
 * @license http://pixelion.com.ua/license.txt PIXELION CMS License
 * @link http://pixelion.com PIXELION CMS
 * @package ext
 * @subpackage callback
 * @uses CWidget
 */
class OrderprojectWidget extends BlockWidget {

    public $alias = 'ext.blocks.orderproject';

    public function getTitle() {
        return Yii::t('OrderprojectWidget.default', 'TITLE');
    }

    public static function actions() {
        return array(
            'action' => 'ext.blocks.orderproject.actions.OrderprojectAction',
        );
    }
    public $btn_title = false;
   // protected $assetsPath;
   // protected $assetsUrl;

    public function init() {
        $this->btn_title = (!$this->btn_title) ? Yii::t('OrderprojectWidget.default', 'BUTTON') : $this->btn_title;
        parent::init();
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $this->registerClientScript();
    }

    public function run() {
        $this->render($this->skin);
    }


    protected function registerClientScript() {
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/js/orderproject.js', CClientScript::POS_END);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}