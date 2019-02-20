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

Yii::import('mod.shop.models.*');

/**
 * Class DefaultController
 * PROBLEMS
 * 1. https
 * 2. ereors_reporting(all)
 */
class DefaultController extends Controller
{

    // private $apikey = 'AAAAjlNFGXg:APA91bHwlBUo8177kxW9xR4L1HxJWxA6XNRkVeMwkyDIOjH9vJFQsxP3Ogh2AddvWJiAwzap1ZXD5Rmcu0AYQNCcZqQzDlTAUw0cv4ynoACeCzKMTB0ziOI0xl5PfxlqODsW4dtdslrY';
    private $apikey = 'AAAAlkYnQxc:APA91bGafdakk6H4MRTpH3M9z5aixfB7ZPi8V_OkfQmHEQ2UBOpVpVsTDJRlgRdzrg7aD48mLKWVkqcX8TcHNAt-lUQZtsg0ENgwkmal_pS0oUITshdgvTiVw_bUEFSdgAEGMjuLoH8N';

    /**
     * @param $tokens
     * @param $message
     * @return mixed
     */
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

    public function actionSendNotify()
    {
        $tokens = array();
        $devicesTokens = Yii::app()->db->createCommand()
            ->select('token')
            ->from('{{android_fcm_devices}}')
            ->queryAll();

        foreach ($devicesTokens as $device) {
            $tokens[] = $device['token'];
        }


        $message['message'] = array(
            //'topic' => 'test',
            "notification" => array(
                'id' => rand(1, 100),
                'vibrate' => true,
                'action' => 'callback',
                "title" => "Перезвони мне",
                "text" => "на номер +3 (111) 123-45-45",
                "phone" => '+3 (111) 123-45-45',
                'summaryText' => '',
                'lines' => array(
                    'dasdsa',
                    'dasdsa123'
                ),
            ));


        $message_status = $this->sendNotify($tokens, $message);
        echo $message_status;
        //Yii::log($message_status,'application','info');
        Yii::app()->end();

    }

