<?php

class OpenWeatherMapWidget extends BlockWidget
{

    public $alias = 'ext.blocks.openweathermap';
    public $assetsUrl;
    public $errors = true;
    public $result;

    public function init()
    {
        $this->setId('openweathermap-widget');
        $this->publishAssets();
        parent::init();
    }

    public function run()
    {
        $this->result = Yii::app()->cache->get(__CLASS__);
        if ($this->result === false && isset($this->config)) {
            if (Yii::app()->hasComponent('curl')) {
                $curl = Yii::app()->curl;

                $curl->options = array(
                    'timeout' => 320,
                    'setOptions' => array(
                        CURLOPT_HEADER => false
                    ),
                );
                $connect = $curl->run('http://api.openweathermap.org/data/2.5/weather?lat=' . $this->config->lat . '&lon=' . $this->config->lon . '&units=' . $this->config->units . '&cnt=10&lang=' . Yii::app()->language . '&APPID=' . $this->config->apikey);
                if (!$connect->hasErrors()) {
                    $this->result = CJSON::decode($connect->getData());

                } else {
                    $this->result = $connect->getErrors();
                }
                Yii::app()->cache->set(__CLASS__, $this->result, 0); //3600 - час
            } else {
                throw new Exception('error curl component');
            }
        } else {
            $this->result = (object)array(
                'hasError' => true,
                'message' => Yii::t('OpenWeatherMapWidget.default', 'NO_SETTINGS', array(
                    '{link}' => Html::link(Yii::t('app', 'SETTINGS'), array('/admin/app/widgets/update', 'alias' => $this->alias . '.' . __CLASS__))
                ))
            );
        }

        $this->render($this->skin, array('result' => $this->result));
    }

    public function publishAssets()
    {
        $assets = dirname(__FILE__) . '/assets';
        $this->assetsUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/css/weather.css");
        } else {
            throw new Exception(Yii::t('app', 'ERROR_ASSETS_PATH', array('{class}' => __CLASS__)));
        }
    }

    public function degToCompass($num)
    {
        $val = floor(($num / 22.5) + .5);
        $arr = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");
        return Yii::t('OpenWeatherMapWidget.default', $arr[($val % 16)]);
    }

    public function degToCompassImage($num)
    {
        $val = floor(($num / 22.5) + .5);
        $arr = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");
        // $arr = array("wind1", "wind2", "wind8", "wind2", "wind7", "ESE", "SE", "SSE", "wind5", "wind4", "SW", "WSW", "wind3", "WNW", "wind8", "NNW");
        return '<div class="wind ' . $arr[($val % 16)] . '"></div>';
    }

    public function getDeg()
    {
        return ($this->config->units == 'metric') ? '&deg;C' : '&deg;F';
    }

    public function getIcon()
    {

        if ($this->result['weather'][0]['icon'] == '01d') {
            $data = 'w-sun';
        } elseif ($this->result['weather'][0]['icon'] == '01n') {
            $data = 'w-moon';
        } elseif ($this->result['weather'][0]['icon'] == '02d') {
            $data = 'w-cloud-moon';
        } elseif ($this->result['weather'][0]['icon'] == '02n') {
            $data = 'w-cloud-sun';
        } elseif (in_array($this->result['weather'][0]['icon'], array('03d', '03n'))) {
            $data = 'w-cloud';
        } elseif (in_array($this->result['weather'][0]['icon'], array('04d', '04n'))) {
            $data = 'w-clouds';
        } elseif ($this->result['weather'][0]['icon'] == '10d') {
            $data = 'w-rain-sun';
        } elseif ($this->result['weather'][0]['icon'] == '10n') {
            $data = 'w-rain-moon';
        } elseif ($this->result['weather'][0]['icon'] == '11d') {
            $data = 'w-lighting-sun';
        } elseif ($this->result['weather'][0]['icon'] == '11n') {
            $data = 'w-lighting-moon';
        } elseif (in_array($this->result['weather'][0]['icon'], array('13d', '13n'))) {
            $data = 'w-snow';
        } elseif ($this->result['weather'][0]['icon'] == '50n') { //туман
            $data = 'w-haze';
        } else {
            $data = '';
        }
        return Html::tag('i', array('class' => $data), '', true);
    }

}
