<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class ProductLabelWidget extends CWidget {

    public $position = 'right';
    public $model;

    public function init() {
        if ($this->model->productLabel) {
            $this->publishAssets();
        }
    }

    public function run() {
        $this->render($this->skin, array('model' => $this->model));
    }

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerCssFile($baseUrl . '/css/productLabel.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
