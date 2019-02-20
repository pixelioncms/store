<?php

/**
 * Widget class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CWidget
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class Widget extends CWidget
{

    public $title;
    public $cs;
    public $assetsUrl;
    public $assetsPath;
    public $registerFile = array();
    public $registerCoreFile = array();
    public $min;

    public function init()
    {
        $className = get_class($this);

        //if (!isset($this->assetsPath))
        //    throw new Exception($className . ': не могу определить пусть к ресурсам assetsPath.');

        $this->min = (YII_DEBUG) ? '' : '.min';
        $this->cs = Yii::app()->clientScript;
        if($this->assetsPath){
            $this->assetsUrl = Yii::app()->getAssetManager()->publish($this->assetsPath, false, -1, YII_DEBUG);
            $this->registerAssets();
        }
        $basename = basename($this->getBaseDir());
        $skins = array(
            "current_theme.views._widgets.{$basename}.{$this->skin}",
            $this->skin,
        );
        foreach ($skins as $skin) {
            if (file_exists(Yii::getPathOfAlias($skin) . '.php')) {
                $this->skin = $skin;
                break;
            }
        }

    }

    protected function registerAssets()
    {
        foreach ($this->registerFile as $file) {
            if (preg_match('/[-\w]+\.js/', $file)) {
                $this->cs->registerScriptFile($this->assetsUrl . '/js/' . $file, CClientScript::POS_END);
            } elseif (preg_match('/[-\w]+\.css/', $file)) {
                $this->cs->registerCssFile($this->assetsUrl . '/css/' . $file);
            }
        }
        foreach ($this->registerCoreFile as $file) {
            $this->cs->registerCoreScript($file);
        }
    }

    public function getBaseDir()
    {
        $class = new ReflectionClass($this);
        return dirname($class->getFileName());
    }

    public function getViewFile($viewName)
    {


        $class = get_class($this);
        $basename = basename($this->getBaseDir());
        if (($renderer = Yii::app()->getViewRenderer()) !== null)
            $extension = $renderer->fileExtension;
        else
            $extension = '.php';

        if (file_exists(Yii::getPathOfAlias("current_theme.views._widgets.{$basename}.{$viewName}") . $extension)) {
            $viewFile = Yii::getPathOfAlias("current_theme.views._widgets.{$basename}.{$viewName}");
            //$this->skin = "current_theme.views._widgets.{$basename}.{$viewName}";
            return Yii::app()->findLocalizedFile($viewFile . '.php');
        }

        if (strpos($viewName, '.')) // a path alias
            $viewFile = Yii::getPathOfAlias($viewName);
        else {
            $viewFile = $this->getViewPath(true) . DS . $viewName;
            if (is_file($viewFile . $extension))
                return Yii::app()->findLocalizedFile($viewFile . $extension);
            elseif ($extension !== '.php' && is_file($viewFile . '.php'))
                return Yii::app()->findLocalizedFile($viewFile . '.php');
            $viewFile = $this->getViewPath(false) . DS . $viewName;
        }
        if (is_file($viewFile . $extension)) {


            return Yii::app()->findLocalizedFile($viewFile . $extension);
        } elseif ($extension !== '.php' && is_file($viewFile . '.php'))
            return Yii::app()->findLocalizedFile($viewFile . '.php');
        else
            return false;
    }
}


