<?php

/**
 * Контроллер авторизации пользователей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers
 * @uses Controller
 */
class LoginController extends Controller
{

    public function allowedActions()
    {
        return 'login, logout';
    }

    public function actionLogin()
    {
        $this->pageName = Yii::t('UsersModule.default', 'AUTH');
        $this->pageTitle = $this->pageName;

        $this->serviceAuth();

        if (!Yii::app()->user->isGuest) {
            Yii::app()->request->redirect('/');
        }

        Yii::import('mod.users.forms.UserLoginForm');
        $model = new UserLoginForm;
        $view = (Yii::app()->request->isAjaxRequest) ? '_form' : 'login';
        if (Yii::app()->request->getIsPostRequest()) {
            $model->attributes = $_POST['UserLoginForm'];

            // integration forum
            // CIntegrationForums::instance()->check_user($model->login, $model->password);
            if ($model->validate()) {
                $duration = ($model->rememberMe) ? Yii::app()->settings->get('app', 'cookie_time') : 0;
                if (Yii::app()->user->login($model->getIdentity(), $duration)) {
                    Yii::app()->timeline->set(Yii::t('timeline', 'LOGIN'));
                    if (Yii::app()->request->isAjaxRequest) {
                        $view = 'ajax_success_login';
                    } else {
                        $this->refresh();
                    }
                } else {
                    // if (count(User::model()->findByAttributes(array('password' => User::encodePassword($model->password)))) < 1)
                    //     $model->addError('login', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                    if (count(User::model()->findByAttributes(array('login' => $model->login))) < 1 || count(User::model()->findByAttributes(array('password' => User::encodePassword($model->password)))) < 1)
                        $model->addError('login', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        }
        $this->render($view, array(
            'model' => $model,
        ), false, true);
    }

    /**
     * Logout user
     */
    public function actionLogout()
    {
        if (!Yii::app()->user->isGuest) {
            Yii::app()->timeline->set(Yii::t('timeline', 'LOGOUT'));
            $session = Session::model()->findByAttributes(array('user_name' => Yii::app()->user->login));
            if ($session) {
                $session->delete();
            }
            Yii::app()->user->logout();
        }
        Yii::app()->request->redirect(Yii::app()->user->returnUrl);
    }

    private function serviceAuth()
    {
        $service = Yii::app()->request->getQuery('service');
        if (isset($service)) {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = '/users/login';
            $authIdentity->cancelUrl = $this->createAbsoluteUrl('login');
            try {
                if ($authIdentity->authenticate()) {

                    $identity = new ServiceUserIdentity($authIdentity);
                    // $identity = new EAuthUserIdentity($authIdentity);
                    // Успешный вход
                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity, Yii::app()->user->rememberTime);
                        // die(print_r($identity->authenticate()));
                        // Специальный редирект с закрытием popup окна
                        $authIdentity->redirect();
                    } else {
                        die('error: cancel();');
                        // Закрываем popup окно и перенаправляем на cancelUrl
                        $authIdentity->cancel();
                    }
                }
            } catch (EAuthException $e) {
                // save authentication error to session
                Yii::app()->user->setFlash('error', 'EAuthException: ' . $e->getMessage());
                // close popup window and redirect to cancelUrl
                $authIdentity->redirect($authIdentity->getCancelUrl());
            }
            // Что-то пошло не так, перенаправляем на страницу входа
            //  $this->redirect(array('login'));
        }
    }

}
