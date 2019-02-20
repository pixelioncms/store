<?php

class GoogleTranslate {
    const API_KEY = 'AIzaSyDeuUyP2Q072o5c8LgF0aPXlG_n712XLd4';

    public $api_url = 'https://translation.googleapis.com/language/translate/v2?key=';
    
    
    
        private function curl_get_contents($url) {
        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'setOptions' => array(
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                )
            );
            $connect = $curl->run($url);
            if (!$connect->hasErrors()) {

                return $curl->getData();
            } else {
                return CJSON::encode($curl->getErrors());
            }
        } else {
            throw new Exception('Curl error');
        }
    }
    
}
