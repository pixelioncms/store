<?php

class SliderWidget extends CWidget {

    protected $assetsPath;
    protected $assetsUrl;
    public $cs = true;

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

    public function run() {
        Yii::import('mod.banner.models.*');
        $model = Banner::model()->published()->findAll();

        $this->render($this->skin, array('model' => $model));
    }

    protected function registerClientScript() {
        if($this->cs){
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/js/jssor.slider.mini.js', CClientScript::POS_END);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
        }
    }

}