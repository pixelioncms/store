<?php

/**
 * Модель формы настроки приложения
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage admin.models
 * @uses FormSettingsModel
 *
 * @property string $site_name Site name
 * @property string $forum Integration of forums
 * @property string $theme Theme name
 * @property string $etheme Event theme name
 * @property datetime $etheme_start Start date event theme
 * @property datetime $etheme_end End date event theme
 * @property boolean $site_close Close site
 * @property text $site_close_text Close site text
 * @property string $format_date Format datetime CMS::date()
 * @property int $cookie_time Cookie time in day
 */
class SettingsAppForm extends FormSettingsModel
{

    const NAME = 'app';


    public $admin_theme;
    public $license_key;
    public $site_name;
    public $forum;
    public $forum_path;
    public $theme;
    public $etheme;
    public $etheme_start;
    public $etheme_end;
    public $site_close;
    public $site_close_text;
    public $format_date;
    public $cookie_time;
    public $cache_time;
    public $pagenum;
    public $multi_language;
    public $censor_array;
    public $censor;
    public $censor_replace;
    public $site_close_allowed_users;
    public $site_close_allowed_ip;
    public $session_time;
    public $admin_email;
    public $default_timezone;
    public $translate_object_url;
    public $auto_detect_language;
    public $attachment_image_type;
    public $attachment_image_resize;
    public $attachment_wm_active;
    public $attachment_wm_path;
    public $attachment_wm_corner;
    public $attachment_wm_offsetx;
    public $attachment_wm_offsety;


    //public $cache;


    public function init()
    {

        $def = self::defaultSettings();
        $list = array();
        $param = Yii::app()->settings->get('app');
        $list['cache_time'] = $param->cache_time / 86400;
        $list['cookie_time'] = $param->cookie_time / 86400;
        $list['session_time'] = $param->session_time / 60;

        if (isset($param->attachment_wm_active) && $param->attachment_wm_active) {
            $modules = explode(',', $param->attachment_wm_active);
            foreach ($modules as $mod) {
                $mods[] = $mod;
            }
            $list['attachment_wm_active'] = $mods;
        }

        //@todo error with install module
        $list['admin_theme'] = (isset(Yii::app()->user->adminTheme))?Yii::app()->user->adminTheme:$def['admin_theme'];


        $this->attributes = CMap::mergeArray((array)$param, $list);
    }


    public static function defaultSettings()
    {
        return array(
            'admin_theme' => 'dark',
            'site_name' => Yii::app()->name,
            'admin_email' => 'dev@pixelion.com.ua',
            'theme' => 'default',
            'forum' => '',
            'cache_time' => 864000,
            'cookie_time' => 864000,
            'session_time' => 600,
            'format_date' => 'd MMM yyyy',
            'pagenum' => 50,
            'multi_language' => 0,
            'site_close' => 0,
            'site_close_text' => 'site close text',
            'site_close_allowed_users' => 'admin',
            'site_close_allowed_ip' => '',
            'auto_detect_language' => 0,
            'censor' => 1,
            'censor_array' => 'anti',
            'censor_replace' => '***',
            'default_timezone' => 'Europe/Kiev',
        );
    }

    public static function getDropDownCacheList()
    {
        $list = array();
        $list['CDbCache'] = 'DbCache — (использует таблицу базы данных для хранения кэшируемых данных)';
        $list['CFileCache'] = 'FileCache — (использует файлы для хранения кэшированных данных)';
        $list['CDummyCache'] = 'DummyCache — (кэш-пустышка)';
        $list['CMemCache'] = 'MemCache — (использует расширение memcache для PHP)';
        $list['CRedisCache'] = 'RedisCache';
        $list['CApcCache'] = 'ApcCache';
        $list['CXCache'] = 'XCache';
        $list['CEAcceleratorCache'] = 'EAcceleratorCache';
        $list['CEAcceleratorCache'] = 'EAcceleratorCache';
        $list['CWinCache'] = 'WinCache';
        $list['CZendDataCache'] = 'ZendDataCache';
        return $list;
    }

