<?php

class TopButtomWidget extends CWidget {

    // getAssetsUrl()
    //    return the URL for this widget's assets, performing the publish operation
    //    the first time, and caching the result for subsequent use.
    private $_assetsUrl;
    public $minDepth;
    public $minHeight;
    public $fadeInTime;
    public $fadeOutTime;
    public $opacity;
    public $scrollTopTime;
    public $scrollBottomTime;
    public $enableTop=true;
    public $enableBottom=false;

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null) {
            $file = dirname(__FILE__) . DS . 'assets';
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish($file, false, -1, YII_DEBUG);
        }

        return $this->_assetsUrl;
    }

    public function init() {
        if (!isset($this->minDepth))
            $this->minDepth = 1000;
        if (!isset($this->minHeight))
            $this->minHeight = 500;
        if (!isset($this->fadeInTime))
            $this->fadeInTime = 700;
        if (!isset($this->fadeOutTime))
            $this->fadeOutTime = 700;
        if (!isset($this->opacity))
            $this->opacity = 0;
        if (!isset($this->scrollTopTime))
            $this->scrollTopTime = 1000;
        if (!isset($this->scrollBottomTime))
            $this->scrollBottomTime = 1000;

        $this->render($this->skin);
    }

}
