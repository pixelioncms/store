<?php

class ThemeForm extends FormThemeSettingsModel {

    const MODULE_ID = 'admin';

    public $logo;

    public $favicon;
    public $google_theme_color;

    public static function defaultSettings() {
        return array(
            'logo' => 'logo.png',
            'favicon' => null,
            'google_theme_color' => '#222222'
        );
    }

    public function getLogoOverview() {
        if (file_exists(Yii::getPathOfAlias('webroot.uploads') . DS . $this->logo) && !empty($this->logo)) {
            Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.overview-image'));
            return '<a href="/uploads/' . $this->logo . '" class="overview-image" title="Картинка">Картинка</a>';
        } else {
            return 'None;';
        }
    }

    public function getForm() {
        Yii::import('ext.colorpicker.ColorPicker');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CONTENT'),
                    'elements' => array(
                        'logo' => array(
                            'type' => 'file',
                            //'hint' => $this->getLogoOverview()
                            'hint'=>'*.jpg, *.gif, *.png'
                        ),
                        'favicon' => array(
                            'type' => 'file',
                         'hint' => '*.ico, *.png'
                        ),
                        'google_theme_color' => array(
                            'type' => 'ColorPicker',
                            'mode' => 'textfield',
                            'fade' => false,
                            'slide' => false,
                            'curtain' => true,
                            'hint'=>Html::link('Что это?','https://developers.google.com/web/updates/2014/11/Support-for-theme-color-in-Chrome-39-for-Android',array('target'=>'_blank'))
                        ),

                    ),
                )
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

    public function rules() {
        return array(
            //array('logo', 'required'),
            array('logo', 'FileValidator', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            array('favicon', 'FileValidator', 'types' => 'ico, png', 'allowEmpty' => true),
            //array('logo_width, logo_height', 'numerical', 'integerOnly' => true),
            array('google_theme_color', 'type', 'type' => 'string'),
            array('google_theme_color', 'length', 'min' => 7, 'max' => 7),
                //  array('multi_language, censor, site_close, translate_object_url', 'boolean')
        );
    }

}
