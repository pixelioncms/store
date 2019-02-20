<?php

/**
 * @author Andrew S. <andrew.panix@gmail.com>
 * @package components.integration
 * @subpackage forum
 * @version 1.0
 * 
 * Рекомендация
 * Отключить регистрацю пользователе на форуме.
 */
Yii::import('app.integration.forums.classes.GlobalClass');

class CIntegrationForums {

    private static $instance = null;
    public $forum;
    public $forum_path;
    public $_config;
    public $_nameClass;

    public static function instance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new CIntegrationForums();
        }
        return($instance);
    }

    public function __construct() {
        if ($this->checked()) {
            $exp = explode('|', $this->_config->forum);
            if ($exp[0] == 'ipb') { //ready
                $this->_nameClass = 'IPB';
            } elseif ($exp[0] == 'vb5') {
                $this->_nameClass = 'VB5';
            } elseif ($exp[0] == 'smf') {
                $this->_nameClass = 'SMF';
            } elseif ($exp[0] == 'phpbb3') { //ready
                $this->_nameClass = 'PHPBB3';
            } elseif ($exp[0] == 'phpbb2') { //ready
                $this->_nameClass = 'PHPBB2';
            } else {
                die('ERROR FORUM NOT FIND');
            }
            Yii::import("app.integration.forums.classes.{$this->_nameClass}");
            $this->forum = new $this->_nameClass($exp[1]);
            return $this->forum;
        } else {
            return false;
        }
    }

    public function checked() {
        $this->_config = Yii::app()->settings->get('app');
        return ($this->_config->forum != null) ? true : false;
    }

    public function log_in($login, $password) {
        return ($this->checked()) ? $this->forum->log_in($login, $password) : false;
    }

    public function register($login, $password, $email) {
        return ($this->checked()) ? $this->forum->register($login, $password, $email) : false;
    }

    public function log_out() {
        return ($this->checked()) ? $this->forum->log_out() : false;
    }

    public function check_user($login, $password) {
        return ($this->checked()) ? $this->forum->check_user($login, $password) : false;
    }

    public function changepassword($login, $newpass, $email) {
        return ($this->checked()) ? $this->forum->changepassword($login, $newpass, $email) : false;
    }

}

?>