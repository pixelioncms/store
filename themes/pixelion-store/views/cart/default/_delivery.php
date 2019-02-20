<?php
$cacheIdCities = 'cache_novaposhta_cities';
$value=Yii::app()->cache->get($cacheIdCities);
if($value===false) {
    $curl = Yii::app()->curl;
    $curl->options = array(
        'timeout' => 320,
        'setOptions' => array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => CJSON::encode(array(
                //"modelName" => "AddressGeneral",
                //"calledMethod" => "getWarehouses",
                "modelName" => "Address",
                 "calledMethod" => "getCities",

                /* "modelName" => "TrackingDocument",
                 "calledMethod" => "getStatusDocuments",

                 "methodProperties" => array(
                     // "Language" => "ru"
                     //"CityName" => "Одесса"
                     "Documents" => array(
                         array(
                             "DocumentNumber" => "20400097190568",
                         ),
                     )
                 ),*/
                "Language" => "ru",
                "apiKey" => "b7562676aa545994573dc363ccb2c6b1"
            ))
        ),
    );

    $connent = $curl->run("https://api.novaposhta.ua/v2.0/json/");

    if (!$connent->hasErrors()) {
        $result = CJSON::decode($connent->getData());
        if ($result['success']) {
            foreach ($result['data'] as $data) {
                echo $data['DescriptionRu'];
                echo '<br>';
                /*echo $data['CitySender'];
                echo '<br>';
                echo $data['CityRecipient'];
                echo '<br>';
                echo $data['WarehouseRecipient'];
                echo '<br>';
                echo $data['DocumentCost'];*/

                $value[$data['DescriptionRu']]=$data['DescriptionRu'];
               // echo CVarDumper::dump($data, 10, true);
            }

        } else {
            echo 'error';
        }

    } else {
        $error = $connent->getErrors();
        print_r($error);
    }
    Yii::app()->cache->set($cacheIdCities,$value,86400*346);
}

print_r($value);