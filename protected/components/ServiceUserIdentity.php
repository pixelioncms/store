<?php

/**
 * ServiceUserIdentity class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses UserIdentity
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 * @ignore
 */
class ServiceUserIdentity extends UserIdentity {

    const ERROR_NOT_AUTHENTICATED = 3;

    /**
     * @var EAuthServiceBase the authorization service instance.
     */
    protected $service;

    /**
     * Constructor.
     * @param EAuthServiceBase $service the authorization service instance.
     */
    public function __construct($service) {
        $this->service = $service;
    }

    /**
     * Authenticates a user based on {@link username}.
     * This method is required by {@link IUserIdentity}.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $login = $this->service->serviceName . $this->service->getAttribute('id');

        if ($this->service->isAuthenticated) {
            $app_user = User::model()->findByAttributes(array('login' => $login));
            //если пользователя ещё нет - создаём
            if (!$app_user) {
                $app_user = $this->createUser($login);
            } else { //Обновляем информацию о пользователе
                $app_user = $this->refreshUser($login);
            }
//die(print_r($app_user));
            //die('auth: ' . print_r($this->service->getAttributes()));
            $this->applyUser($app_user);

            $this->errorCode = self::ERROR_NONE;
        } else {
            $this->errorCode = self::ERROR_NOT_AUTHENTICATED;
        }
        return !$this->errorCode;
    }

    private function applyUser($user) {



        $this->_id = $user->id;
        $this->setState('id', $user->id);
        $this->setState('username', $this->service->getAttribute('name'));
        $this->setState('service', $user->service);
    }

    private function refreshUser($login) {
        $model = User::model()->findByAttributes(array('login' => $login));
        $model->username = $this->service->getAttribute('name');
        $model->service = $this->service->serviceName;
        if($this->service->getAttribute('photo')) {
            $model->avatar = $this->service->getAttribute('photo');
        }

        if($this->service->getAttribute('birthday')){
            $model->date_birthday = $this->service->getAttribute('birthday');
        }
        if($this->service->getAttribute('gender')){
            $model->gender = $this->service->getAttribute('gender');
        }
        if($this->service->getAttribute('email')){
            $model->email = $this->service->getAttribute('email');
        }
        $model->last_login = date('Y-m-d H:i:s');
        $model->save(false, false, false);
        Yii::log($this->service->getAttribute('photo_small'),'info','application');
        return $model;
    }

    private function createUser($login) {

        $model = new User;
        $tmpname = array();
        preg_match('/^([^\s]+)\s*(.*)?$/', $this->service->getAttribute('name'), $tmpname); //разделение имени по запчастям
        //$newUser->firstname = $tmpname[1];
        //$newUser->lastname = $tmpname[2];
        $model->login = $login;
        $model->username = $this->service->getAttribute('name');
        if($this->service->getAttribute('photo')) {
            $model->avatar = $this->service->getAttribute('photo');
        }
       // if($this->service->getAttribute('timezone')) {
       //     $model->timezone = $this->service->getAttribute('timezone');
       // }
        //if($this->service->getAttribute('gender')){
       //     $model->gender = $this->service->getAttribute('gender');
       // }

        $model->service = $this->service->serviceName;
       // if($this->service->getAttribute('birthday')){
       //     $model->date_birthday = $this->service->getAttribute('birthday');
       // }
        $model->subscribe = 1;
        $model->active = true;
        $model->last_login = date('Y-m-d H:i:s');
        $model->date_registration = date('Y-m-d H:i:s');
        if ($model->validate()) {
            $model->save(false, false,false);
            Yii::app()->authManager->assign('Authenticated', $model->id);
        } else {
            print_r($model->getErrors());
            die;
        }


        return $model;
    }

}
