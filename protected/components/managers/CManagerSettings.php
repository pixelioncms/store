<?php

/**
 * CManagerSettings
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @subpackage managers
 * @uses CComponent
 * @link http://pixelion.com.ua PIXELION CMS
 */
class CManagerSettings extends CComponent {

    /**
     * @var array
     */
    protected $data = array();
    public $cache_key = 'cached_settings';

    /**
     * Initialize component
     */
    public function init() {

        $this->data = Yii::app()->cache->get($this->cache_key);

        if (!$this->data) {
            // Load settings
            $settings = Yii::app()->db->createCommand()
                    ->from('{{settings}}')
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

    /**
     * Запись настроеек в базу даннах
     * 
     * Пример:
     * <code>Yii::app()->settings->set('category_name',array('param'=>10));</code>
     * 
     * @param string $category string component unique id. e.g: contacts, shop, news
     * @param array $data key-value array. e.g array('param'=>10)
     * 
     */
    public function set($category, array $data) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($this->get($category, $key) !== null) {
                    Yii::app()->db->createCommand()->update('{{settings}}', array(
                        'value' => $value), '{{settings}}.category=:category AND {{settings}}.key=:key', array(':category' => $category, ':key' => $key));
                } else {
                    Yii::app()->db->createCommand()->insert('{{settings}}', array(
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
            //Yii::log('update settings','info','application');
            Yii::app()->cache->set($this->cache_key, $this->data);
        }else{
           // Yii::log('empty settings','info','application');
        }
    }

    /**
     * 
     * Пример: {@link set}
     * <code>
     * Yii::app()->settings->get('category_name','param')); //Вернет результат "10"
     * </code>
     * 
     * <code>
     * Yii::app()->settings->get('category_name')); //Вернет результат array('param'=>'10')
     * </code>
     * 
     * 
     * @param string $category Уникальное название категории настроеек
     * @param null $key option key. Если не предусмотрено все настройки категории будут возвращены как массив.
     * @param null|string $default Значение по умолчанию, если оригинал не существует
     * @return mixed
     */
    public function get($category, $key = null, $default = null) {



        if (!isset($this->data[$category]))
            return $default;

        if ($key === null)
            return (object) $this->data[$category];
        if (isset($this->data[$category][$key]))
            return $this->data[$category][$key];
        else
            return $default;
    }

    /**
     * Удаление категории настроек с базы данных.
     * 
     * @param string $category
     */
    public function clear($category) {
        Yii::app()->db->createCommand()->delete('{{settings}}', 'category=:category', array(':category' => $category));
        if (isset($this->data[$category]))
            unset($this->data[$category]);

        Yii::app()->cache->delete($this->cache_key);
    }

}
