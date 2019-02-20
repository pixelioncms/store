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

/**
 *
 * add to model Order
 *
 * 'android' => array(
 * 'class' => 'mod.android.behaviors.NotifyBehavior',
 * ),
 *
 */
class NotifyBehavior extends CActiveRecordBehavior
{

    private $_OLD_apikey = 'AAAAjlNFGXg:APA91bHwlBUo8177kxW9xR4L1HxJWxA6XNRkVeMwkyDIOjH9vJFQsxP3Ogh2AddvWJiAwzap1ZXD5Rmcu0AYQNCcZqQzDlTAUw0cv4ynoACeCzKMTB0ziOI0xl5PfxlqODsW4dtdslrY';
    private $apikey = 'AAAAlkYnQxc:APA91bFt42mArAjdwMqxIZw3Y4Gd48aW-YCBsOtBIjuMG4BSKi698xBXQif7aQfEJCpJO7WWQCdEKTOrwIkxJOfwh4SXkyLSoF4h6KSNuiYCZ60IIU0q5feyaCmTSxjhDl9ttLsYsyDr';

    public function attach($owner)
    {
        parent::attach($owner);
    }

    /**
     *
     * @param CModelEvent $event
     */
    public function beforeSave($event)
    {

        $owner = $this->owner;


        $tokens = array();
        $devicesTokens = Yii::app()->db->createCommand()
            ->select('token')
            ->from('{{android_fcm_devices}}')
            ->queryAll();

        foreach ($devicesTokens as $device) {
            $tokens[] = $device['token'];
        }

        $message = array('message' => Yii::t('app', '{productsCount} товара на сумму {total_price} {currency_cymbol}', array(
            '{productsCount}' => count($owner->products),
            '{total_price}' => Yii::app()->currency->number_format($owner->total_price),
            '{currency_cymbol}' => Yii::app()->currency->active->symbol,
        )));

        $send = $this->sendNotify($tokens, $message);
        if (!$send) {
            die('send error android');
        }
    }

    private function sendNotify($tokens, $message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
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
