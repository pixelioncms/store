<?php

class OpenWeatherMapWidgetForm extends WidgetFormModel {

    public $lat;
    public $lon;
    public $enable_sunrise;
    public $enable_sunset;
    public $enable_humidity;
    public $enable_pressure;
    public $enable_wind;
    public $units;
    public $title;
    public $apikey;

    public function rules() {
        return array(
            array('lat, lon, title, units, apikey', 'type'),
            array('apikey', 'required'),
            array('enable_sunrise, enable_sunset, enable_humidity, enable_pressure, enable_wind', 'boolean')
        );
    }

    public function getForm() {
        Yii::import('ext.blocks.openweathermap.OpenWeatherMapWidget');
        return array(
            'type' => 'form',
            'attributes' => array(
                'class' => 'form-horizontal',
                'id' => __CLASS__
            ),
            'elements' => array(
                'title' => array(
                    'label' => 'Заголовок блока',
                    'type' => 'text',
                ),
                'apikey' => array(
                    'label' => 'API ключ',
                    'type' => 'text',
                    'hint' => Yii::t('app', 'Для получение ключа, необходимо зарегистрироватся на сайте, {link} ', array(
                        '{link}' => Html::link('openweathermap.org', 'http://openweathermap.org', array('traget' => '_blank'))
                            )
                    )
                ),
                'lat' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'COORD_LAT'),
                    'type' => 'text',
                ),
                'lon' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'COORD_LON'),
                    'type' => 'text',
                ),
                'units' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'UNITS'),
                    'type' => 'dropdownlist',
                    'items' => array('metric' => html_entity_decode('&deg;C'), 'imperial' => html_entity_decode('&deg;F'))
                ),
                'enable_wind' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'WIND'),
                    'type' => 'checkbox',
                ),
                'enable_sunrise' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'SUNRISE'),
                    'type' => 'checkbox',
                ),
                'enable_sunset' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'SUNSET'),
                    'type' => 'checkbox',
                ),
                'enable_humidity' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'HUMIDITY'),
                    'type' => 'checkbox',
                ),
                'enable_pressure' => array(
                    'label' => Yii::t('OpenWeatherMapWidget.default', 'PRESSURE'),
                    'type' => 'checkbox',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        );
    }

}
