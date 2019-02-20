<?php

/**
 * CManagerTheme
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @uses CThemeManager
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
Yii::import('app.managers.Theme');

class CManagerTheme extends CThemeManager
{
    public $themeClass = 'Theme';
    private $_config;
    protected $data = array();
    public $cache_key = 'cached_settings_theme';

    public function init()
    {


        if (!Yii::app()->getDb()->schema->getTable('{{settings_theme}}')) {
            /*  Yii::app()->getDb()->createCommand()->createTable('{{settings_theme}}', array(
                  'id' => 'pk',
                  'type' => 'string NOT NULL',
                  'cur' => 'string NOT NULL',
                  'rate' => 'string NOT NULL',
                  'date' => 'date',
              ));*/
        }
        $this->data = Yii::app()->cache->get($this->cache_key);

        if (!$this->data) {
            // Load settings
            $settings = Yii::app()->getDb()->createCommand()
                ->from('{{settings_theme}}')
                ->order('category')
                ->queryAll();

            if (!empty($settings)) {
                foreach ($settings as $row) {
                    if (!isset($this->data[$row['category']]))
                        $this->data[$row['category']] = array();
                    $this->data[$row['category']][$row['key']] = $row['value'];
                }
            }
            Yii::app()->cache->set($this->cache_key, $this->data);
        }

    }

    private function eventTheme($theme)
    {
        $c = Yii::app()->settings->get('app');
        if (!empty($c->etheme)) {
            $now = CMS::time();
            $timeStart = strtotime($c->etheme_start);
            $timeEnd = strtotime($c->etheme_end);
            if ($timeStart < $now) {
                if ($timeEnd < $now) {
                    $t = $theme;
                } else {
                    $t = $c->etheme;
                }
                return $t;
            }
        } else {
            return $theme;
        }
    }

    private $_basePath = null;
    private $_baseUrl = null;

    /**
     * @return string the base path for all themes. Defaults to "WebRootPath/themes".
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {

            if (file_exists(dirname(Yii::app()->getRequest()->getScriptFile()) . DIRECTORY_SEPARATOR . self::DEFAULT_BASEPATH)) {
                $this->setBasePath(dirname(Yii::app()->getRequest()->getScriptFile()) . DIRECTORY_SEPARATOR . self::DEFAULT_BASEPATH);
            } else {

                //die(dirname(COMMON_PATH));
                $this->setBasePath(COMMON_PATH . DIRECTORY_SEPARATOR . self::DEFAULT_BASEPATH);
            }
        }
        return $this->_basePath;
    }

    /**
     * @param string $value the base path for all themes.
     * @throws CException if the base path does not exist
     */
    public function setBasePath($value)
    {
        $this->_basePath = realpath($value);
        if ($this->_basePath === false || !is_dir($this->_basePath))
            throw new CException(Yii::t('yii', 'Theme directory "{directory}" does not exist.', array('{directory}' => $value)));
    }

    public function getTheme($name)
    {
        $name = $this->eventTheme($name);
        $themePath = $this->getBasePath() . DIRECTORY_SEPARATOR . $name;
        if (is_dir($themePath)) {
            $class = Yii::import($this->themeClass, true);
            return new $class($name, $themePath, $this->getBaseUrl() . '/' . $name);
        } else
            return null;
    }

    public function getConfig()
    {
        $this->_config = $this->get(Yii::app()->theme->name);
        return $this->_config;
    }

    public function set($category, array $data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($this->get($key) !== null) { //$category, $key
                    Yii::app()->getDb()->createCommand()->update('{{settings_theme}}', array(
                        'value' => $value), '{{settings_theme}}.category=:category AND {{settings_theme}}.key=:key', array(':category' => $category, ':key' => $key));
                } else {
                    Yii::app()->db->createCommand()->insert('{{settings_theme}}', array(
                        'category' => $category,
                        'key' => $key,
                        'value' => $value
                    ));
                }
            }

            if (!isset($this->data[$category]))
                $this->data[$category] = array();
            $this->data[$category] = CMap::mergeArray($this->data[$category], $data);

            // Update cache
            Yii::app()->cache->set($this->cache_key, $this->data);
        }
    }

    /**
     * @param null $key option key. If not provided all category settings will be returned as array.
     * @param null|string $default default value if original does not exists
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        $category = Yii::app()->theme->name;

        if (!isset($this->data[$category]))
            return $default;

        if ($key === null)
            return $this->data[$category];
        if (isset($this->data[$category][$key]))
            return $this->data[$category][$key];
        else
            return $default;
    }

    public function ge2t($category, $key = null, $default = null)
    {
        if (!isset($this->data[$category]))
            return $default;

        if ($key === null)
            return $this->data[$category];
        if (isset($this->data[$category][$key]))
            return $this->data[$category][$key];
        else
            return $default;
    }

    /**
     * Remove category from DB
     * @param $category
     */
    public function clear($category)
    {
        Yii::app()->db->createCommand()->delete('{{settings_theme}}', 'category=:category', array(':category' => $category));
        if (isset($this->data[$category]))
            unset($this->data[$category]);

        Yii::app()->cache->delete($this->cache_key);
    }

}
