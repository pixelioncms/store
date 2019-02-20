<?php

Yii::import('mod.cart.widgets.delivery.novaposhta.NovaposhtaConfigurationModel');

/**
 * Novaposhta delivery system
 */
class NovaposhtaDeliverySystem extends BaseDeliverySystem
{

    /**
     * This method will be triggered after redirection from payment system site.
     * If payment accepted method must return Order model to make redirection to order view.
     * @param ShopDeliveryMethod $method
     * @return boolean|Order
     */
    public function processDeliveryRequest(ShopDeliveryMethod $method)
    {
        $request = Yii::app()->request;


        //  if (isset($_POST['delivery'])) {
        //      parse_str($request->getPost('delivery'), $payments);
        //  }

        // $order_id = substr($payments['order'], 5);
        //  $settings = $this->getSettings($method->id);
        //  $order = Order::model()->findByPk($order_id);

        // if ($order === false)
        //     return false;
        // For first WebMoney pre-request
        //if (!isset($_POST['signature']) && isset($_GET['result']))
        //     die('YES');

        // $MERCHANT_ID = $settings['MERCHANT_ID'];
        // $MERCHANT_PASS = $settings['MERCHANT_PASS'];


        // Grab WM variables from post.
        // Variables to create signature.
        /* $forHash = array(
          'amt' => '',
          'ccy' => '',
          'details' => '',
          'ext_details' => '',
          'pay_way' => '',
          'order' => '',
          'merchant'=>$MERCHANT_ID
          ); */
        //parse_str(Yii::app()->request->getPost('payment'), $forHash);
        // foreach ($forHash as $key => $val) {
        //     if ($request->getParam($key))
        //         $forHash[$key] = $request->getParam($key);
        // }
        // Check if order is paid.
        // if ($order->paid) {
        //     Yii::log('Order is paid', 'info', 'payment privat24');
        //     return false;
        // }

        // unset($forHash['state'],$forHash['payCountry'],$forHash['ref'],$forHash['date']);
        //parse_str(Yii::app()->request->getPost('payment'), $test);
//print_r($forHash);
        // Check amount.
        //  if (Yii::app()->currency->active->iso != $payments['ccy']) {
        //      Yii::log('Currency error', 'info', 'payment privat24');
        //      return false;
        //  }


        // Set order paid
        // $order->paid = 1;
        // $order->save(false);


        // return $order;
    }

    public function renderDeliveryForm(ShopDeliveryMethod $method)
    {
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
        Yii::app()->clientScript->scriptMap = array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.css'=>false
        );
        Yii::app()->controller->render("mod.cart.widgets.delivery.{$method->delivery_system}._sa", array(
            'test' => $this->test(),
            'method' => $method
        ), false, true);

    }

    /**
     * This method will be triggered after payment method saved in admin panel
     * @param $paymentMethodId
     * @param $postData
     */
    public function saveAdminSettings($paymentMethodId, $postData)
    {
        $this->setSettings($paymentMethodId, $postData['NovaposhtaConfigurationModel']);
    }


    public function test()
    {
        $cacheIdCities = 'cache_novaposhta_cities';
        $value = Yii::app()->cache->get($cacheIdCities);
        if ($value === false) {
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
                       // echo $data['DescriptionRu'];
                        //echo '<br>';
                        /*echo $data['CitySender'];
                        echo '<br>';
                        echo $data['CityRecipient'];
                        echo '<br>';
                        echo $data['WarehouseRecipient'];
                        echo '<br>';
                        echo $data['DocumentCost'];*/

                        $value[$data['DescriptionRu']] = $data['DescriptionRu'];
                        // echo CVarDumper::dump($data, 10, true);
                    }

                } else {
                    echo 'error';
                }

            } else {
                $error = $connent->getErrors();
                print_r($error);
            }
            Yii::app()->cache->set($cacheIdCities, $value, 86400 * 346);
        }

        return $value;
    }

    /**
     * @param $paymentMethodId
     * @return string
     */
    public function getSettingsKey($paymentMethodId)
    {
        return $paymentMethodId . '_NovaposhtaDeliverySystem';
    }

    /**
     * Get configuration form to display in admin panel
     * @param string $deliveryMethodId
     * @return CForm
     */
    public function getConfigurationFormHtml($deliveryMethodId)
    {
        $model = new NovaposhtaConfigurationModel;
        $model->attributes = (array)$this->getSettings($deliveryMethodId);

        $form = new BaseDeliveryForm($model->getForm(), $model);

        return $form;
    }

}
