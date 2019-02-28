<?php

class DefaultController extends Controller {

    //public $layout = '//layouts/main';

    public function actions() {
        return array(
            'subscribe.' => 'mod.delivery.widgets.subscribe.SubscribeWidget',
        );
    }

    public function actionIndex() {

if(Yii::app()->user->hasFlash('success') || Yii::app()->user->hasFlash('error')){
    $this->render('index');
}else{
    throw new CHttpException(404);
}

    }

    public function actionSend() {
        $model = new Delivery();
        if (isset($_POST['Delivery'])) {
            $model->attributes = $_POST['Delivery'];
            if ($model->validate()) {
                $model->save();
                Yii::app()->user->setFlash('success', 'Вы успешно подписались');
            } else {
                Yii::app()->user->setFlash('error', 'Email введен неверно');
            }
        }
        $this->render('mod.delivery.widgets.delivery.views._delivery', array('model' => $model));
    }

    /**
     * @param $key
     */
    public function actionConfirmed($key) {
        $model = Delivery::activeNewPassword($key);
        if ($model) {
            Yii::app()->user->setFlash('success', Yii::t('DeliveryModule.default', 'CONFIRMED_SUCCESS',array(
                '{name}'=>$model->name,
                '{email}'=>$model->email
            )));
            // $this->setNotify(Yii::t('UsersModule.default', 'REMIND_ACTIVE_SUCCESS'));
            $this->redirect(array('/delivery/default/index'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('DeliveryModule.default', 'CONFIRMED_ERROR'));
            //$this->setNotify(Yii::t('UsersModule.default', 'REMIND_ACTIVE_ERROR'));
            $this->redirect(array('/delivery/default/index'));
        }
    }

}