    public static function getDropDownCacheListOptions()
    {
        $list = self::getDropDownCacheList();
        $options = array();

        if (!extension_loaded("redis")) {
            $options['CRedisCache']['disabled'] = true;
            $options['CRedisCache']['label'] = $list['CRedisCache'] . ' — (' . Yii::t('app', 'OFF', 0) . ')';
        }
        if (!extension_loaded('apcu') || !extension_loaded('apc')) {
            $options['CApcCache']['label'] = $list['CApcCache'] . ' — (' . Yii::t('app', 'OFF', 0) . ')';
            $options['CApcCache']['disabled'] = true;
        }
        if (!extension_loaded('xcache_isset')) {
            $options['CXCache']['disabled'] = true;
            $options['CXCache']['label'] = $list['CXCache'] . ' — (' . Yii::t('app', 'OFF', 0) . ')';
        }

        if (!extension_loaded('memcache') || extension_loaded('memcached')) {
            $options['CMemCache']['disabled'] = true;
        }


        if (!function_exists('eaccelerator_get')) {
            $options['CEAcceleratorCache']['label'] = $list['CEAcceleratorCache'] . ' — (' . Yii::t('app', 'OFF', 0) . ')';
            $options['CEAcceleratorCache']['disabled'] = true;
        }


        if (!extension_loaded('wincache') && !ini_get('wincache.ucenabled')) {
            $options['CWinCache']['label'] = $list['CWinCache'] . ' — (' . Yii::t('app', 'OFF', 0) . ')';
            $options['CWinCache']['disabled'] = true;
        }
        if (!function_exists('zend_shm_cache_store')) {
            $options['CZendDataCache']['label'] = $list['CZendDataCache'] . ' — (' . Yii::t('app', 'OFF', 0) . ')';
            $options['CZendDataCache']['disabled'] = true;
        }

        return $options;
    }

