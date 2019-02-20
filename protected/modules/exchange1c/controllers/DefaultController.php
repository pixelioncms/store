<?php

class DefaultController extends Controller {

    public function actionIndex() {
        $request = Yii::app()->request;
        $config = Yii::app()->settings->get('exchange1c');

        
        if ($request->getQuery('password') != $config['password'])
            exit('ERR_WRONG_PASS');

        if ($request->userHostAddress != $config['ip']){
            exit('ERR_WRONG_IP');
            }

        if ($request->getQuery('type') && $request->getQuery('mode'))
            C1ProductsImport::processRequest($request->getQuery('type'), $request->getQuery('mode'));
        

    }

}
