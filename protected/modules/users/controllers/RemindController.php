<?php

Yii::import('mod.users.forms.RemindPasswordForm');

/**
 * Контроллер востановление паролья и активации пользователей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers
 * @uses Controller
 */
class RemindController extends Controller {

    /**
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action) {
        // Allow only gues access
        if (Yii::app()->user->isGuest)
            return parent::beforeAction($action);
        else
            $this->redirect('/');
    }

    public function actionIndex() {
        $model = new RemindPasswordForm;
        $this->pageName = Yii::t('UsersModule.default', 'REMIND_PASS');
        $this->breadcrumbs = array($this->pageName);
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['RemindPasswordForm'];
            if ($model->validate()) {
                $model->sendRecoveryMessage();
                Yii::app()->user->setFlash('success', Yii::t('UsersModule.default', 'REMIND_SUCCESS'));
                //$this->setNotify(Yii::t('UsersModule.default', 'На вашу почту отправлены инструкции по активации нового пароля.'));
                $this->refresh();
            }
        }

        $this->render('index', array(
            'model' => $model
        ));
    }

    /**
     * @param $key
     */
    public function actionActivatePassword($key) {
        if (User::activeNewPassword($key) === true) {
            Yii::app()->user->setFlash('success', Yii::t('UsersModule.default', 'REMIND_ACTIVE_SUCCESS'));
           // $this->setNotify(Yii::t('UsersModule.default', 'REMIND_ACTIVE_SUCCESS'));
            $this->redirect(array('/users/login/login'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('UsersModule.default', 'REMIND_ACTIVE_ERROR'));
            //$this->setNotify(Yii::t('UsersModule.default', 'REMIND_ACTIVE_ERROR'));
            $this->redirect(array('/users/remind'));
        }
    }

}
