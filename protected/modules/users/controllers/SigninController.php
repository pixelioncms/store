<?php

/**
 * Контролле регистрации и входа пользовотеля
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers
 * @uses Controller
 */
class SigninController extends Controller {

    public function allowedActions() {
        return 'register';
    }

    /**
     * Дополнительные действия
     * @return array
     */
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'transparent' => true,
                'testLimit' => 1,
                'padding' => 0,
                'height' => 40
//'foreColor' => 0x348017
            ),
        );
    }

    public function actionIndex() {
        $config = Yii::app()->settings->get('users');
        if (!Yii::app()->user->isGuest || !$config['registration'])
            Yii::app()->request->redirect('/');

        Yii::import('mod.users.forms.UserLoginForm');
        $this->pageName = Yii::t('UsersModule.default', 'REGISTRATION');
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array($this->pageName);
        $register = new User('register');
        $login = new UserLoginForm();
        $view = 'index';
        if (Yii::app()->request->isPostRequest && isset($_POST['User'])) {
            $register->attributes = $_POST['User'];
            if (Yii::app()->settings->get('app', 'forum') == null) {
                $register->email = $register->login;
            }
            $register->active = ($config['register_nomail']) ? 1 : 0;
            if ($register->validate()) {
                if ($register->save()) {
                    if (Yii::app()->settings->get('app', 'forum') != null) {
                        CIntegrationForums::instance()->register($register->login, $_POST['User']['password'], $user->email);
                    }
                }

                Yii::app()->authManager->assign('Authenticated', $register->id);
                $this->setNotify(Yii::t('UsersModule.default', 'REG_SUCCESS'));

                $identity = new UserIdentity($register->login, $_POST['User']['password']);
                if ($identity->authenticate()) {

                    Yii::app()->user->login($identity, Yii::app()->user->rememberTime);
                    if ($config['register_nomail']) {
                        Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
                    } else {
                        $view = 'success_register';
                    }
                }
            }
        }


        if (Yii::app()->request->isPostRequest && isset($_POST['UserLoginForm'])) {
            $login->attributes = $_POST['UserLoginForm'];
            if ($login->validate()) {
                $duration = ($login->rememberMe) ? Yii::app()->settings->get('app', 'cookie_time') : 0;
                if (Yii::app()->user->login($login->getIdentity(), $duration)) {
                    Yii::app()->timeline->set(Yii::t('timeline', 'LOGIN'));
                    if (Yii::app()->request->isAjaxRequest) {
                        $view = 'success_login';
                    } else {
                        $this->refresh();
                    }
                } else {
                    // if (count(User::model()->findByAttributes(array('password' => User::encodePassword($model->password)))) < 1)
                    //     $model->addError('login', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                    if (count(User::model()->findByAttributes(array('login' => $login->login))) < 1 || count(User::model()->findByAttributes(array('password' => User::encodePassword($login->password)))) < 1)
                        $login->addError('login', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            }
        }

        $this->render($view, array(
            'register' => $register,
            'login' => $login,
        ));
    }

}
