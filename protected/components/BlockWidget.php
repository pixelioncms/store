<?php

/**
 *
 * Class BlockWidget
 *
 * @version 1.0
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * @property object $cs clientScript
 * @property string $assetsUrl assetManager
 * @property string $assetsPath assets dir path
 * @property array $registerFile array widget js,css scripts
 * @property array $registerCoreFile array of core js scripts
 *
 * @property string $this->name Название виджета
 * @property string title Заголовок виджета
 * @property string baseDir Абсолютный путь папки виджета.
 * @property array config Настройки виджета.
 */
class BlockWidget extends CWidget
{

    private $cs;
    public $assetsUrl;
    public $assetsPath;
    public $registerFile = array();
    public $registerCoreFile = array();
    public $alias;
    private $_title;

    public function init()
    {


        $class = (new ReflectionClass($this))->getName();



        if (file_exists($this->getBaseDir() . DS . 'assets')) {
            $this->assetsPath = $this->getBaseDir() . DS . 'assets';
            $this->cs = Yii::app()->clientScript;
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
            $this->registerAssets();
        }
        if (file_exists($this->getBaseDir() . DS . 'form')) {
            if (!$this->config) {
                $link = Html::link('настройте виджет.',array('/admin/app/widgets/update','alias'=>$this->alias.'.'.$class));
                echo Yii::app()->tpl->alert('info', 'Пожалуйста, '.$link, false,'m-3');
            }
        }

        // echo $this->getViewPath(false);
    }

    public function getBaseDir()
    {
        $class = new ReflectionClass($this);
        return dirname($class->getFileName());
    }

    public function getTitle()
    {
        if (file_exists($this->getBaseDir() . DS . 'messages')) {
            return $this->_title = Yii::t($this->getName() . '.default', 'TITLE');
        } else {
            if ($this->_title !== null) {
                return $this->_title;
            } else {
                return $this->_title = $this->getName();
            }
        }
    }

    public function setTitle($value)
    {
        $this->_title = $value;
    }

    public function getConfig()
    {
        return Yii::app()->settings->get($this->getName());
    }

    public function getName()
    {
        return get_class($this);
    }

    protected function registerAssets()
    {
        foreach ($this->registerFile as $file) {
            if (preg_match('/[-\w]+\.js/', $file)) {
                $this->cs->registerScriptFile($this->assetsUrl . '/js/' . $file);
            } else {
                $this->cs->registerCssFile($this->assetsUrl . '/css/' . $file);
            }
        }
        foreach ($this->registerCoreFile as $file) {
            $this->cs->registerCoreScript($file);
        }
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


