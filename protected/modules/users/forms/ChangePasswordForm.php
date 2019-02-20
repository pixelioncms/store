<?php

class ChangePasswordForm extends FormModel {

    const MODULE_ID = 'users';

    /**
     * @var string
     */
    public $current_password;

    /**
     * @var string
     */
    public $new_password;
    public $new_repeat_password;

    /**
     * @var User
     */
    public $user;

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('current_password, new_password, new_repeat_password', 'required'),
            array('new_password, new_repeat_password', 'length', 'min' => 4, 'max' => 40),
            array('current_password', 'validateCurrentPassword'),
             array('new_repeat_password', 'validatePasswords'),
        );
    }

    public function validateCurrentPassword() {
        if (User::encodePassword($this->current_password) != $this->user->password)
            $this->addError('current_password', self::t('ERR_CURRENT_PASSWORD'));
    }
    public function validatePasswords(){
       if ($this->new_password != $this->new_repeat_password)
            $this->addError('new_repeat_password', self::t('ERR_PASSWORDS'));
    }

}
