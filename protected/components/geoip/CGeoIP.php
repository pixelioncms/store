<?php

/**
 * CGeoip class file.
 *
 */
class CGeoIP extends CApplicationComponent
{
    public $result;
    protected $cache;
    protected $cachePath;

    /**
     * 86400 day;
     */
    const CACHE_TIME = 86400 * 30;

    public function init()
    {
        $this->cachePath = Yii::getPathOfAlias('application.runtime.cache_ips');
        if (!file_exists($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
            chmod($this->cachePath, 0777);
            Yii::log('Geo create dir cache_ips', 'info', 'application');
        }

        parent::init();
    }


    public function get($ip = null)
    {
        $this->_getIP($ip);

        $data = array();
        $data['city'] = (isset($this->result['city'])) ? Yii::t('CGeoIP.city', $this->result['city']) : null;
        $data['region'] = (isset($this->result['region'])) ? Yii::t('CGeoIP.region', $this->result['region']) : null;

        if (isset($this->result['country'])) {
            $data['country'] = Yii::t('CGeoIP.country', $this->result['country']);
            $data['countryCode'] = $this->result['country'];
        } else {
            $data['country'] = null;
            $data['countryCode'] = null;
        }
        if (isset($this->result['loc'])) {
            $coords = explode(',', $this->result['loc']);
            $data['location'] = (object)array('lat' => $coords[0], 'lng' => $coords[1]);
        } else {
            $data['location'] = (object)array('lat' => null, 'lng' => null);
        }

        $data['hostname'] = (isset($this->result['hostname'])) ? $this->result['hostname'] : null;
        $data['postal'] = (isset($this->result['postal'])) ? $this->result['postal'] : null;
        $data['org'] = (isset($this->result['org']) || !empty($this->result['org'])) ? $this->result['org'] : null;
        $data['phone'] = (isset($this->result['phone'])) ? $this->result['phone'] : null;

        return (object)$data;
    }

    protected function _getIP($ip = null)
    {
        if ($ip === null) {
            $ip = Yii::app()->getRequest()->getUserHostAddress();
        }


        $this->result = $this->connect($ip);
        return $ip;
    }


    private function connect($ip)
    {
        if (Yii::app()->hasComponent('curl')) {
            $this->cache = Yii::app()->cacheGeo;
            $this->cache->cachePath = $this->cachePath;
            $result = $this->cache->get('geo_' . $ip);

            if ($result === false) {
                $curl = Yii::app()->curl;
                $curl->options = array(
                    'timeout' => 320,
                    'setOptions' => array(
                        CURLOPT_HEADER => false,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_SSL_VERIFYPEER => false,
                    ),
                );
                $connent = $curl->run("https://ipinfo.io/{$ip}/json", array());

                if (!$connent->hasErrors()) {

                    $result = CJSON::decode($connent->getData());
                    if (!isset($result['bogon'])) { //Не записывать 127.0.0.1
                        //Yii::log('Geo add ip: ' . $ip, 'info', 'application');
                        $this->cache->set('geo_' . $ip, $result, self::CACHE_TIME);
                    } else {
                        //Yii::log('Geo bogonIP: ' . $ip, 'info', 'application');
                    }

                } else {
                    Yii::log('Geo limited', 'info', 'application');
                    $error = $connent->getErrors();
                    $result = array(
                        'status' => 'error',
                        'city' => 'unknown'
                    );

                }
            }

        }else{
            return $ip;
        }
        return $result;
    }

}

