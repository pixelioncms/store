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
class AccuweatherWidget extends BlockWidget
{

    public $alias = 'ext.blocks.accuweather';
    public $assetsUrl;
    public $errors = true;
    public $result;

    public function init()
    {
        $this->setId('accuweather-widget');
        $this->publishAssets();
        parent::init();
    }

    public function run()
    {
        $this->result = Yii::app()->cache->get(__CLASS__);
        if ($this->result === false) {
            if (Yii::app()->hasComponent('curl')) {
                $curl = Yii::app()->curl;

                $curl->options = array(
                    'timeout' => 320,
                    'setOptions' => array(
                        CURLOPT_HEADER => false
                    ),
                );

                //  $connect = $curl->run('http://dataservice.accuweather.com/forecasts/v1/daily/1day/325343?apikey=3C27rqJG3G6lrtJbsYGDvdsVBuFvmAxr&details=false&metric=true');
                $connect = $curl->run('http://dataservice.accuweather.com/currentconditions/v1/325343.json?metric=true&apikey=3C27rqJG3G6lrtJbsYGDvdsVBuFvmAxr&details=true');

                if (!$connect->hasErrors()) {
                    $this->result = CJSON::decode($connect->getData(), false);
                    //if ($this->result->code == 22) {
                    //    $this->result = (object)array('message' => 'dasasd');
                    // }
                } else {
                    $this->result = $connect->getErrors();
                }
                //Кэшируем на 30 минут, так как лимит 50 запросв.
                Yii::app()->cache->set(__CLASS__, $this->result, 1800);
            } else {
                throw new Exception('error curl component');
            }
        }

        CVarDumper::dump($this->result, 100, true);

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
