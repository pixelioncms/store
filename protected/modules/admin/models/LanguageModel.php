<?php

/**
 * This is the model class for table "LanguageModel".
 *
 * The followings are the available columns in table 'LanguageModel':
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage admin.models
 * @uses ActiveRecord
 *
 * @property integer $id
 * @property string $name Название языка
 * @property string $code Url префикс
 * @property string $locale Language locale
 * @property boolean $is_default Is lang is_default
 * @property boolean $flag_name Flag image name
 */
class LanguageModel extends ActiveRecord
{

    const MODULE_ID = 'admin';

    public $data_lang;
    public $start_auto_translate = false;
    private static $_languages;
    public $disallow_delete = array(1);

    public function getDataLangList()
    {
        $currLangs = Yii::app()->languageManager->getCodes();
        $result = array();
        foreach (self::langListArray() as $lang) {
           // if (!array_keys($currLangs, $lang[0])) {
			if($currLangs[0] !== $lang[0]){
                $result[$lang[0]] = $lang[1]['name'];
            }
        }
        return $result;
    }

    /**
     * Locale {@link http://lh.2xlibre.net/locales} helper
     * @return array
     */
    public static function langListArray()
    {
        return array(
            array('en', array('id'=>'en','name' => 'English', 'image' => 'en.png', 'locale' => 'en_US')),
            array('uk', array('id'=>'ua','name' => 'Ukrainian', 'image' => 'ua.png', 'locale' => 'uk_UA')),
            array('ru', array('id'=>'ru','name' => 'Russian', 'image' => 'ru.png', 'locale' => 'ru_RU')),
            array('ar', array('id'=>'ar','name' => 'Arabic', 'image' => 'ps.png', 'locale' => 'ar_AE')),
            array('hy', array('id'=>'hy','name' => 'Armenian', 'image' => 'am.png', 'locale' => 'hy_AM')),
            array('sq', array('id'=>'sq','name' => 'Albanian', 'image' => 'al.png', 'locale' => 'sq_AL')),
            array('az', array('id'=>'az','name' => 'Azerbaijani', 'image' => 'az.png', 'locale' => 'az_AZ')),
            array('be', array('id'=>'be','name' => 'Belarusian', 'image' => 'by.png', 'locale' => 'be_BY')),
            array('bg', array('id'=>'bg','name' => 'Bulgarian', 'image' => 'bg.png', 'locale' => 'bg_BG')),
            array('bs', array('id'=>'bs','name' => 'Bosnian', 'image' => 'ba.png', 'locale' => 'bs_BA')),
            array('ca', array('id'=>'ca','name' => 'Catalan', 'image' => 'catalonia.png', 'locale' => 'ca_ES')),
            array('cs', array('id'=>'cs','name' => 'Czech', 'image' => 'cz.png', 'locale' => 'cs_CZ')),
            array('hr', array('id'=>'hr','name' => 'Croatian', 'image' => 'hr.png', 'locale' => 'hr_HR')),
            array('zh', array('id'=>'zh','name' => 'Chinese', 'image' => 'cn.png', 'locale' => 'zh_CN')),
            array('da', array('id'=>'da','name' => 'Danish', 'image' => 'dk.png', 'locale' => 'da_DK')),
            array('nl', array('id'=>'nl','name' => 'Dutch', 'image' => 'nl.png', 'locale' => 'nl_AW')),
            array('de', array('id'=>'de','name' => 'German', 'image' => 'de.png', 'locale' => 'de_DE')),
            array('el', array('id'=>'el','name' => 'Greek', 'image' => 'gr.png', 'locale' => 'el_GR')),
            array('ka', array('id'=>'ka','name' => 'Georgian', 'image' => 'ge.png', 'locale' => 'ka_GE')),
            array('et', array('id'=>'et','name' => 'Estonian', 'image' => 'ee.png', 'locale' => 'et_EE')),
            array('fi', array('id'=>'fi','name' => 'Finnish', 'image' => 'fi.png', 'locale' => 'fi_FI')),
            array('fr', array('id'=>'fr','name' => 'French', 'image' => 'fr.png', 'locale' => 'fr_FR')),
            array('he', array('id'=>'he','name' => 'Hebrew', 'image' => 'hn.png', 'locale' => 'he_IL')),
            array('hu', array('id'=>'hu','name' => 'Hungarian', 'image' => 'hu.png', 'locale' => 'hu_HU')),
            array('id', array('id'=>'id','name' => 'Indonesian', 'image' => 'id.png', 'locale' => 'id_ID')),
            array('is', array('id'=>'is','name' => 'Icelandic', 'image' => 'is.png', 'locale' => 'is_IS')),
            array('it', array('id'=>'it','name' => 'Italian', 'image' => 'ie.png', 'locale' => 'it_IT')),
            array('lt', array('id'=>'lt','name' => 'Lithuanian', 'image' => 'lt.png', 'locale' => 'lt_LT')),
            array('lv', array('id'=>'lv','name' => 'Latvian', 'image' => 'lv.png', 'locale' => 'lv_LV')),
            array('mk', array('id'=>'mk','name' => 'Macedonian', 'image' => 'mk.png', 'locale' => 'mk_MK')),
            array('ms', array('id'=>'ms','name' => 'Malay', 'image' => 'my.png', 'locale' => 'ms_MY')),
            array('mt', array('id'=>'mt','name' => 'Maltese', 'image' => 'mt.png', 'locale' => 'mt_MT')),
            array('no', array('id'=>'no','name' => 'Norwegian', 'image' => 'no.png', 'locale' => 'nn_NO')),
            array('pl', array('id'=>'pl','name' => 'Polish', 'image' => 'pl.png', 'locale' => 'pl_PL')),
            array('pt', array('id'=>'pt','name' => 'Portuguese', 'image' => 'pt.png', 'locale' => 'pt_PT')),
            array('ro', array('id'=>'ro','name' => 'Romanian', 'image' => 'ro.png', 'locale' => 'ro_RO')),
            array('sk', array('id'=>'sk','name' => 'Slovak', 'image' => 'sk.png', 'locale' => 'sk_SK')),
            array('sl', array('id'=>'sl','name' => 'Slovenian', 'image' => 'si.png', 'locale' => 'sl_SI')),
            array('sr', array('id'=>'sr','name' => 'Serbian', 'image' => 'si.png', 'locale' => 'sr_RS')),
            array('sv', array('id'=>'sv','name' => 'Swedish', 'image' => 'se.png', 'locale' => 'sv_SE')),
            array('es', array('id'=>'es','name' => 'Spanish', 'image' => 'es.png', 'locale' => 'an_ES')),
            array('th', array('id'=>'th','name' => 'Thai', 'image' => 'th.png', 'locale' => 'th_TH')),
            array('tr', array('id'=>'tr','name' => 'Turkish', 'image' => 'tr.png', 'locale' => 'tr_TR')),
            array('vi', array('id'=>'vi','name' => 'Vietnamese', 'image' => 'vn.png', 'locale' => 'vi_VN')),
        );
    }

