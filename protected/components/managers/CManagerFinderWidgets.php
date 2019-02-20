<?php

/**
 * CManagerFinderWidgets
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @uses CComponent
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class CManagerFinderWidgets extends CComponent {

    protected $widgetList = array();
    protected $data = array();
    public $cache_key = 'cache_widgets';

    public function init() {
        if (Yii::app()->hasComponent('settings')) {
            $cacheTime = Yii::app()->settings->get('app', 'cache_time');
            $this->data = Yii::app()->cache->get($this->cache_key);
            if (!$this->data) {
                $data = array();
                foreach ($this->getWidgetsSystem() as $key => $w) {
                    $data['system'][$key] = $w;
                }
                foreach ($this->getBlocksModules() as $key => $w2) {
                    $data['module'][$key] = $w2;
                }

                $this->data = $data;

                Yii::app()->cache->set($this->cache_key, $this->data, $cacheTime);
            }
        }
    }

    private function getBlocksModules() {
        $result = array();
        foreach (Yii::app()->getModules() as $module) {
            $expName = explode('.', $module['class']); //разбиваем строку на массив
            $modname = $expName[0]; // получаем название модуля
            if (!in_array($modname, ModulesModel::$denieMods)) { // запрещаем выводить приватные модулю
                $path = Yii::getPathOfAlias("mod.{$modname}.blocks");
                if (is_dir($path)) {
                    $file = CFileHelper::findFiles($path, array(
                                'level' => 1,
                                'fileTypes' => array('php'),
                                'absolutePaths' => false
                                    )
                    );

                    Yii::import("mod.{$modname}." . ucfirst($modname) . "Module");
                    foreach ($file as $f) {
                        $rep = str_replace('.php', '', $f);
                        $expFile = explode(DS, $rep);
                        $dir = $expFile[0];
                        $name = $expFile[1];
                        $alias = "mod.{$modname}.blocks.{$dir}.{$name}";

                        Yii::import($alias);
                        $class = new $name;
                        if ($class instanceof BlockWidget) {
                            $this->setWidgetSettings("mod.{$modname}.blocks.{$dir}.form");
                            $result[$alias] = $class->getTitle();
                        } else {
                            $result[$alias] = $name;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Системные виджеты
     * @return array
     */
    public function getWidgetsSystem() {
        $result = array();
        $extFiles = CFileHelper::findFiles(Yii::getPathOfAlias('ext.blocks'), array(
                    'level' => 1,
                    'fileTypes' => array('php'),
                    'absolutePaths' => false
                        )
        );
        foreach ($extFiles as $file) {
            $rep = str_replace('.php', '', $file);
            $expFile = explode(DS, $rep);
            $dir = $expFile[0];
            $name = $expFile[1];
            $alias = "ext.blocks.{$dir}.{$name}";

            Yii::import($alias);
            $class = new $name;
            if ($class instanceof BlockWidget) {
                $this->setWidgetSettings("ext.blocks.{$dir}.form");


                $data = array(
                    'name' => $class->getTitle(),
                    'alias_wgt' => $alias,
                );
                $result[$alias] = $class->getTitle();
                // $this->set('system', $data);
            } else {
                $data = array(
                    'name' => $name,
                    'alias_wgt' => $alias,
                );
                $result[$alias] = $name;
                //$this->set('system', $data);
            }
        }
        return $result;
    }

    public function getData($ex = array()) {
        foreach ($ex as $ee) {
            unset($this->data['system'][$ee]);
            unset($this->data['modules'][$ee]);
        }
        return $this->data;
    }

    public function getDropDownList() {
        $data = array();
        $data['dropdownlist'] = array(
            'Системные' => $this->getWidgetsSystem(),
            'Модули' => $this->data['module'],
        );
        return $data;
    }

    public function clear() {
        Yii::app()->cache->delete($this->cache_key);
    }

    private function setWidgetSettings($aliasForm) {
        if (file_exists(Yii::getPathOfAlias($aliasForm))) {
            $forms = CFileHelper::findFiles(Yii::getPathOfAlias($aliasForm), array(
                        'level' => -1,
                        'fileTypes' => array('php'),
                        'absolutePaths' => false
                            )
            );
            foreach ($forms as $form) {
                $className = str_replace('.php', '', $form);
                Yii::import("{$aliasForm}.{$className}");
                $formClass = new $className;
                if (method_exists($formClass, 'defaultSettings')) {
                    if (!Yii::app()->settings->get($className)) {
                       // Yii::app()->cache->flush();
                        Yii::app()->cache->delete(Yii::app()->settings->cache_key);
                        Yii::app()->settings->set($className, $formClass::defaultSettings());
                    }
                }
            }
        }
    }

}
