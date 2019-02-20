<?php

/**
 * @param string $this->db_user Имя пользователя базы данных
 * @param string $this->db_password Пароль базы данных
 * @param string $this->tbl_prefix Префикс таблиц базы данных
 * @param string $this->db_host Хост сервера базы данных (default = localhost)
 * @param string $thos->db_name Название базы данных
 * @param object $this->db Объект базы данных
 * 
 * 
 * @filesource generateCompiledPasshash() IPB function
 * @filesource generateAutoLoginKey() IPB function
 * @filesource generatePasswordSalt() IPB function
 */
Yii::import('app.integration.forums.classes.GlobalClass');

class PHPBB2 extends GlobalClass {

    public $board_config = array();

    public function __construct($version) {
        parent::__construct($version);
        include('forum/config.php');
        $this->db_user = $dbuser;
        $this->db_password = $dbpasswd;
        $this->db_host = $dbhost;
        $this->db_name = $dbname;
        $this->tbl_prefix = $table_prefix;
        if ($dbms == 'mysql') {
            $this->FDB = $this->setDb('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . '');
            $this->FDB->active = true;
        }

        $result = $this->FDB->createCommand("SELECT * FROM {$this->tbl_prefix}config");
        foreach ($result->queryAll() as $row) {
            $this->board_config[$row['config_name']] = $row['config_value'];
        }
    }

    /**
     * Выход с форума
     * @return boolean
     */
    public function log_out() {
        Yii::app()->request->enableCookieValidation = false;
        $cookiename = $this->board_config['cookie_name'];
        $cookiepath = $this->board_config['cookie_path'];
        $cookiedomain = $this->board_config['cookie_domain'];
        $cookiesecure = $this->board_config['cookie_secure'];
        $session_id = Html::text($_COOKIE[$cookiename . "_sid"]);
        $autologin_key = unserialize($_COOKIE[$cookiename . "_data"]);
        $current_time = time();
        if ($autologin_key['autologinid'] != "" AND $autologin_key['userid'] != "") {
            $autologin_key_hash = md5($autologin_key['autologinid']);
            $user_id = $autologin_key['userid'];
            $this->FDB->createCommand("DELETE FROM {$this->tbl_prefix}sessions_keys WHERE user_id ='$user_id' AND key_id = '$autologin_key_hash'")->execute();
        }
        if ($session_id != "")
            $this->FDB->createCommand("DELETE FROM {$this->tbl_prefix}sessions WHERE session_id = '$session_id'")->execute();
        setcookie($cookiename . '_data', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
        setcookie($cookiename . '_sid', '', $current_time - 31536000, $cookiepath, $cookiedomain, $cookiesecure);
        return true;
    }

    /**
     * Авторизация на форуме
     * @param string $login
     * @param string $password
     * @return boolean
     */
    public function log_in($login, $password) {
        Yii::app()->request->enableCookieValidation = false; //
        $row = $this->FDB->createCommand("SELECT user_id, user_active, username, user_password, user_level, user_login_tries, user_last_login_try FROM {$this->tbl_prefix}users WHERE username = '" . $login . "'")->queryRow();
        if (isset($row)) {
            if (md5($password) == $row['user_password'] AND $row['user_active']) {
                $user_ip = $this->encode_ip($_SERVER['REMOTE_ADDR']);
                $cookiename = $this->board_config['cookie_name'];
                $cookiepath = $this->board_config['cookie_path'];
                $cookiedomain = $this->board_config['cookie_domain'];
                $cookiesecure = $this->board_config['cookie_secure'];
                $user_id = $row['user_id'];
                $current_time = time();
                $auto_login_key = $this->dss_rand() . $this->dss_rand();
                $this->FDB->createCommand("UPDATE {$this->tbl_prefix}sessions_keys SET last_ip = '$user_ip', key_id = '" . md5($auto_login_key) . "', last_login = $current_time WHERE user_id='" . $user_id . "'")->execute();
                $e = @mysql_info();
                //print_r($e);
                // die;
                preg_match("/^\D+(\d+)/", $e, $matches);
                if ($matches[1] == 0)
                    $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}sessions_keys (key_id, user_id, last_ip, last_login) VALUES ('" . md5($auto_login_key) . "', '$user_id', '$user_ip', '$current_time')")->execute();
                $result = $this->FDB->createCommand("UPDATE {$this->tbl_prefix}sessions SET session_user_id = '" . $user_id . "', session_start = '" . $current_time . "', session_time = '" . $current_time . "', session_page = '" . $page_id . "', session_logged_in = '" . $login . "', session_admin = '" . $admin . "' WHERE session_user_id = '" . $user_id . "' AND session_ip = '$user_ip'")->execute();
                if (!$result) {
                    $session_id = md5($this->dss_rand());
                    $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}sessions (session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in, session_admin) VALUES ('$session_id', '$user_id', '$current_time', '$current_time', '$user_ip', '0', '1', '0')")->execute();
                }
                $sessiondata['autologinid'] = $auto_login_key;
                $sessiondata['userid'] = $user_id;
                setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure);
                setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Регистрация на форуме
     * @param string $login
     * @param string $password
     * @param string $email
     * @return boolean
     */
    public function register($login, $password, $user_email) {
        $user_password = md5($password);
        $result = $this->FDB->createCommand("SELECT user_id FROM {$this->tbl_prefix}users WHERE LOWER(username)='" . strtolower($login) . "' OR user_email='" . $user_email . "'")->queryRow();

        if (count($result) == 0) {
            return false;
        }
        list($last_id) = $this->FDB->createCommand("SELECT user_id FROM {$this->tbl_prefix}users ORDER by user_id DESC LIMIT 1")->queryColumn();
        $new_id = $last_id + 1;
        $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}users (user_id, username, user_regdate, user_password, user_email, user_active, user_lang) VALUES ('$new_id', '$login', '" . time() . "', '$user_password', '$user_email', '1', 'russian')")->execute();
        return true;
    }

    /**
     * DEMO no compited
     * @param type $user_name
     * @param type $user_pass
     * @return boolean
     */
    public function check_user($login, $user_pass) {
        $result = $this->FDB->createCommand("SELECT user_email FROM {$this->tbl_prefix}users WHERE LOWER(username)='" . strtolower($login) . "' AND user_password='" . md5($user_pass) . "'");
        $info = $result->queryRow();
        if (isset($info)) {
            $this->createUser($login, $user_pass, $info['user_email']);
        } else {
            return false;
        }
    }

    /**
     * Смена пароля на форуме
     * @param string $login Логин
     * @param string $newpass Новый пароль
     * @param string $email Почта
     * @return boolean
     */
    public function changepassword($login, $newpass, $email) {
        $result = $this->FDB->createCommand("SELECT user_id FROM {$this->tbl_prefix}users WHERE LOWER(username)='" . strtolower($login) . "' AND user_email='" . $email . "'");
        $info = $result->queryRow();
        if (isset($info)) {
            $this->FDB->createCommand("UPDATE {$this->tbl_prefix}users SET user_password='" . md5($newpass) . "' WHERE user_id='" . $info['user_id'] . "'")->execute();
        } else {
            return false;
        }
    }

    public function encode_ip($dotquad_ip) {
        $ip_sep = explode('.', $dotquad_ip);
        return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
    }

    public function dss_rand() {
        $val = $this->board_config['rand_seed'] . microtime();
        $val = md5($val);
        $this->board_config['rand_seed'] = md5($this->board_config['rand_seed'] . $val . 'a');
        return substr($val, 4, 16);
    }


}