<?php

/**
 * Модель сессий пользователей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage admin.models
 * @uses ActiveRecord
 *
 * @property string $ip_adress IP адрес
 * @property string $user_agent User agent информация о браузере
 * @property string $user_type Тип пользователя 0 - Гость, 1 - Бот, 2 - Пользователь, 3 - Администратор
 * @property int $start_expire Время записи сессии {@see CMS}
 * @property int $expire
 * @property string|null $user_login Логин авторизированого пользователя
 * @property string $current_url Текущая ссылка
 * @property string $module Название модуля
 */
class Session extends ActiveRecord
{

    public function getBrowserVersion()
    {
        $browserClass = new Browser($this->user_agent);
        return $browserClass->getVersion();
    }

    public function getPlatformName()
    {
        $browserClass = new Browser($this->user_agent);
        return $browserClass->getPlatform();
    }

    public function getBrowserName()
    {
        $browserClass = new Browser($this->user_agent);
        return $browserClass->getBrowser();
    }

    public function getIconBrowser()
    {
        $browserClass = new Browser($this->user_agent);
        $browser = $browserClass->getBrowser();
        if ($browser == Browser::BROWSER_FIREFOX) {
            return 'firefox';
        } elseif ($browser == Browser::BROWSER_SAFARI) {
            return 'safari';
        } elseif ($browser == Browser::BROWSER_OPERA) {
            return 'opera';
        } elseif ($browser == Browser::BROWSER_CHROME) {
            return 'chrome';
        } elseif ($browser == Browser::BROWSER_IE) {
            return 'ie';
        } else {
            return false;
        }
    }

    public function getIconPlatform()
    {
        $browserClass = new Browser($this->user_agent);
        $platform = $browserClass->getPlatform();
        if ($platform == Browser::PLATFORM_WINDOWS) {
            return 'windows-7';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_8) { //no tested
            return 'windows-7';
        } elseif ($platform == Browser::PLATFORM_ANDROID) {
            return 'android';
        } elseif ($platform == Browser::PLATFORM_LINUX) {
            return 'linux';
        } elseif ($platform == Browser::PLATFORM_APPLE) {
            return 'apple';
        } else {
            return false;
        }
    }

    public static function online()
    {

        $session = Session::model()->findAll();
        $result = array();
        $rules = array();

        foreach (Rights::getRoles() as $name => $role) {
            $rules[$name] = 0;
        }

        $rules['Guest'] = 0;
        $rules['SearchBot'] = 0;

        if (isset($session)) {
            foreach ($session as $val) {
                $result['users'][] = array(
                    'login' => $val->user_name,
                    'ip' => $val->ip_address,
                    'user_agent' => $val->user_agent,
                    //'avatar' => $val->user_avatar,
                    'type' => $val->user_type
                );
                $us = explode(',', $val->user_type);
                if (isset($us)) {
                    foreach ($us as $d) {
                        $rules[$d]++;
                    }
                }
            }
        }
        $result['totals'] = array(
            'all' => count($session),
            'roles' => $rules
        );
        return $result;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{session}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('ip_address, user_agent, user_type', 'length', 'max' => 255),
            array('ip_address, user_agent, user_type, start_expire', 'safe'),
            array('expire, start_expire', 'numerical', 'integerOnly' => true),
            array('user_name, uname, ip_address, user_agent, module, current_url', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'ip_address' => 'ip_address',
            'user_agent' => 'user_agent',
        );
    }

    public function getAvatarUrl($size = false)
    {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = (isset($this->user->avatar)) ? $this->user->avatar : null;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {

            if (in_array($this->user_type, array('Guest', 'SearchBot'))) {
                $img = ($this->user_type == 'Guest') ? 'guest.png' : 'robot.png';
                $returnUrl = CMS::processImage($size, $img, 'users.avatars', 'user_avatar', array(
                    'watermark' => false,
                ));
            } else {
                if (empty($ava)) {
                    $returnUrl = CMS::processImage($size, 'user.png', 'users.avatars', 'user_avatar', array(
                        'watermark' => false,
                    ));
                } else {
                    $returnUrl = CMS::processImage($size, $ava, 'users.avatar', 'user_avatar', array(
                        'watermark' => false,
                    ));
                }
            }
            return $returnUrl;
        } else {
            return $ava;
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get ActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return ActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {

        $criteria = new CDbCriteria;
        //$criteria->with =array('user');
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('t.user_agent', $this->user_agent, true);
        // $criteria->compare('t.user_avatar', $this->user_avatar, true);
        $criteria->compare('t.ip_address', $this->ip_address, true);
        // $criteria->compare('t.module', $this->module, true);
        //$criteria->compare('t.uname', $this->uname, true);

        $criteria->compare('t.current_url', $this->current_url, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getOnlineTime()
    {
        if ($this->start_expire) {
            return CMS::display_time(time() - (int)$this->start_expire);
        } else {
            return 'unknown';
        }
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),

        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Blocks the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
