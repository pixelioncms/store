<?php
/**
 * @version 1.1
 */
class LicenseCMS1 extends CComponent {

    private static $run = null;
    public $timeout = 320; //curl timeout
    private $serverUrl = 'https://pixelion.com.ua/license';
    private $key;
    private $data = array();
    private $result = array();

    public function __construct() {
        $this->key = $this->config['license_key'];
        $this->data = array(
            'format' => 'json',
            'key' => $this->key,
            'domain' => Yii::app()->request->serverName,
            'v' => Yii::app()->version,
            'locale' => Yii::app()->language,
            'email' => $this->config['admin_email']
        );
    }

    public static function run() {
        static $run = null;
        if ($run === null) {
            $run = new LicenseCMS();
        }
        return($run);
    }

    /*
      public function check($key = null) {
      $domain = Yii::app()->request->serverName;
      if (!Yii::app()->request->isAjaxRequest) {
      $this->key = ($key) ? $key : $this->config['license_key'];
      if (file_exists($this->filePathLicense)) {
      $tmp = file_get_contents($this->filePathLicense);
      if ($tmp == md5(date('Ymd') . $domain . $this->key)) {
      return true;
      } else {
      $this->connect();
      }
      } else {
      $this->connect();
      }
      }
      }
     */

    /**
     * Проверяем наличие и содержание временого файла.
     * @return boolean
     */
    public function checkLicenseFile() {
        if (file_exists($this->getFilePathLicense())) {
            $tmp = file_get_contents($this->getFilePathLicense());
            if ($tmp == md5(date('Ymd') . $this->data['domain'] . $this->data['key'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function removeLicenseFile() {

        if (file_exists($this->getFilePathLicense())) {
            unlink($this->getFilePathLicense());
        }
    }

    public function writeLicenseFile() {
        $this->writeFile($this->getFilePathLicense(), md5(date('Ymd') . $this->data['domain'] . $this->key));
    }

    public function writeDataFile($data) {
        $this->writeFile($this->getFilePathData(), serialize($data));
    }

    public function write($data = null) {
        $datas = ($data) ? $data : $this->result['data'];
        $this->writeFile($this->getFilePathLicense(), md5(date('Ymd') . $this->data['domain'] . $this->key));
        if (isset($datas)) {
            $this->writeFile($this->getFilePathData(), serialize($datas));
        } else {

            throw new Exception(Yii::t('exception', 'no $data'));
        }
    }

    /*
      public function checkLicense() {

      if (!Yii::app()->request->isAjaxRequest) {
      if (Yii::app()->hasComponent('curl')) {
      $curl = Yii::app()->curl;
      $curl->options = array(
      'timeout' => $this->timeout,
      'setOptions' => array(
      CURLOPT_HEADER => false
      ),
      );

      $connent = $curl->run($this->serverUrl, $this->data);

      if (!$connent->hasErrors()) {
      $result = CJSON::decode($connent->getData());
      return $result;
      } else {
      return array('dasdsa' => 'dsa');
      }
      } else {
      throw new Exception(Yii::t('exception', 'COM_CURL_NOTFOUND', array('{com}' => 'curl')));
      }
      }
      }
     */

    /**
     * Чтение временого файла.
     * @return array
     */
    public function readData() {
        if (file_exists($this->filePathData)) {
            $data = file_get_contents($this->filePathData);
            return unserialize($data);
        } else {
            return null;
        }
    }

    public function connected($key = null) {
        $this->key = ($key) ? $key : $this->config['license_key'];
        $this->data['key'] = $this->key;
        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'timeout' => $this->timeout,
                'setOptions' => array(
                    CURLOPT_HEADER => false
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

    /**
     * Connecting to CMS server 
     */
    public function connect() {
        $domain = Yii::app()->request->serverName;


        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'timeout' => $this->timeout,
                'setOptions' => array(
                    CURLOPT_HEADER => false
                ),
            );

            $connent = $curl->run($this->serverUrl, $this->data);

            if (!$connent->hasErrors()) {
                $result = CJSON::decode($connent->getData());

                //if (isset($result)) {
                if ($result['status'] == 'success') {
                    $this->writeFile($this->filePathLicense, md5(date('Ymd') . $domain . $this->key));
                    if (isset($result['data'])) {
                        $this->writeFile($this->filePathData, serialize($result['data']));
                    }
                } else {
                    print_r($result);
                    //  Yii::app()->controller->setFlashMessage($result['message']);
                    // Yii::app()->settings->set('app', array('site_close' => 1));
                    // Yii::app()->settings->set('app', array('site_close_text' => $result['message']));
                }
                // if (isset($result['message'])) {
                // Yii::app()->controller->setFlashMessage($result['message']);
                // $this->getAlertLicense($result['message']);
                //  }
            } else {
                $error = $connent->getErrors();
                //   Yii::app()->controller->setFlashMessage('Connect error: ' . $error->code . ': ' . $error->message);
                print_r($error);
            }
        } else {
            throw new Exception(Yii::t('exception', 'COM_CURL_NOTFOUND', array('{com}' => 'curl')));
        }
    }

    public function getFilePathData() {
        return Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_data.txt";
    }

    public function getFilePathLicense() {
        return Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_license.txt";
    }

    protected function getConfig() {
        return Yii::app()->settings->get('app');
    }

    protected function writeFile($file, $content) {
        if ($file) {
            $fp = fopen($file, "wb");
            fwrite($fp, $content);
            fclose($fp);
        } else {
            die('error write license');
        }
    }

}
