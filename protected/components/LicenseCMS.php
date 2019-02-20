<?php

/**
 * LicenseCMS class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CComponent
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 * @ignore
 */
class LicenseCMS extends CComponent {

    private static $run = null;
    public $timeout = 320; //curl timeout
    private $serverUrl = 'https://pixelion.com.ua/license';
    private $key;
    private $data = array();
    private $result = array();
    const CACHE_TIME = 86400;//сутки

    const CACHE_DATA = 'cache_license_data'; //b62c0e93c1b7ebacca38e564865cfac6.bin
    const CACHE_KEY = 'cache_license_key'; //d13979f76fb00b210d52166aa6a023ef.bin

    public function __construct() {
        // if(isset($this->config['license_key'])){
        //     $key = $this->config['license_key'];
        // }else{
        //     $key = $_REQUEST['license_key'];
        //}
        // $this->key = $key;
        $this->data['format'] = 'json';
        $this->data['key'] = $this->key;
        $this->data['domain'] = Yii::app()->request->serverName;
        $this->data['locale'] = Yii::app()->language;
        // $this->data['email'] = $this->config['admin_email'];
        if (isset(Yii::app()->version))
            $this->data['v'] = Yii::app()->version;
    }

    public static function run() {
        static $run = null;
        if ($run === null) {
            $run = new LicenseCMS();
        }
        return($run);
    }

    public function checkLicense() {
        $value = Yii::app()->cache->get(self::CACHE_KEY);
        if ($value !== false) {
            if ($value == md5(date('Ymd') . $this->data['domain'] . $this->data['key'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Проверяем наличие и содержание временого файла.
     * @return boolean
     */
    public function removeLicenseCache() {
        Yii::app()->cache->delete(self::CACHE_KEY);
    }

    //c21d5d60dbbb8f863bddca6d948d4caf.bin
    public function writeLicenseCache() {
        $value = Yii::app()->cache->get(self::CACHE_KEY);
        if ($value === false) {
            Yii::log('write license ' . date('Y-m-d H:i:s'));
            $data = md5(date('Ymd') . $this->data['domain'] . $this->key);
            Yii::app()->cache->set(self::CACHE_KEY, $data, self::CACHE_TIME);
        }
    }

    public function writeDataCache() {
        $value = Yii::app()->cache->get(self::CACHE_DATA);
        if ($value === false) {
            $data = LicenseCMS::run()->connected();
            Yii::app()->cache->set(self::CACHE_DATA, serialize($data['data']), self::CACHE_TIME);
        }
    }

    /**
     * Чтение временого файла.
     * @return array
     */
    public function getData() {
        return unserialize(Yii::app()->cache->get(self::CACHE_DATA));
    }

    public function connected($key = false) {

        $this->key = ($key) ? $key : $this->config['license_key'];
        $this->data['key'] = $this->key;
        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'timeout' => $this->timeout,
                'setOptions' => array(
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER=>1,
                    CURLOPT_SSL_VERIFYPEER => false,
                ),
            );
            $connent = $curl->run($this->serverUrl, $this->data);

            if (!$connent->hasErrors()) {
                $result = CJSON::decode($connent->getData());
                $this->result = $result;
                return $this->result;
            } else {
                $error = $connent->getErrors();
                
                if ($error->code == 22) {
                    $this->result = array(
                        'status' => 'error',
                        'message' => $error->message,
                        'code' => $error->code
                    );
                } else {
                    $this->result = array(
                        'status' => 'error',
                        'message' => $error->message,
                        'code' => $error->code
                    );
                }

                return $this->result;
            }
        } else {
            throw new Exception(Yii::t('exception', 'COM_CURL_NOTFOUND', array('{com}' => 'curl')));
        }
    }

    /*
      public function getFilePathData() {
      return Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_data.txt";
      }

      public function getFilePathLicense() {
      return Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_license.txt";
      }
     */

    protected function getConfig() {
        if (Yii::app()->hasComponent('settings')) {
            return Yii::app()->settings->get('app');
        } else {
            return array();
        }
    }

}
