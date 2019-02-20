<?php
/**
 *
 * @copyright (c) 2018, Semenov Andrew
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @author Semenov Andrew <info@andrix.com.ua>
 *
 * @link http://pixelion.com.ua PIXELION CMS
 * @link http://andrix.com.ua Developer
 *
 */

class AccuweatherWidgetForm extends WidgetFormModel {

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
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        Yii::import('ext.blocks.accuweather.AccuweatherWidget');
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
                    'label' => Yii::t('AccuweatherWidget.default', 'COORD_LAT'),
                    'type' => 'text',
                ),
                'lon' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'COORD_LON'),
                    'type' => 'text',
                ),
                'units' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'UNITS'),
                    'type' => 'SelectInput',
                    'data' => array('metric' => html_entity_decode('&deg;C'), 'imperial' => html_entity_decode('&deg;F'))
                ),
                'enable_wind' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'WIND'),
                    'type' => 'checkbox',
                ),
                'enable_sunrise' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'SUNRISE'),
                    'type' => 'checkbox',
                ),
                'enable_sunset' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'SUNSET'),
                    'type' => 'checkbox',
                ),
                'enable_humidity' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'HUMIDITY'),
                    'type' => 'checkbox',
                ),
                'enable_pressure' => array(
                    'label' => Yii::t('AccuweatherWidget.default', 'PRESSURE'),
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
