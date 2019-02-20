<?php

class Exchange24Block extends BlockWidget {

    public $timeout = 320; //curl timeout
    public $serverUrl = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';

    public function run() {
        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'timeout' => $this->timeout,
                'setOptions' => array(
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SSL_VERIFYPEER => false,
                ),
            );
            $connent = $curl->run($this->serverUrl, array());
        }
     $data = array();
        if (!$connent->hasErrors()) {
            $currencies = CJSON::decode($connent->getData());


       
            $series = array();
            $categories = array();
            unset($currencies[3]);
            foreach ($currencies as $currency) {
                $data['Покупка'][] = round($currency['buy'], 2);
                $data['Продажа'][] = round($currency['sale'], 2);
                $categories[] = $currency['ccy'];
            }

            foreach ($data as $cur => $curdata) {
                //  
                $series[] = array(
                    'name' => $cur,
                    'data' => $curdata,
                );
            }
        } else {
            $error = $connent->getErrors();
            if ($error->code == 22) {
                $result = array(
                    'status' => 'error',
                    'message' => $error->message,
                    'code' => $error->code
                );
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => $error->message,
                    'code' => $error->code
                );
            }
        }



        // print_r($categories);
        $this->render($this->skin, array(
            'data' => $data,
            'series' => $series,
            'categories' => $categories
        ));
    }

}
