<?php

Yii::import('mod.shop.ShopModule');

class AndroidModule extends WebModule
{

    public $icon = 'icon-android';
    private $apikey = 'AAAAlkYnQxc:APA91bGafdakk6H4MRTpH3M9z5aixfB7ZPi8V_OkfQmHEQ2UBOpVpVsTDJRlgRdzrg7aD48mLKWVkqcX8TcHNAt-lUQZtsg0ENgwkmal_pS0oUITshdgvTiVw_bUEFSdgAEGMjuLoH8N';

    public function init()
    {

        $this->setImport(array(
            $this->id . '.models.*',
        ));
        $this->configure(self::getInfo());
        parent::init();
    }

    public function afterInstall()
    {
        Yii::app()->database->import($this->id);
        return parent::afterInstall();

    }

    public function afterUninstall()
    {
        Yii::app()->db->createCommand()->dropTable('{{android_fcm_devices}}');
        return parent::afterUninstall();
    }

    public function getRules()
    {
        return array(
            'android/login' => array('android/default/login'),
            'android/get_token' => array('android/default/getToken'),
            'android/productList' => array('android/default/productList'),
            'android/orderList' => array('android/default/orderList'),
            'android/fcmRegister' => array('android/default/fcmRegister'),
            'android/sendNotify' => array('android/default/sendNotify'),
            'android/testList/<key>/<id>' => array('android/default/testList'),
        );
    }

    public function getInfo()
    {
        return array(
            'name' => Yii::t('AndroidModule.default', 'MODULE_NAME'),
            'author' => 'info@pixelion.com.ua',
            'version' => '1.0',
            'icon' => $this->icon,
            'url' => false,
            'description' => Yii::t('AndroidModule.default', 'MODULE_DESC'),
        );
    }

    public function push(array $data){
        if ($data) {
            $tokens = array();
            $devicesTokens = Yii::app()->db->createCommand()
                ->select('token')
                ->from('{{android_fcm_devices}}')
                ->queryAll();

            foreach ($devicesTokens as $device) {
                $tokens[] = $device['token'];
            }

            return $this->sendNotify($tokens, $data);
        }else{
            die('error');
        }
    }
    private function sendNotify($tokens, $message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message,
        );
        $headers = array(
            'Authorization:key=' . $this->apikey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed:' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
