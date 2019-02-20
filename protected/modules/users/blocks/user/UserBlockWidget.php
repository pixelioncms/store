<?php

class UserBlockWidget extends BlockWidget {

    public $alias = 'mod.users.blocks.user';

    public function getTitle() {
        return Yii::t('UsersModule.default', 'MODULE_NAME');
    }
    public function init() {
        parent::init();
    }
    public function run() {
        $this->skin = (Yii::app()->user->isGuest) ? 'user_auth' : 'user_info';
        if (Yii::app()->user->isGuest) {
            Yii::import('mod.users.forms.UserLoginForm');
            $model = new UserLoginForm;
            if (isset($_POST['UserLoginForm'])) {
                $model->attributes = $_POST['UserLoginForm'];
                if ($model->validate()) {
                    CIntegrationForums::instance()->check_user($model->login, $model->password);
                    $duration = ($model->rememberMe) ? Yii::app()->settings->get('app', 'cookie_time') : 0;
                    $model->authenticate();
                    if (Yii::app()->user->login($model->getIdentity(), $duration)) {
                        Yii::app()->timeline->set(Yii::t('timeline', 'LOGIN'));
                        Yii::app()->controller->refresh();
                    } else {
                        Yii::app()->user->setFlash('login-error', 'Login error');
                    }
                } else {
                    Yii::app()->user->setFlash('login-error', 'No validate');
                }
            }
        } else {
            $model = false;
        }
        $this->render($this->skin, array('model' => $model, 'online' => Session::online()));
    }

}