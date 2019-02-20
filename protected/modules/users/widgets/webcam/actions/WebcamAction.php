<?php

/**
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * 
 */
Yii::import('mod.users.widgets.webcam.Webcam');

class WebcamAction extends CAction {

    public function run() {

        Yii::app()->request->enableCsrfValidation = false;
        //if(Yii::app()->request->isAjaxRequest){

        $this->controller->render('mod.users.widgets.webcam.views.render');
        // }else{
        // throw new CHttpException(401);
        // }
    }

}