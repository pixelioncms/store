<?php

class DefaultController extends Controller
{

    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'testLimit' => '1',
            ),
        );
    }

    public function actionGetAddressList()
    {
        $data = $_POST['data'];

        $model = ContactsAddress::model()
            //->published()
            ->findAllByAttributes(array('city_id' => $data['index']));
        $address = array();
        if ($model) {
            $address['success'] = true;
            foreach ($model as $addr) {
                $coord = explode(',', $addr->coords);
                $address['address'][] = array(
                    'name' => $addr->name,
                    'coordx' => (float)$coord[1],
                    'coordy' => (float)$coord[0],
                    'weekdays_time' => $addr->weekdays_time,
                    'weekend_time' => $addr->weekend_time
                );
            }
        } else {
            $address['success'] = false;
            $address['message'] = 'у нас нет представительств в этом городе';
        }


        echo CJSON::encode($address);
        die;
        //$this->render('address_list', array('model' => $model));
    }

    public function actionIndex()
    {
        $this->pageName = Yii::t('ContactsModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName);
        $model = new ContactForm;
        $model->performAjaxValidation();
        $config = Yii::app()->settings->get('contacts');
        if (Yii::app()->request->getPost('ContactForm')) {
            $model->attributes = Yii::app()->request->getPost('ContactForm');
            if (Yii::app()->request->isPostRequest && $model->validate()) {


                //$model->performAjaxValidation();
                $model->sendMessage();
                $model->unsetAttributes();


                if (Yii::app()->request->isAjaxRequest) {
                    $json = array(
                        'status' => 'success',
                        'message' => Yii::t('ContactsModule.default', 'MESSAGE_SUCCESS')
                    );
                    $this->setJson($json);
                }

                //  Yii::app()->user->setFlash('success', Yii::t('ContactsModule.default', 'MESSAGE_SUCCESS'));
                //  $this->setNotify(Yii::t('ContactsModule.default', 'MESSAGE_SUCCESS'));
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    $json = array(
                        'status' => 'error',
                        'message' => Yii::t('ContactsModule.default', 'MESSAGE_FAIL')
                    );
                    $this->setJson($json);
                }


                //$this->setNotify(Yii::t('ContactsModule.default', 'MESSAGE_FAIL'));
                Yii::app()->user->setFlash('error', Yii::t('ContactsModule.default', 'MESSAGE_FAIL'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => $config));

    }

}
