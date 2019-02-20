<?php
$data = LicenseCMS::run()->getData();
// $pw = User::encodePassword('aprilpw');
$pw = 'admin';
//Yii::app()->user->setState('http_auth', false);
$url = 'https://pixelion.com.ua/license/auth';
print_r($data);
//foreach($data['http_auth'] as $login =>$pass){
   // $url = 'https://'.$login.':'.$pass.'@pixelion.com.ua/license/auth';
   // break;
//}

       // $upgrade = new Upgrade();
       // $upgrade->filename = 'upgrade-test222.zip';
        //$upgrade->download();
        //$upgrade->setup();


if (Yii::app()->hasComponent('curl')) {
    $curl = Yii::app()->curl;
    // $curl->setHttpLogin('admin','admin');
    $curl->options = array(
        'timeout' => 320,
        'setOptions' => array(
            //CURLOPT_HTTPHEADER=> array('Content-Type: application/json'),
            CURLOPT_HEADER => false,
           // CURLOPT_VERBOSE=>true,
           // CURLOPT_FAILONERROR=>true,
           // CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,
            //CURLOPT_HTTPHEADER => $header,
           // CURLOPT_RETURNTRANSFER => 1,

           // CURLOPT_FOLLOWLOCATION => 1,
           // CURLOPT_POST=>true,
            //  CURLOPT_NOBODY=>true,
            //CURLOPT_HTTPAUTH=>CURLAUTH_ANY,
           // CURLOPT_USERPWD => "admin:admin",
        ),
       /* 'login' => array(
            'username' => 'admin',
            'password' => 'admin'
        ),*/
    );
    $connent = $curl->run($url,array('test'=>1));

    if (!$connent->hasErrors()) {
        //print_r($connent->getData());
        $result = CJSON::decode($connent->getData());


  //  echo $connent->getInfo();
        print_r($result);
    } else {
        $error = $connent->getErrors();
        $result = array(
            'status' => 'error',
            'message' => $error->message,
            'code' => $error->code
        );


        print_r($error);
    }
} else {
    throw new Exception(Yii::t('exception', 'COM_CURL_NOTFOUND', array('{com}' => 'curl')));
}

