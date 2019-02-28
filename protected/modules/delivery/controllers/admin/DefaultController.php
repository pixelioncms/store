<?php

class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('DeliveryModule.default', 'DELIVERYS');

        $this->topButtons = array(
            array(
                'label' => Yii::t('DeliveryModule.default', 'CREATE_DELIVERY'),
                'url' => $this->createUrl('createDelivery'),
                'htmlOptions' => array('class' => 'btn btn-success')
            ),
            array(
                'label' => Yii::t('DeliveryModule.default', 'CREATE_DELIVERY_MAIL'),
                'url' => $this->createUrl('create'),
                'htmlOptions' => array('class' => 'btn btn-success')
            )
        );

        $deliveryRecord = new Delivery('search');
        $deliveryRecord->unsetAttributes();  // clear any default values    
        if (Yii::app()->request->getPost('Delivery')) {
            $deliveryRecord->attributes = Yii::app()->request->getPost('Delivery');
        }
        $this->render('index', array('deliveryRecord' => $deliveryRecord));
    }

    public function actionUpdate($new = false) {
        $this->topButtons = false;
        if ($new === true) {
            $model = new Delivery;
            $model->unsetAttributes();
            $this->pageName = Yii::t('deliveryModule.default', 'Создание подписчика');
        } else {
            $model = $this->loadModel($_GET['id']);
            $this->pageName = Yii::t('deliveryModule.default', 'Редактирование подписчика');
        }
        $this->breadcrumbs = array(
            Yii::t('deliveryModule.default', 'MODULE_NAME') => array('index'),
            $this->pageName
        );
        if (Yii::app()->request->getPost('Delivery')) {
            $model->attributes = Yii::app()->request->getPost('Delivery');
            //$this->performAjaxValidation($model);
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionCreateDelivery() {
        $this->pageName=Yii::t('deliveryModule.default', 'MODULE_NAME');
        $this->topButtons = false;
        $model = new DeliveryForm;
        $delivery = Delivery::model()->findAll();
        $mails = array();
        $users = User::model()->subscribe()->findAll();
        $render = 'create';
        if (Yii::app()->request->getPost('DeliveryForm')) {
            $model->attributes =Yii::app()->request->getPost('DeliveryForm');
            //$this->performAjaxValidation($model);
            if ($model->validate()) {

                if ($model->from == 'all') {
                    foreach ($users as $user) {
                        $mails[] = $user->email;
                    }
                    //if (isset($delivery)) {
                    foreach ($delivery as $subscriber) {
                        $mails[] = $subscriber->email;
                    }
                    //} else {
                    //    $mails_subscriber = array();
                    //}
                    // $mails = array_merge($mails_users, $mails_subscriber);
                } elseif ($model->from == 'users') {
                    foreach ($users as $user) {
                        $mails[] = $user->email;
                    }
                } else {
                    foreach ($delivery as $subscriber) {
                        $mails[] = $subscriber->email;
                    }
                }


                if (Yii::app()->request->isAjaxRequest) {
                    $render = 'send';
                } else {
                    $render = 'create';
                }
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    $render = 'form';
                } else {
                    $render = 'create';
                }
                //Stops the request from being sent.
                //throw new CHttpException(404, 'Model has not been saved');
            }
        }


        $this->breadcrumbs = array(
            Yii::t('deliveryModule.default', 'MODULE_NAME') => array('index'),
            Yii::t('deliveryModule.default', 'CREATE_DELIVERY')
        );

        $this->render($render, array('users' => $users, 'delivery' => $delivery, 'model' => $model, 'mails' => $mails));
    }

    public function actionSendmail() {

      //  Yii::app()->request->enableCsrfValidation = false;
        if (Yii::app()->request->isAjaxRequest) {
            $host = $_SERVER['HTTP_HOST'];
            $mailer = Yii::app()->mail;
            $mailer->From = 'robot@' . $host;
            $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
            $mailer->Subject = $_POST['themename'];
            $mailer->Body = $_POST['text'];
            $mailer->AddAddress($_POST['email']);
            $mailer->AddReplyTo('robot@' . $host);
            $mailer->isHtml(true);
            $mailer->Send();
            $mailer->ClearAddresses();
        }
    }

    public function actionSendNewProduct() {
        $products = ShopProduct::model()->newToDay()->findAll();
        if (count($products)) {
            foreach ($products as $product) {
                print_r($product->name);
                echo '<br>';
            }
            $this->setNotify(Yii::t('app', 'Сообщение оправлено подписчикам.'));
        } else {
            $this->setNotify(Yii::t('app', 'Новых товаров за сегодня небыло добавлено!'));
        }
        $this->redirect(array('index'));
    }

    public function loadModel($id) {
        $model = Delivery::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'Отправить новые товары'),
                'url' => array('/admin/delivery/default/sendNewProduct'),
                'icon' => 'flaticon-shopcart',
                'visible'=>false
            ),
        );
    }

}
