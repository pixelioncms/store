<?php

/**
 * EditGridColumnsWidget class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList
 * @uses CWidget
 */
class EditGridColumnsWidget extends CWidget {

    public static function actions() {
        return array(
            'editGridColumns' => 'ext.adminList.actions.EditGridColumsAction',
        );
    }

    public $grid_id;
    public $model;
    public $module;
    protected $assetsPath;
    protected $assetsUrl;

    public function init() {
        parent::init();
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $this->registerClientScript();
    }

    protected function registerClientScript() {
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/editgridcolums.js', CClientScript::POS_BEGIN);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
