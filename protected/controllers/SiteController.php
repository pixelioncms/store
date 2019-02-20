<?php

class SiteController extends Controller
{

    /**
     * Action error for frontend
     */
    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        $this->layout = 'current_theme.views.layouts.error';

        if ($error) {
            $this->pageName = Yii::t('default', 'ERROR') . ' ' . $error['code'];
            $this->pageTitle = $this->pageName;
            $this->breadcrumbs = array($this->pageName);

            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('current_theme.views.layouts._error', array('error' => $error));
            }
        }
    }



}
