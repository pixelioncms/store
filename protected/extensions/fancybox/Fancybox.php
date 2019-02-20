<?php

/**
 * FancyBox widget class file.
 *
 */
class Fancybox extends CWidget
{

    public $id;

    /**
     * the taget element on DOM
     * @var string
     */
    public $target;

    /**
     * settings for fancybox
     * @var array
     */
    public $config = array();

    /**
     * init widget
     */
    public function init()
    {
        // if not informed will generate Yii defaut generated id, since version 1.6
        if (!isset($this->id))
            $this->id = $this->getId();
        // publish the required assets
        $this->publishAssets();
    }

    /**
     * run the widget
     */
    public function run()
    {
        if (!isset($this->config['lang'])) {
            $this->config['lang'] = Yii::app()->language;
            $this->config['i18n'][Yii::app()->language]['CLOSE'] = Yii::t('Fancybox.default','CLOSE');
            $this->config['i18n'][Yii::app()->language]['NEXT'] = Yii::t('Fancybox.default','NEXT');
            $this->config['i18n'][Yii::app()->language]['PREV'] = Yii::t('Fancybox.default','PREV');
            $this->config['i18n'][Yii::app()->language]['ERROR'] = Yii::t('Fancybox.default','ERROR');
            $this->config['i18n'][Yii::app()->language]['PLAY_START'] = Yii::t('Fancybox.default','PLAY_START');
            $this->config['i18n'][Yii::app()->language]['PLAY_STOP'] = Yii::t('Fancybox.default','PLAY_STOP');
            $this->config['i18n'][Yii::app()->language]['FULL_SCREEN'] = Yii::t('Fancybox.default','FULL_SCREEN');
            $this->config['i18n'][Yii::app()->language]['THUMBS'] = Yii::t('Fancybox.default','THUMBS');
            $this->config['i18n'][Yii::app()->language]['DOWNLOAD'] = Yii::t('Fancybox.default','DOWNLOAD');
            $this->config['i18n'][Yii::app()->language]['SHARE'] = Yii::t('Fancybox.default','SHARE');
            $this->config['i18n'][Yii::app()->language]['ZOOM'] = Yii::t('Fancybox.default','ZOOM');
        }
        $config = CJavaScript::encode($this->config);
        Yii::app()->clientScript->registerScript($this->getId(), "$('$this->target').fancybox($config);", CClientScript::POS_END);
    }

    /**
     * function to publish and register assets on page
     * @throws Exception
     */
    public function publishAssets()
    {
        $assets = dirname(__FILE__) . '/assets';
        $min = (YII_DEBUG) ? '' : '.min';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . "/jquery.fancybox{$min}.js", CClientScript::POS_END);
            Yii::app()->clientScript->registerCssFile($baseUrl . "/jquery.fancybox{$min}.css");
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}