    public function attributeLabels()
    {
        return CMap::mergeArray(array(
            'data_lang' => self::t('DATA_LANG'),
            'start_auto_translate' => self::t('START_AUTO_TRANSLATE')
        ), parent::attributeLabels());
    }

    public function getForm()
    {
        if ($this->isNewRecord) {
            $formArray = array(
                'data_lang' => array(
                    'type' => 'dropdownlist',
                    'items' => $this->getDataLangList(),
                ),
                'start_auto_translate' => array(
                    'type' => 'checkbox',
                    'hint' => self::t('HINT_START_AUTO_TRANSLATE')
                ),
                'is_default' => array(
                    'type' => 'checkbox',
                )
            );
        } else {
            $formArray = array(
                'name' => array(
                    'type' => 'text',
                ),
                'code' => array(
                    'type' => 'text',
                    'hint' => Yii::t('app', 'EXAMPLE', array('{example}' => 'ru')),
                ),
                'locale' => array(
                    'type' => 'text',
                    'hint' => Yii::t('app', 'EXAMPLE', array('{example}' => 'en_US')),
                ),
                'flag_name' => array(
                    'type' => 'dropdownlist',
                    'items' => self::getFlagImagesList(),
                    'beforeContent' => '<span id="flag_render" class="" style="margin-left:15px;"></span>'
                ),
                'is_default' => array(
                    'type' => 'checkbox',
                ));
        }
        return new CMSForm(array(
            'attributes' => array('id' => __CLASS__),
            'elements' => $formArray,
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                ),
                'translate' => array(
                    'type' => 'link',
                    'visible' => !$this->isNewRecord,
                    'href' => array('/admin/app/translates/application?lang=' . $this->code),
                    'class' => 'btn btn-secondary',
                    'onClick' => 'if(confirm("' . Yii::t('app', 'CONFIRM_LANG_TRANSLATE', array('{name}' => $this->name)) . '")) {
                        window.location.href="/admin/app/translates/application?lang=' . $this->code . '";
                        return true;
                      } else {
                        return false;
                      }
                    ',
                    'label' => Yii::t('app', 'TRANSLATE_ALL_WEBSITE')
                ),
            )
        ), $this);
    }

    /**
     * Returns the static model of the specified AR class.
     * @return LanguageModel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{language}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('data_lang, start_auto_translate', 'required', 'on' => 'insert'),
            array('start_auto_translate', 'boolean'),
            array('start_auto_translate', 'validateYandexTranslate'),
            array('name, code', 'required', 'on' => 'update'),
            array('name, locale', 'length', 'max' => 100),
            array('flag_name', 'length', 'max' => 255),
            array('code', 'length', 'max' => 25),
            array('is_default', 'in', 'range' => array(0, 1)),
            array('id, name, code, locale', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('locale', $this->locale, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    private function attrCreate()
    {
        if ($this->isNewRecord) {
            $langsArray = self::langListArray();
            $findLang = array();
            foreach ($langsArray as $lang) {
                if ($this->data_lang == $lang[0]) {
                    $findLang = $lang;
                    break;
                }
            }
            $this->name = $findLang[1]['name'];
            $this->code = $findLang[1]['id'];
            $this->locale = $findLang[1]['locale'];
            $this->flag_name = $findLang[1]['image'];
        }
    }

    public function afterValidate()
    {
        $this->attrCreate();
        return parent::afterValidate();
    }

    public function afterSave()
    {
        $this->attrCreate();

        // Leave only one default language
        /* if ($this->is_default)
          {
          self::model()->updateAll(array(
          'is_default'=>0,
          ), 'id != '.$this->id);
          } */
        return parent::afterSave();
    }

    private static function removeDir($dir)
    {
        CFileHelper::removeDirectory($dir, array('traverseSymlinks' => true));
    }

    public function afterDelete()
    {
        // Remove application messages
        if (file_exists(Yii::getPathOfAlias("application.messages.{$this->code}"))) {
            self::removeDir(Yii::getPathOfAlias("application.messages.{$this->code}"));
        }

        // Remove locale dirs in modules
        foreach (Yii::app()->getModules() as $key => $mod) {
            $dir = Yii::getPathOfAlias("mod.{$key}.messages.{$this->code}");
            if (file_exists($dir)) {
                self::removeDir($dir);
            }

            //Remove module widget messages.
            $widgetsPath = "webroot.protected.modules.{$key}.widgets";
            if (file_exists(Yii::getPathOfAlias($widgetsPath))) {
                $widgetdirs = scandir(Yii::getPathOfAlias($widgetsPath));
                foreach ($widgetdirs as $entry) {
                    if ($entry != '.' && $entry != '..' && $entry != 'index.html' && !preg_match("/\.([a-zA-Z0-9]+)/", $entry)) {
                        if (file_exists(Yii::getPathOfAlias("{$widgetsPath}.{$entry}.messages.{$this->code}"))) {
                            self::removeDir(Yii::getPathOfAlias("{$widgetsPath}.{$entry}.messages.{$this->code}"));
                        }
                    }
                }
            }
        }

        // Remove in folder extensions.
        $extPath = "webroot.protected.extensions";
        $extdirs = scandir(Yii::getPathOfAlias($extPath));
        foreach ($extdirs as $entry) {
            if ($entry != '.' && $entry != '..' && $entry != 'index.html' && !preg_match("/\.([a-zA-Z0-9]+)/", $entry)) {
                if (file_exists(Yii::getPathOfAlias("{$extPath}.{$entry}.messages.{$this->code}"))) {
                    self::removeDir(Yii::getPathOfAlias("{$extPath}.{$entry}.messages.{$this->code}"));
                }
            }
        }

        return parent::afterDelete();
    }

    public function beforeDelete()
    {
        if ($this->is_default)
            return false;

        return parent::beforeDelete();
    }

    public function validateYandexTranslate($attr)
    {
        if ($this->start_auto_translate) {
            $t = new yandexTranslate;
            if ($t->checkConnect()->hasError) {
                $this->addError($attr, $t->checkConnect()->message);
            }
        }
    }


    public static function getFlagImagesList()
    {
        Yii::import('system.utils.CFileHelper');
        $flagsPath = 'webroot.uploads.language';

        $result = array();
        $flags = CFileHelper::findFiles(Yii::getPathOfAlias($flagsPath));

        foreach ($flags as $f) {
            $parts = explode(DS, $f);
            $fileName = end($parts);
            $result[$fileName] = $fileName;
        }

        return $result;
    }

}
