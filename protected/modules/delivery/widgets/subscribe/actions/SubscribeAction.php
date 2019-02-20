<?php

class SubscribeAction extends CAction
{


    /**
     * Subscribe action
     * @throws CHttpException
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {


            $model = new Delivery();

            Yii::app()->controller->performAjaxValidation($model,'subscribe-form');

            if (isset($_POST['Delivery'])) {
                $model->attributes = $_POST['Delivery'];
                if ($model->validate()) {
                    $model->save();
                    $model->sendRecoveryMessage();
                    Yii::app()->user->setFlash('success', Yii::t('SubscribeWidget.default', 'SUBSCRIBE_SUCCESS', array('{email}' => $model->email)));
                }
            }


            // $this->controller->render('mod.delivery.widgets.subscribe.views._subscribe', array('model' => $model));
            // $this->controller->render('current_theme.views.layouts.inc.main.subscribe._subscribe', array('model' => $model));
            $this->controller->render(Yii::app()->request->getPost('skin'), array('model' => $model));
        } else {
            throw new CHttpException(403);
        }
    }


}