    public function actionFcmRegister()
    {
        if (isset($_POST['Token'])) {
            $token = $_POST['Token'];


            $android_version = Yii::app()->request->getPost('android_version');
            $android_incremental = Yii::app()->request->getPost('android_incremental');
            $android_sdk = Yii::app()->request->getPost('android_sdk');
            $sim_operator_name = Yii::app()->request->getPost('sim_operator_name');
            $sim_operator_country = Yii::app()->request->getPost('sim_operator_country');
            $sim_operator_provider_name = Yii::app()->request->getPost('sim_operator_provider_name');
            $sim_operator_provider_country = Yii::app()->request->getPost('sim_operator_provider_country');

            $metrics_height_pix = Yii::app()->request->getPost('metrics_height_pix');
            $metrics_width_pix = Yii::app()->request->getPost('metrics_width_pix');

            $xdpi = Yii::app()->request->getPost('xdpi');
            $ydpi = Yii::app()->request->getPost('ydpi');

            $scaled_density = Yii::app()->request->getPost('scaled_density');
            $density_dpi = Yii::app()->request->getPost('density_dpi');

            $cores = Yii::app()->request->getPost('cores');

            $ip = CMS::getip();
            $user_agent = (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : Yii::app()->request->userAgent;
            $command = Yii::app()->db->createCommand("INSERT INTO {{android_fcm_devices}} (token, 
ip_address, 
user_agent,
android_version,
android_sdk,
android_incremental,
sim_operator_name,
sim_operator_country,
sim_operator_provider_country,
sim_operator_provider_name,
metrics_height_pix,
metrics_width_pix,
xdpi,
ydpi,
scaled_density,
density_dpi,
cores
) Values (
'$token',
'$ip',
'$user_agent',
'$android_version',
'$android_sdk',
'$android_incremental',
'$sim_operator_name',
'$sim_operator_country',
'$sim_operator_provider_country',
'$sim_operator_provider_name',
'$metrics_height_pix',
'$metrics_width_pix',
'$xdpi',
'$ydpi',
'$scaled_density',
'$density_dpi',
'$cores'
) ON DUPLICATE KEY UPDATE token = '$token';");
            $command->execute();
            Yii::log($token, 'info', 'application');
        } else {
            Yii::log('error fcm register', 'info', 'application');
            die('Goodbye.');
        }
    }


    public function actionProductList()
    {
        $response = array();

        $model = ShopProduct::model()
            ->published()
            ->findAll(array('limit' => 10));
        if ($model) {
            $response['items'] = array();
            foreach ($model as $product) {
                $response['items'][] = array(
                    'id' => $product->id,
                    'name' => $product->name,
                    "rating" => 7.8,
                    "sku" => ($product->sku) ? $product->sku : null,
                    "director" => "Joss Whedon",
                    "tagline" => "A new age begins",
                    "cast" => array(
                        array(
                            "name" => "Robert Downey Jr."
                        ),
                        array(
                            "name" => "Chris Evans"
                        ),
                        array(
                            "name" => "Mark Ruffalo"
                        )
                    ),
                    //"description" => $product->short_description,
                    "description" => 'dasdasdas',
                    //"description" => $product->full_description,
                    'price' => Yii::app()->currency->number_format($product->price),
                    'currency_symbol' => Yii::app()->currency->active->symbol,
                    'manufacturer' => Yii::app()->currency->active->symbol,
                    'mainCategory' => ($product->mainCategory) ? $product->mainCategory->name : null,
                    'image' => 'http://april.corner-cms.com/uploads/product/423_34553721.jpg',
                    // 'image'=>Yii::app()->createAbsoluteUrl('uploads/2RSRfKbxuL.jpg'),
                    //'image' => $this->getOriginalImageUrl($product)
                    //'image' => Yii::app()->createAbsoluteUrl($product->getMainImageUrl('original'))
                );
            }
        }

        echo CJSON::encode($response);
        Yii::app()->end();
    }

    public function actionOrderList()
    {
        Yii::import('mod.cart.models.*');
        Yii::import('mod.cart.CartModule');
        $response = array();


        $model = Order::model()
            ->findAll();
        if ($model) {

            foreach ($model as $order) {
                $responseProducts = array();
                foreach ($order->products as $product) {
                    $responseProducts[] = array(
                        'product_name' => $product->name,
                        //'image' => $this->getOriginalImageUrl($product),
                        'product_test' => 'babab',
                        'product_image' => 'http://test.aprilgroup.ru/assets/product/800x860/11_1648670444.jpg'
                    );
                }

                $response['items'][] = array(
                    'order_id' => $order->id,
                    'user_name' => $order->user_name,
                    'user_email' => $order->user_email,
                    'user_phone' => $order->user_phone,
                    'deliveryMethod' => $order->deliveryMethod->name,
                    'paymentMethod' => $order->paymentMethod->name,
                    'currency_symbol' => Yii::app()->currency->active->symbol,
                    'status' => $order->status->name,
                    'status_color' => $order->status->color,
                    'paid' => ($order->paid) ? true : false,
                    'total_price' => Yii::app()->currency->number_format($order->total_price + $order->delivery_price),
                    'productsCount' => count($order->products),
                    'productsItems' => $responseProducts
                );
            }
        }

        echo CJSON::encode($response);
    }


    public function actionTestList($key, $id)
    {
        if ($this->checkKey($key)) {
            Yii::import('mod.cart.models.*');
            Yii::import('mod.cart.CartModule');
            $response = array();


            $model = Order::model()
                ->with('products')
                ->findByPk($id);
            if ($model) {
                foreach ($model->products as $product) {
                    $response['items'][] = array(
                        'name' => $product->name,
                        //'image' => $this->getOriginalImageUrl($product->prd),
                        'image' => 'test',
                        'quantity' => $product->quantity,
                    );
                }
            }

            echo CJSON::encode($response);
        }
    }


    private function checkKey($key)
    {
        if ($key == "pan1") {
            return true;
        } else {
            die('Error');
        }
    }


    private function getOriginalImageUrl($model)
    {
        if ($model->attachmentsMain) {
            return Yii::app()->request->hostInfo . '/uploads/product/' . $model->getMainImageUrl('original');
        }
    }

    public function actionGetToken()
    {

        $resp = array(
            'error'=>false,
            'token' => Yii::app()->request->csrfToken,
        );
        echo json_encode($resp);
        Yii::app()->end();
    }
    public function actionLogin()
    {
        Yii::import('mod.users.forms.UserLoginForm');
        $model = new UserLoginForm;
        $resp = array('error' => false);
        if (isset($_POST['UserLoginForm'])) {


            //$respss = (CMS::checkApp())?'yes':'mo';


            $model->attributes = $_POST['UserLoginForm'];
            if ($model->validate(false)) {
                //$duration = ($model->rememberMe) ? Yii::app()->settings->get('app', 'cookie_time') : 0;
                $duration = Yii::app()->settings->get('app', 'cookie_time');
                if (Yii::app()->user->login($model->getIdentity(), $duration)) {
                    Yii::app()->timeline->set(Yii::t('timeline', 'LOGIN'));


                    $resp['user'] = array(
                        'uid' => '12',
                        'token' => Yii::app()->request->csrfToken,
                        'login' => 'asddsadasdsa', //$email
                        'email' => 'saasddsadsa', //$email
                        'created_at' => '141551',
                        'password' => 'dsadsaa'
                    );
                Yii::log($_POST['token'].'-'.Yii::app()->request->csrfToken,'info','application');
                    // $this->setFlashMessage(Yii::t('app', 'WELCOME', array('{user_name}' => Yii::app()->user->getName())));
                } else {
                    $resp = array('error' => true, 'error_msg' => Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            } else {
                Yii::app()->timeline->set(Yii::t('timeline', 'ERROR_AUTH', array(
                    '{login}' => $model->login
                )));
                $resp = array('error' => true, 'error_msg' => Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                // print_r($model->getErrors());
            }


        }
        echo json_encode($resp);
        Yii::app()->end();
    }

}
