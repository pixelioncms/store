<?php

/**
 * Контролле регистрации пользовотеля
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers
 * @uses Controller
 */
class RegisterController extends Controller {

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

    public function actionRegister() {
        $config = Yii::app()->settings->get('users');
        if (!Yii::app()->user->isGuest || !$config->registration)
            Yii::app()->request->redirect('/');


        $this->pageName = Yii::t('UsersModule.default', 'REGISTRATION');
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array($this->pageName);
        $user = new User('register');
        $view = 'index';
        if (Yii::app()->request->isPostRequest && isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if (Yii::app()->settings->get('app', 'forum') == null) {
                $user->email = $user->login;
            }
            $user->active = ($config->register_nomail) ? 1 : 0;
            if ($user->validate()) {
                if ($user->save()) {
                    if (Yii::app()->settings->get('app', 'forum') != null) {
                        CIntegrationForums::instance()->register($user->login, $_POST['User']['password'], $user->email);
                    }
                }

                Yii::app()->authManager->assign('Authenticated', $user->id);
                $identity = new UserIdentity($user->login, $_POST['User']['password']);
                if ($identity->authenticate()) {

                    Yii::app()->user->login($identity, Yii::app()->user->rememberTime);
                    if ($config->register_nomail) {
                        Yii::app()->user->setFlash('success', Yii::t('UsersModule.default', 'REG_SUCCESS'));
                        Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
                    } else {
                        $this->sendUserActive($user);
                        Yii::app()->user->setFlash('success', Yii::t('UsersModule.default', 'REG_SUCCESS_MAIL'));
                        Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
                    }
                }
            }
        }

        $this->render($view, array(
            'user' => $user,
        ));
    }

    public function sendUserActive(User $model) {
        /*
        $mailer = Yii::app()->mail;
        $mailer->From = Yii::app()->params['adminEmail'];
        $mailer->FromName = Yii::app()->params['adminEmail'];
        $mailer->Subject = Yii::t('UsersModule.default', 'Восстановление пароля');
        $mailer->Body = $this->body;
        $mailer->AddReplyTo(Yii::app()->params['adminEmail']);
        $mailer->isHtml(false);
        $mailer->AddAddress($this->email);
        $mailer->Send();*/
    }

}
