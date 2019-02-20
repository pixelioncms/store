<?php

/**
 * User login form
 */
class UserLoginForm extends FormModel {

    const MODULE_ID = 'users';

    public $login;
    public $password;
    public $rememberMe = false;
    private $_identity;

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('login, password', 'required'),
           // array('login', 'checkExistUser'),
            array('password', 'authenticate'),
            array('rememberMe', 'boolean'),
        );
    }

    /**
     * Try to authenticate user
     */
    public function authenticate() {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->login, $this->password);
            if (!$this->_identity->authenticate()) {
                if ($this->_identity->errorCode === UserIdentity::ERROR_PASSWORD_INVALID) {
                    $this->addError('password', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            }
        }
    }

    public function checkExistUser($attr) {
        $labels = $this->attributeLabels();
        $check = User::model()->countByAttributes(array($attr => $this->$attr));

        if ($check > 0)
            $this->addError($attr, Yii::t('usersModule.default', 'ERROR_ALREADY_USED', array('{attr}' => $labels[$attr])));
    }

    /**
     * @return mixed
     */
    public function getIdentity() {
        return $this->_identity;
    }

}
