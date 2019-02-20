<?php

Yii::import('ext.tinymce.TinymceTrait');

/**
 * languages https://www.tinymce.com/download/language-packages/
 */
class TinymceWidget extends CWidget {

    use TinymceTrait;

    protected $assetsPath;
    protected $assetsUrl;
    public $options = array();
    
    public function init() {
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $assetsName = str_replace("/assets/", "", $this->assetsUrl);
        $moxiemanagerPath = Yii::getPathOfAlias("webroot.assets.{$assetsName}.cms_plugins.moxiemanager");
        CMS::setChmod($moxiemanagerPath . DS . 'api.php', 0640);
        $this->registerScript();
    }

}
