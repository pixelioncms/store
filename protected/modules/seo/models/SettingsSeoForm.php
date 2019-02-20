<?php

class SettingsSeoForm extends FormSettingsModel {

    public $googleanalytics_id;
    public $googletag_id;
    public $yandexmetrika_id;
    public $yandexmetrika_clickmap;
    public $yandexmetrika_trackLinks;
    public $yandexmetrika_webvisor;
    public $canonical;
    public $google_site_verification;
    public $yandex_verification;
    public $separation;

    public static function defaultSettings() {
        return array(
            'googleanalytics_id' => null,
            'googletag_id' => null,
            'yandexmetrika_id' => null,
            'yandexmetrika_clickmap' => true,
            'yandexmetrika_trackLinks' => true,
            'yandexmetrika_webvisor' => true,
            'canonical' => true,
            'google_site_verification' => '',
            'yandex_verification' => '',
            'separation'=>'|'
        );
    }

    public function getForm() {
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'global' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Global'),
                    'elements' => array(
                        'separation' => array('type' => 'text'),
                        'canonical' => array('type' => 'checkbox'),
                    )
                ),
                'google' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Google'),
                    'elements' => array(
                        'google_site_verification' => array('type' => 'text'),
                        'googleanalytics_id' => array('type' => 'text', 'hint' => 'UA-12345678-9'),
                        'googletag_id' => array('type' => 'text', 'hint' => 'GTM-123AB45'),
                    )
                ),
                'yandex' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Yandex'),
                    'elements' => array(
                        'yandex_verification' => array('type' => 'text'),
                        'yandexmetrika_id' => array('type' => 'text'),
                        'yandexmetrika_clickmap' => array('type' => 'checkbox', 'hint' => self::t('YANDEXMETRIKA_CLICKMAP_HINT')),
                        'yandexmetrika_trackLinks' => array('type' => 'checkbox', 'hint' => self::t('YANDEXMETRIKA_TRACKLINKS_HINT')),
                        'yandexmetrika_webvisor' => array('type' => 'checkbox', 'hint' => self::t('YANDEXMETRIKA_WEBVISOR_HINT')),
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

    public function rules() {
        return array(
            array('yandexmetrika_clickmap, yandexmetrika_trackLinks, yandexmetrika_webvisor, canonical', 'boolean'),
            array('separation, googleanalytics_id, googletag_id, google_site_verification, yandex_verification', 'type', 'type' => 'string'),
            array('yandexmetrika_id', 'numerical', 'integerOnly' => true),
            array('googleanalytics_id', 'length', 'max' => 13, 'min' => 13),
        );
    }

}
