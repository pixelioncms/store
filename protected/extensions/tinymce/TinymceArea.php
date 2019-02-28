<?php

Yii::import('ext.tinymce.TinymceTrait');

/**
 * languages https://www.tinymce.com/download/language-packages/
 */
class TinymceArea extends CInputWidget {

    use TinymceTrait;

    protected $assetsPath;
    protected $assetsUrl;
    public $options = array();

    public function run() {

        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }

        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = $this->htmlOptions['class'];
        else
            $this->htmlOptions['class'] = 'editor';

        if ($this->hasModel())
            echo Html::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textArea($name, $this->value, $this->htmlOptions);

        $assetsName = str_replace("/assets/", "", $this->assetsUrl);
        $moxiemanagerPath = Yii::getPathOfAlias("webroot.assets.{$assetsName}.cms_plugins.moxiemanager");
        CMS::setChmod($moxiemanagerPath . DS . 'api.php', 0666);

        $this->registerScript();
    }

}
