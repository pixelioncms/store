<?php

/**
 * Приложение системы.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package app
 * @uses CWebApplication
 */
class Application extends CWebApplication
{

    private $_theme = null;

    /**
     * @param null $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
    }

    public function getVersion()
    {
        return '1.0.1';
    }


    private function addComponents()
    {
        $components = ComponentsModel::model()
            ->findAll();

        if ($components) {
            $compArray = array();
            foreach ($components as $component) {
                $compArray[$component->name] = array(
                    'class' => $component->class
                );
            }
            $this->setComponents($compArray);
        }
    }

    public function getCopyright()
    {
        return Yii::t('app', 'COPYRIGHT_APP', array(
            '{year}' => date('Y'),
            '{v}' => Yii::app()->getVersion(),
            '{site_name}' => Html::link($this->name, '//pixelion.com.ua', array('title' => $this->name, 'target' => '_blank'))
        ));
    }

    public function installComponent($name, $class)
    {

        if (isset($class) && isset($name)) {
            if (ComponentsModel::model()->countByAttributes(array('name' => $name)) == 0) {
                // if (file_exists(Yii::getPathOfAlias($alias_wgt) . '.php')) {
                $w = new ComponentsModel();
                $w->class = $class;
                $w->name = $name;
                if ($w->validate()) {
                    if (!$w->save(false, false)) {
                        die('error save');
                    }
                } else {
                    throw new CException(Yii::t('exception', 'SET_WIDGET_ERR_VALID', array('{name}' => $name, '{class}' => $class)));
                }
                // } else {
                //     throw new CException(Yii::t('app', 'SET_WIDGET_NOTFOUND'));
                // }
            } else {
                throw new CException(Yii::t('exception', 'SET_WIDGET_ALREADY_EXISTS'));
            }
        } else {
            throw new CException(Yii::t('exception', 'SET_WIDGET_ERR'));
        }
    }

    public function unintallComponent($name=false)
    {
        if ($name) {
            if ($this->getComponent($name)) {
                $c = ComponentsModel::model()->findByAttributes(array('name' => $name));
                if (isset($c)) {
                    $c->delete();
                }
            }
        }
    }

    /**
     * Initialize component
     */
    public function init()
    {
        $this->addComponents();
        $this->setEngineModules();
        parent::init();
    }

    /**
     * Set enabled system modules to enable url access.
     */
    protected function setEngineModules()
    {
        $mods = ModulesModel::getEnabled();
        if ($mods) {
            foreach ($mods as $module) {
                $this->setModules(array($module->name => array(
                    'access' => $module->access
                )
                ));
            }
        }
    }

    /**
     * @return CTheme
     */
    public function getTheme()
    {
        $globConfig = Yii::app()->settings->get('app');
        if ($this->_theme === null) {
            if (Yii::app()->settings->get('users', 'change_theme')) {
                if (isset(Yii::app()->user->theme)) {
                    $theme = Yii::app()->user->theme;
                } else {
                    $theme = $globConfig->theme;
                }
            } else {
                $theme = $globConfig->theme;
            }
            $this->_theme = Yii::app()->themeManager->getTheme($theme);
        }
        return $this->_theme;
    }


}
