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

class IPB extends GlobalClass {

    public function __construct($version) {
        parent::__construct($version);
       // die($this->forum_path);
        include($this->forum_path.'/conf_global.php');
        //print_r($INFO);
        //require 'forum/conf_global.php';
        $this->db_user = $INFO['sql_user'];
        $this->db_password = $INFO['sql_pass'];
        $this->db_host = $INFO['sql_host'];
        $this->db_name = $INFO['sql_database'];
        $this->tbl_prefix = $INFO['sql_tbl_prefix'];
        //if ($INFO['sql_driver'] == 'mysql') {
            $this->forum_db = $this->setDb('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . '');
            $this->forum_db->active = true;
       // }
    }

    /**
     * Выход с форума
     * @return boolean
     */
    public function log_out() {
        $this->forum_db->createCommand("UPDATE {$this->tbl_prefix}sessions SET member_name='', seo_name='', member_id='0', login_type='0' WHERE id='" . Yii::app()->request->cookies['session_id']->value . "'")->execute();
        Yii::app()->request->enableCookieValidation = false;
        Yii::app()->request->cookies['member_id'] = new CHttpCookie('member_id', '0');
        Yii::app()->request->cookies['pass_hash'] = new CHttpCookie('pass_hash', '0');
        Yii::app()->request->cookies['session_id'] = new CHttpCookie('session_id', '-1');
        $this->forum_db->createCommand("UPDATE {$this->tbl_prefix}members SET last_visit='" . CMS::time() . "', last_activity='" . CMS::time() . "' WHERE member_id='" . Yii::app()->request->cookies['member_id']->value . "'")->execute();
        return true;
    }

    /**
     * Авторизация на форуме
     * @param string $login
     * @param string $password
     * @return boolean
     */
    public function log_in($login, $password) {
        $password = md5($password);
       
        $member = $this->forum_db->createCommand("SELECT * FROM {$this->tbl_prefix}members WHERE name='" . $login . "'")->queryRow();
        if ($member['members_pass_hash'] != self::generateCompiledPasshash(str_replace("\\\\", '\\', $member['members_pass_salt']), $password)) {
            return false;
        }
        $sid = md5(uniqid(microtime()));
        $expire = CMS::time() + intval(84600 * Yii::app()->settings->get('app', 'cookie_time'));
        $pass_hash_set = $member['member_login_key'];
        $this->stronghold_set_cookie($member['member_id'], $member['member_login_key']);
        Yii::app()->request->enableCookieValidation = false;
        Yii::app()->request->cookies['member_id'] = new CHttpCookie('member_id', $member['member_id'], array('expire' => $expire));
        Yii::app()->request->cookies['pass_hash'] = new CHttpCookie('pass_hash', $pass_hash_set, array('expire' => $expire));
        Yii::app()->request->cookies['session_id'] = new CHttpCookie('session_id', $sid, array('expire' => $expire));
        $this->forum_db->createCommand("UPDATE {$this->tbl_prefix}members SET ip_address='" . $_SERVER['REMOTE_ADDR'] . "' WHERE member_id='" . $member['member_id'] . "'")->execute();
        $this->forum_db->createCommand("DELETE FROM {$this->tbl_prefix}sessions WHERE ip_address='" . $_SERVER['REMOTE_ADDR'] . "'")->execute();
        $userag = CMS::getagent();
        $browser = substr($userag, 0, 64);
        $ip = substr($_SERVER['REMOTE_ADDR'], 0, 16);
        $this->forum_db->createCommand("INSERT INTO {$this->tbl_prefix}sessions (id, member_name, seo_name, member_id, running_time, ip_address, browser, login_type, member_group) VALUES ('$sid', '" . $member['name'] . "','" . $member['name'] . "', '" . $member['member_id'] . "',  '" . CMS::time() . "', '$ip', '$browser', '0', '" . $member['member_group_id'] . "')")->execute();
        return true;
    }

    /**
     * Регистрация на форуме
     * @param string $login
     * @param string $password
     * @param string $email
     * @return boolean
     */
    public function register($login, $password, $email) {
        $member = $this->forum_db->createCommand("SELECT member_id FROM {$this->tbl_prefix}members WHERE LOWER(name)='" . strtolower($login) . "'  OR email='" . $email . "' OR LOWER(members_display_name)='" . strtolower($login) . "'")->query();
        if (count($member) == 0) {
            $ip = substr($_SERVER['REMOTE_ADDR'], 0, 16);
            $member_login_key = self::generateAutoLoginKey();
            $key_exp = CMS::time() + 604800;
            $salt = self::generatePasswordSalt(5);
            $salt = str_replace('\\', "\\\\", $salt);
            $passhash = md5(md5($salt) . md5($password));
            list($lastID) = $this->forum_db->createCommand("SELECT member_id FROM {$this->tbl_prefix}members ORDER BY member_id DESC LIMIT 1")->queryColumn();
            $this->forum_db->createCommand("INSERT INTO {$this->tbl_prefix}members (member_id, name, member_group_id, email, joined, ip_address, language, member_login_key, member_login_key_expire, members_display_name, members_l_display_name, members_l_username,members_pass_hash, members_pass_salt) VALUES (NULL, '$login', '3', '$email', '" . CMS::time() . "', '$ip', '1', '$member_login_key', '$key_exp', '$login', '" . strtolower($login) . "', '" . strtolower($login) . "', '" . $passhash . "','" . $salt . "')")->execute();
            $result = $this->forum_db->createCommand("SELECT * FROM {$this->tbl_prefix}cache_store WHERE cs_key='stats'")->queryRow();
            $stats = unserialize($result['cs_value']);
            $stats['last_mem_name'] = $login;
            $stats['mem_count'] = $stats['mem_count'] + 1;
            $stats['last_mem_id'] = $lastID;
            $stats = serialize($stats);
            $this->forum_db->createCommand("UPDATE {$this->tbl_prefix}cache_store SET cs_value='$stats' WHERE cs_key='stats'")->execute();
        } else {
            return false; //allready
        }
    }
    /**
     * DEMO no compited
     * @param string $login
     * @param string $user_pass
     * @return boolean
     */
    public function check_user($login, $user_pass) {
        $result = $this->forum_db->createCommand("SELECT email, members_pass_hash, members_pass_salt FROM {$this->tbl_prefix}members WHERE LOWER(name)='" . strtolower($login) . "'");
        $info = $result->queryRow();
        if (isset($info)) {
            $new_passhash = self::generateCompiledPasshash($info['members_pass_salt'], md5($user_pass));
            if ($new_passhash == $info['members_pass_hash']) {
                $this->createUser($login, $user_pass, $info['email']);
            } else {
                return false;
            }
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
        $member = $this->forum_db->createCommand("SELECT member_id FROM {$this->tbl_prefix}members WHERE LOWER(name)='" . strtolower($login) . "' AND email='" . $email . "'");
        if (isset($member)) {
            $info = $member->queryRow();
            $salt = self::generatePasswordSalt(5);
            $new_passhash = self::generateCompiledPasshash($salt, md5($newpass));
            $this->forum_db->createCommand("UPDATE {$this->tbl_prefix}members SET members_pass_hash='$new_passhash', members_pass_salt='$salt' WHERE member_id='" . $info['member_id'] . "'")->execute();
        } else {
            return false;
        }
    }

    private function stronghold_set_cookie($member_id, $member_log_in_key) {
        global $sql_pass, $sql_user;
        $ip_octets = explode(".", $_SERVER["REMOTE_ADDR"]);
        $crypt_salt = md5($sql_pass . $sql_user);
        $stronghold = md5(md5($member_id . "-" . $ip_octets[0] . '-' . $ip_octets[1] . '-' . $member_log_in_key) . $crypt_salt);
        setcookie("ipb_stronghold", $stronghold, CMS::time() + 31536000);
        return true;
    }

    /**
     * Generates a compiled passhash.
     * Returns a new MD5 hash of the supplied salt and MD5 hash of the password
     *
     * @param	string		User's salt (5 random chars)
     * @param	string		User's MD5 hash of their password
     * @return	string		MD5 hash of compiled salted password
     */
    static public function generateCompiledPasshash($salt, $md5_once_password) {
        return md5(md5($salt) . $md5_once_password);
    }

    /**
     * Generates a password salt.
     * Returns n length string of any char except backslash
     *
     * @param	integer		Length of desired salt, 5 by default
     * @return	string		n character random string
     */
    static public function generatePasswordSalt($len = 5) {
        $salt = '';
        for ($i = 0; $i < $len; $i++) {
            $num = mt_rand(33, 126);
            if ($num == '92') {
                $num = 93;
            }
            $salt .= chr($num);
        }
        return $salt;
    }

    /**
     * Generates a log in key
     *
     * @param	integer		Length of desired random chars to MD5
     * @return	string		MD5 hash of random characters
     */
    static public function generateAutoLoginKey($len = 60) {
        $pass = self::generatePasswordSalt($len);
        return md5($pass);
    }

}