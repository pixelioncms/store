<?php

$curl = Yii::app()->curl;
$curl->options = array(
    'timeout' => 320,
    'setOptions' => array(
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
    ),
);
$connent = $curl->run("https://api.novaposhta.ua/v2.0/json/", array(
    "modelName" => "AddressGeneral",
    "calledMethod" => "getWarehouses",
    "methodProperties" => array(
        "Language" => "ru"
    ),
    "apiKey" => "b7562676aa545994573dc363ccb2c6b1"
));

if (!$connent->hasErrors()) {
    $result = CJSON::decode($connent->getData());
    print_r($result);
} else {
    $error = $connent->getErrors();


    print_r($error);
}