    public function getForm()
    {
        $themesNames = Yii::app()->themeManager->themeNames;
        $themes = array_combine($themesNames, $themesNames);

        $eventThemes = array_combine($themesNames, $themesNames);
        unset($eventThemes[$this->theme]);
        $df = Yii::app()->dateFormatter;
        Yii::import('ext.tinymce.TinymceArea');
        Yii::import('app.jui.JuiDateTimePicker');
        Yii::import('ext.tageditor.TagEditor');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'global' => array(
                    'type' => 'form',
                    'title' => static::t('TAB_GENERAL'),
                    'elements' => array(
                        'site_name' => array('type' => 'text'),
                        'admin_theme' => array(
                            'type' => 'dropdownlist',
                            'items' => array('pixelion' => 'Pixelion (beta)', 'dark' => 'Темная', 'light' => 'Светлая (beta)')
                        ),
                        // 'license_key' => array('type' => (Yii::app()->user->isSuperuser) ? 'text' : 'read'),
                        'admin_email' => array('type' => 'text'),
                        'forum' => array(
                            'type' => 'dropdownlist',
                            'items' => self::forums(),
                            'hint' => 'Расположение директории форума должна быть в корне сайта. <code>"' . $_SERVER['DOCUMENT_ROOT'] . '"</code>',
                            'empty' => Yii::t('app', 'EMPTY_LIST')

                        ),
                        //'forum_path' => array('type' => 'text'),
                        'session_time' => array('type' => 'text'),
                        'cookie_time' => array('type' => 'text'),
                        'cache_time' => array('type' => 'text'),
                        'pagenum' => array('type' => 'text'),
                        'multi_language' => array('type' => 'checkbox'),
                        'theme' => array(
                            'type' => 'dropdownlist',
                            'items' => $themes,
                        ),
                        'etheme' => array(
                            'type' => 'dropdownlist',
                            'items' => $eventThemes,
                            'empty' => Yii::t('app', 'EMPTY_LIST')
                        ),
                        'etheme_start' => array(
                            'type' => 'JuiDateTimePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'timeFormat' => 'HH:mm:00'
                            ),
                            // 'htmlOptions' => array(
                            //     'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                            // )
                        ),
                        'etheme_end' => array(
                            'type' => 'JuiDateTimePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'timeFormat' => 'HH:mm:00'
                            ),
                            // 'htmlOptions' => array(
                            //     'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                            // )
                        ),
                    )
                ),
                /* 'cached' => array(
                     'type' => 'form',
                     'title' => self::t('TAB_CACHE'),
                     'elements' => array(
                         'cache' => array(
                             'type' => 'dropdownlist',
                             'items' => self::getDropDownCacheList(),
                             'options' => self::getDropDownCacheListOptions()
                         ),
                         'cache_mem_servers' => array('type' => 'TagInput'),
                     )
                 ),*/
                'attachments' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_ATTACHMENTS'),
                    'elements' => array(
                        '<div class="form-group row">
				            <div class="col"><div class="alert alert-info">' . self::t('ATTACHMENT_INFO') . '</div></div>
				        </div>',
                        'attachment_image_resize' => array('type' => 'text'),
                        'attachment_image_type' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                'asset' => 'Asset (Сохранение изображений в папку assets)',
                                'render' => 'Render (Создание изображений на лету)'
                            ),
                            'hint' => self::t('ATTACHMENT_IMAGE_TYPE_HINT')
                        ),
                        'attachment_wm_active' => array(
                            //'type' => 'dropdownlist',
                            //'multiple' => true,
                            'type' => 'checkboxlist',
                            'items' => ModulesModel::getModules()
                        ),
                        'attachment_wm_path' => array(
                            'type' => 'file',
                        ),
                        '<div class="form-group row">
				            <div class="col-sm-4"><label></label></div>
				            <div class="col-sm-8">' . $this->renderWatermarkImageTag() . '</div>
				        </div>',
                        'attachment_wm_corner' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->getWatermarkCorner()
                        ),
                        'attachment_wm_offsetx' => array('type' => 'text'),
                        'attachment_wm_offsety' => array('type' => 'text'),
                    )
                ),
                'close_site' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CLOSESITE'),
                    'elements' => array(
                        'site_close' => array('type' => 'checkbox'),
                        'site_close_text' => array('type' => 'TinymceArea'),
                        'site_close_allowed_users' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => self::t('ADD_USER')
                            ),
                            'hint' => Yii::t('app', 'HINT_TAGS_PLUGIN')
                        ),
                        'site_close_allowed_ip' => array(
                            'type' => 'text',
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => self::t('ADD_IP')
                            ),
                            'hint' => Yii::t('app', 'HINT_TAGS_PLUGIN')
                        ),
                    )
                ),
                'censor' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CENSOR'),
                    'elements' => array(
                        'censor' => array('type' => 'checkbox'),
                        'censor_array' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => self::t('ADD_WORD')
                            ),
                            'hint' => Yii::t('app', 'HINT_TAGS_PLUGIN')
                        ),
                        'censor_replace' => array('type' => 'text'),
                    )
                ),
                'datetime' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_DATETIME'),
                    'elements' => array(
                        'format_date' => array(
                            'type' => 'text',
                            'afterContent' => '<i class="icon-calendar"></i>',
                            'hint' => "<div>День (d или dd): " . $df->format('dd', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MM): " . $df->format('MM', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MMM): " . $df->format('MMM', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MMMM): " . $df->format('MMMM', date('Y-m-d H:i:s')) . "</div>
                    <div>Год (yy): " . $df->format('yy', date('Y-m-d H:i:s')) . "</div>
                    <div>Год (yyyy): " . $df->format('yyyy', date('Y-m-d H:i:s')) . "</div>"
                        ),
                        'default_timezone' => array(
                            'type' => 'dropdownlist',
                            'items' => TimeZoneHelper::getTimeZoneData()
                        ),
                    )
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        ), $this);
    }


    public function getWatermarkCorner()
    {
        return array(
            1 => self::t('CORNER_LEFT_TOP'),
            2 => self::t('CORNER_RIGHT_TOP'),
            3 => self::t('CORNER_LEFT_BOTTOM'),
            4 => self::t('CORNER_RIGHT_BOTTOM'),
            5 => self::t('CORNER_CENTER'),
        );
    }

    public function renderWatermarkImageTag()
    {
        if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/watermark.png'))
            return Html::image('/uploads/watermark.png?' . time(), '', array('class' => 'img-fluid'));
    }

    public function validateWatermarkFile($attr)
    {
        $file = CUploadedFile::getInstance($this, 'watermark_image');
        if ($file) {
            $allowedExts = array('jpg', 'gif', 'png');
            if (!in_array($file->getExtensionName(), $allowedExts))
                $this->addError($attr, self::t('ERROR_WM_NO_IMAGE'));
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(

            //Watermark
            array('attachment_wm_corner', 'numerical', 'integerOnly' => true),
            array('attachment_wm_offsetx, attachment_wm_offsety, attachment_wm_corner, attachment_image_type, admin_theme', 'required'),
            array('attachment_wm_path', 'validateWatermarkFile'),
            array('attachment_wm_active, attachment_image_resize', 'type', 'type' => 'string'),
            array('attachment_wm_active', 'length', 'max' => 250),
            //cache
            // array('cache', 'type', 'type' => 'string'),
            //end cahe
            array('etheme_end, etheme_start', 'type', 'type' => 'datetime', 'datetimeFormat' => 'yyyy-MM-dd hh:mm:ss'),
            array('pagenum, admin_email, default_timezone, session_time, site_close_allowed_users, site_name, censor_replace, censor_array, theme, site_close_text, format_date, cache_time, cookie_time, license_key', 'required'),
            array('site_close_allowed_ip', 'IPValidator'),
            // array('license_key', 'validateLicense'),
            array('translate_object_url, auto_detect_language', 'safe'),
            array('forum, etheme, etheme_start, etheme_end, default_timezone, forum_path', 'type', 'type' => 'string'),
            array('multi_language, censor, site_close', 'boolean')
        );
    }

    public function beforeValidate()
    {

        if ($this->attachment_wm_active) {
            $post = Yii::app()->request->getPost(__CLASS__, null);
            $this->attachment_wm_active = implode(',', $post['attachment_wm_active']);
            return true;
        }

        return parent::beforeValidate();
    }

    public function validateLicense($attr)
    {
        $data = LicenseCMS::run()->connected($this->$attr);
        if ($data['status'] == 'error') {
            $this->addError($attr, $data['message']);
        } else {
            LicenseCMS::run()->removeLicenseCache();
        }
    }

    /**
     * Saves attributes into database
     */
    public function save($message = true)
    {
        $this->cache_time = $_POST['SettingsAppForm']['cache_time'] * 86400;
        $this->cookie_time = $_POST['SettingsAppForm']['cookie_time'] * 86400;
        $this->session_time = $_POST['SettingsAppForm']['session_time'] * 60;
        if ($this->admin_theme) {
            $user = User::model()->updateByPk(Yii::app()->user->id, array('admin_theme' => $this->admin_theme));
            //  unset($this->admin_theme);
        }

        parent::save($message);
    }

    /**
     * Integration forums
     * @return array
     */
    private static function forums()
    {
        return array(
            'ipb|3.4.x' => 'Invision Power Board (3.4.x)',
            'phpbb3|3' => 'phpBB 3',
            'phpbb2|2' => 'phpBB 2',
            //'vb3|3.x'=>'vBulletin (3.x)',
            //'vb5|4.x'=>'vBulletin (4.x)',
            //  'vb5|5.x'=>'vBulletin (5.x)',
            //  'smf|2.x'=>'Simple Machines Forum',
            // 'phpbb|2.x'=>'phpBB (2.x)',
        );
    }

}
