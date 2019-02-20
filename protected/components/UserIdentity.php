<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CUserIdentity
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 * @ignore
 */
class UserIdentity extends CUserIdentity {

    protected $_id;
    protected $_login;

    public function authenticate() {
        $record = User::model()->findByAttributes(array('login' => $this->username));



       /* if(false){
            $record = (object) array(
                'id'=>0,
                'login'=>'root',
                'password'=>'admin',
                'banned'=>0
            );
            $this->_id = $record->id;
            $this->_login = $record->login;
            $this->setState('id', $record->id);
            $this->setState('username', $record->login);
            $this->errorCode = self::ERROR_NONE;
        }*/

        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if ($record->banned === '1')
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($record->password !== User::encodePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $record->id;
            $this->_login = $record->login;
            $record->last_login = CMS::getDate();
            $record->login_ip = CMS::getip();
            $record->save(false, false, false);
            $this->setState('id', $record->id);
            $this->setState('username', $record->login);
            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

    function validateIP($ip) {
        return inet_pton($ip) !== false;
    }

    public function getLogin() {
        return $this->_login;
    }

}
