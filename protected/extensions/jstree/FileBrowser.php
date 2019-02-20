<?php

class FileBrowser extends CWidget {


    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * Init widget
     */
    public function init() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG
        );
        $this->cs = Yii::app()->getClientScript();
      //  $this->cs->registerCoreScript('cookie');
        $this->cs->registerScriptFile($assetsUrl . '/jstree.min.js');
        $this->cs->registerCssFile($assetsUrl . '/themes/default/style.min.css');
        $this->cs->registerCssFile($assetsUrl . '/themes/default/filebrowser.css');
        
    }

    public function run() {
        Yii::import('mod.admin.components.fs');
        $fs = new fs(Yii::getPathOfAlias('webroot.themes'));

    }

  
}
