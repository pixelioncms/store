<?php

/**
 * WebUser class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses RWebUser
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class WebUser extends RWebUser
{

    private $_identity;

    /**
     * @var int
     */
    public $rememberTime = 2622600;

    /**
     * @var User model
     */
    private $_model;
    public $guestName = 'GUEST';


    public function login($identity, $duration = 0)
    {
        $this->_identity = $identity;
        return parent::login($identity, $duration);
    }

    public function afterLogin($fromCookie)
    {
        if ($this->_identity !== null) {
            CIntegrationForums::instance()->log_in($this->_identity->username, $this->_identity->password);
        }
        return parent::afterLogin($fromCookie);
    }

    public function afterLogout()
    {
        CIntegrationForums::instance()->log_out();
    }

    /**
     * @return string user email
     */
    public function getEmail()
    {
        $this->_loadModel();
        return (isset($this->_model->email)) ? $this->_model->email : null;
    }


    public function getTheme()
    {
        $this->_loadModel();
        return $this->_model->theme;
    }

    public function getLanguage()
    {
        $this->_loadModel();
        return $this->_model->language;
    }

    public function getTimezone()
    {
        $this->_loadModel();
        return $this->_model->timezone;
    }

    public function getLast_login()
    {
        $this->_loadModel();
        return $this->_model->last_login;
    }

    public function getLogin()
    {
        $this->_loadModel();
        return $this->_model->login;
    }

    public function getPhone()
    {
        $this->_loadModel();
        return $this->_model->phone;
    }

    public function getAddress()
    {
        $this->_loadModel();
        return $this->_model->address;
    }

    public function getAccessMessage()
    {
        if (Yii::app()->user->message) {
            return true;
        } else {
            throw new CHttpException(401, MessageModule::t('ACCESS_DENIED_USER'));
        }
    }

    /**
     * @return string username
     */
    public function getUsername()
    {
        $this->_loadModel();
        return (isset($this->_model->username)) ? $this->_model->username : null;
    }

    public function getService()
    {
        $this->_loadModel();
        return $this->_model->service;
    }

    public function getMessage()
    {
        $this->_loadModel();
        return $this->_model->message;
    }

    public function getEdit_mode()
    {
        $this->_loadModel();
        return $this->_model->edit_mode;
    }

    public function getAdminTheme()
    {
        $this->_loadModel();
        return $this->_model->admin_theme;
    }


    public function getIsEditMode()
    {
        if (!$this->isGuest) {
            $this->_loadModel();
            if ($this->_model->edit_mode && $this->isSuperuser) { // && !Yii::app()->controller->isAdminController
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getRoles()
    {
        $result = array();
        $this->_loadModel();
        if ($this->_model) {
            foreach (Rights::getAssignedRoles($this->id) as $role) {
                $result[] = $role->name;
            }
        }
        return $result;
    }

    public function getIsEditMode2()
    {
        $this->_loadModel();
        if (!$this->isGuest && $this->_model->edit_mode && $this->isSuperuser && Yii::app()->request->isAjaxRequest) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Load user model
     */
    private function _loadModel()
    {
        if (!$this->_model)
            $this->_model = User::model()->findByPk($this->id);

    }

    public function getModel()
    {
        $this->_loadModel();
        return $this->_model;
    }

    public function getAvatarUrl($size = false, $isGuest = false)
    {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = (isset($this->_model->avatar)) ? $this->_model->avatar : null;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {
            if (!$isGuest) {
                if (empty($ava)) {
                    $returnUrl = CMS::processImage($size, 'user.png', 'users.avatars', 'user_avatar', array(
                        'watermark' => false,
                    ));
                } else {
                    $returnUrl = CMS::processImage($size, $ava, 'users.avatar', 'user_avatar', array(
                        'watermark' => false,
                    ));
                }
            } else {
                $returnUrl = CMS::processImage($size, 'guest.png', 'users.avatars', 'user_avatar', array(
                    'watermark' => false,
                ));
            }
            return $returnUrl;
        } else {
            return $ava;
        }
    }

}
