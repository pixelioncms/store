<?php

class ErrorsController extends AdminController {

    public $topButtons = false;

    public function allowedActions() {
        return 'index';
    }

    public function actionIndex() {
        $this->pageName = Yii::t('default', 'ERROR');
        $this->breadcrumbs = array($this->pageName);

        $this->layout = 'mod.admin.views.layouts.main';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', array('error' => $error));
        }
    }

}
