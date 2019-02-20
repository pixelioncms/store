<?php

class DeliveryController extends Controller {

    public function actionProcess() {
        $delivery_id = (int) Yii::app()->request->getParam('delivery_id');
        $model = ShopDeliveryMethod::model()->findByPk($delivery_id);

        if (!$model)
            $this->error404('Ошибка');


        $system = $model->getDeliverySystemClass();
        if ($system instanceof BaseDeliverySystem) {
          //  $response = $system->processDeliveryRequest($model);
            Yii::app()->clientScript->registerCoreScript('jquery');
           $system->renderDeliveryForm($model);





           /* $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'city',
                'source' => array('ac1', 'ac2', 'ac3'),
                // additional javascript options for the autocomplete plugin
                'options' => array(
                    'minLength' => '2',
                ),
                'htmlOptions' => array(

                ),
            ), false);*/



           // if ($response instanceof Order)
            //    $this->redirect($this->createUrl('/cart', array('view' => $response->secret_key)));
           // else
           //     throw new CHttpException(404, Yii::t('CartModule.default', 'Возникла ошибка при обработке запроса. <br> {err}', array('{err}' => $response)));
        }
        Yii::app()->end();
    }

    public function actionNovaposhtaCities()
    {
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
    }
}
