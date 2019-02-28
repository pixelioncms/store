<?php

Yii::import('users.forms.UserLoginForm');

class AuthController extends AdminController
{

    public $layout = 'login';

    public function allowedActions()
    {
        return 'index';
    }

    public function actionIndex()
    {
        if (!Yii::app()->user->isGuest)
            $this->redirect('/admin');

        $model = new UserLoginForm;

        $this->setPageTitle(Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('app', 'site_name'))));
        if (isset($_POST['UserLoginForm'])) {

            $resp = array('error' => false);

            //$respss = (CMS::checkApp())?'yes':'mo';




            $model->attributes = $_POST['UserLoginForm'];
            if ($model->validate(false)) {
                $duration = ($model->rememberMe) ? Yii::app()->settings->get('app', 'cookie_time') : 0;
                //$duration = Yii::app()->settings->get('app', 'cookie_time');
                if (Yii::app()->user->login($model->getIdentity(), $duration)) {
                    Yii::app()->timeline->set(Yii::t('timeline', 'LOGIN'));


                    $resp['user'] = array(
                        'uid' => '12',
                        'login' =>'asddsadasdsa', //$email
                        'email' => 'saasddsadsa', //$email
                        'created_at' => '141551',
                        'password' => 'dsadsaa'
                    );

                    $this->setNotify(Yii::t('app', 'WELCOME', array('{user_name}' => Yii::app()->user->getName())));
                    $this->redirect($this->createUrl('/admin'));
                } else {
                    //$resp = array('error' => true,'error_msg'=>$respss.Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                    Yii::app()->user->setFlash('error', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            } else {
                Yii::app()->timeline->set(Yii::t('timeline', 'ERROR_AUTH', array(
                    '{login}' => $model->login
                )));
                $resp = array('error' => true,'error_msg'=>Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
               // print_r($model->getErrors());

                Yii::app()->user->setFlash('error', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                $this->redirect($this->createUrl('/admin/auth'));
            }
            //echo json_encode($resp);
            //Yii::app()->end();

        }


        $this->render('auth', array('model' => $model));
    }

}
