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

class VB5 extends GlobalClass {

    public $cookieprefix;
    public $fcookiesalt;

    public function __construct($version) {
        parent::__construct($version);
        include('vb5/core/includes/config.php');

        $this->cookieprefix = $config['Misc']['cookieprefix'];
        $this->fcookiesalt = $config['Misc']['cookie_security_hash'];
        $this->db_user = $config['MasterServer']['username'];
        $this->db_password = $config['MasterServer']['password'];
        $this->db_host = $config['MasterServer']['servername'];
        $this->db_name = $config['Database']['dbname'];
        $this->tbl_prefix = $config['Database']['tableprefix'];
        if ($config['Database']['dbtype'] == 'mysql') {
            $this->FDB = $this->setDb('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . '');
            $this->FDB->active = true;
        }
    }

    /**
     * Выход с форума
     * @return boolean
     */
    public function log_out() {
  Yii::app()->request->enableCookieValidation = false;
        $this->FDB->createCommand("DELETE FROM {$this->tbl_prefix}session WHERE sessionhash='" . Html::text($_COOKIE[$this->cookieprefix . 'sessionhash']) . "'")->execute();
        setcookie($this->cookieprefix . "userid", "");
        setcookie($this->cookieprefix . "password", "");
        setcookie($this->cookieprefix . "sessionhash", "");
        return true;
    }

    /**
     * Авторизация на форуме
     * @param string $login
     * @param string $password
     * @return boolean
     */
    
    public function fetch_substr_ip($ip, $length = null) {
        if($length === NULL OR $length > 3) {
            $length = 1;
        }
        return implode('.', array_slice(explode('.', $ip), 0, 4 - $length));
    } 

      public function fetch_alt_ip() {
        $alt_ip = $_SERVER['REMOTE_ADDR'];

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $alt_ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            $ranges = array(
                '10.0.0.0/8' => array(ip2long('10.0.0.0'), ip2long('10.255.255.255')),
                '127.0.0.0/8' => array(ip2long('127.0.0.0'), ip2long('127.255.255.255')),
                '169.254.0.0/16' => array(ip2long('169.254.0.0'), ip2long('169.254.255.255')),
                '172.16.0.0/12' => array(ip2long('172.16.0.0'), ip2long('172.31.255.255')),
                '192.168.0.0/16' => array(ip2long('192.168.0.0'), ip2long('192.168.255.255')),
            );
            foreach ($matches[0] AS $ip) {
                $ip_long = ip2long($ip);
                if ($ip_long === false OR $ip_long == -1) {
                    continue;
                }

                $private_ip = false;
                foreach ($ranges AS $range) {
                    if ($ip_long >= $range[0] AND $ip_long <= $range[1])
                    {
                        $private_ip = true;
                        break;
                    }
                }

                if (!$private_ip) {
                    $alt_ip = $ip;
                    break;
                }
            }
        } else if (isset($_SERVER['HTTP_FROM'])) {
            $alt_ip = $_SERVER['HTTP_FROM'];
        }

        return $alt_ip;
    } 
    public function log_in($login, $password) {
        
        $expire = time() + intval(84600 * Yii::app()->settings->get('app', 'cookie_time'));
        $user_name = strtolower(str_replace('|', '&#124;', $login));
        $user_password = md5($password);
        $member = $this->FDB->createCommand("SELECT userid, usergroupid, membergroupids, infractiongroupids, username, password, salt FROM {$this->tbl_prefix}user WHERE LOWER(username) = '" . $user_name . "'")->queryRow();
        if (isset($member)) {
            if ($member['password'] != md5($user_password . $member['salt'])) {
                return false;
            }
           // $ip = substr($_SERVER['REMOTE_ADDR'], 0, 16);
            $ip = implode('.', array_slice(explode('.', $ip), 0, 4 - 1));
            $userag = CMS::getagent();
            $session_idhash = md5($userag . $ip);
            $scriptpath = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_ENV['REQUEST_URI'];
            $sessionhash = md5(time() . $scriptpath . $session_idhash . $ip . mt_rand(1, 1000000));
//$GENsessionhash = md5(uniqid(microtime(), true));
           



          //  die($sessionhash);
            $browser = substr($userag, 0, 64);
            $old_s_id = Html::text($_COOKIE[$this->cookieprefix . "sessionhash"]);
            Yii::app()->request->enableCookieValidation = false;
            if ($old_s_id != "")
                $this->FDB->createCommand("DELETE FROM {$this->tbl_prefix}session WHERE sessionhash = '" . $old_s_id . "'")->execute();
            $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}session (sessionhash, userid, host, idhash, lastactivity, location, useragent, loggedin) VALUES ('$sessionhash', '" . $member['userid'] . "', '$ip', '$session_idhash', '" . time() . "', '$scriptpath', '$browser', '1')")->execute();
            setcookie($this->cookieprefix . "userid", $member['userid'], $expire);
            //setcookie($this->cookieprefix . "password", md5($member['password'] . $this->fcookiesalt), $expire);
            setcookie($this->cookieprefix . "password", md5($user_password . $member['salt']), $expire);
            setcookie($this->cookieprefix . "sessionhash", $sessionhash, $expire);
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
    public function register($login, $password, $email) {
        $member = $this->FDB->createCommand("SELECT member_id FROM {$this->tbl_prefix}members WHERE LOWER(name)='" . strtolower($login) . "'  OR email='" . $email . "' OR LOWER(members_display_name)='" . strtolower($login) . "'")->query();
        if (count($member) == 0) {
            $ip = substr($_SERVER['REMOTE_ADDR'], 0, 16);
            $member_login_key = self::generateAutoLoginKey();
            $key_exp = time() + 604800;
            $salt = self::generatePasswordSalt(5);
            $salt = str_replace('\\', "\\\\", $salt);
            $passhash = md5(md5($salt) . md5($password));
            list($lastID) = $this->FDB->createCommand("SELECT member_id FROM {$this->tbl_prefix}members ORDER BY member_id DESC LIMIT 1")->queryColumn();
            $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}members (member_id, name, member_group_id, email, joined, ip_address, language, member_login_key, member_login_key_expire, members_display_name, members_l_display_name, members_l_username,members_pass_hash, members_pass_salt) VALUES (NULL, '$login', '3', '$email', '" . time() . "', '$ip', '1', '$member_login_key', '$key_exp', '$login', '" . strtolower($login) . "', '" . strtolower($login) . "', '" . $passhash . "','" . $salt . "')")->execute();
            $result = $this->FDB->createCommand("SELECT * FROM {$this->tbl_prefix}cache_store WHERE cs_key='stats'")->queryRow();
            $stats = unserialize($result['cs_value']);
            $stats['last_mem_name'] = $login;
            $stats['mem_count'] = $stats['mem_count'] + 1;
            $stats['last_mem_id'] = $lastID;
            $stats = serialize($stats);
            $this->FDB->createCommand("UPDATE {$this->tbl_prefix}cache_store SET cs_value='$stats' WHERE cs_key='stats'")->execute();
        } else {
            return false; //allready
        }
    }

    /**
     * DEMO no compited
     * @param type $user_name
     * @param type $user_pass
     * @return boolean
     */
    public function check_user($login, $user_pass) {

    }

    /**
     * Смена пароля на форуме
     * @param string $login Логин
     * @param string $newpass Новый пароль
     * @param string $email Почта
     * @return boolean
     */
    public function changepassword($login, $newpass, $email) {
        $member = $this->FDB->createCommand("SELECT member_id FROM {$this->tbl_prefix}members WHERE LOWER(name)='" . strtolower($login) . "' AND email='" . $email . "'");
        if (isset($member)) {
            $info = $member->queryRow();
            $salt = self::generatePasswordSalt(5);
            $new_passhash = self::generateCompiledPasshash($salt, md5($newpass));
            $this->FDB->createCommand("UPDATE {$this->tbl_prefix}members SET members_pass_hash='$new_passhash', members_pass_salt='$salt' WHERE member_id='" . $info['member_id'] . "'")->execute();
        } else {
            return false;
        }
    }

    public function do_salt($length = 3) {
        $salt = '';
        for ($i = 0; $i < $length; $i++) {
            $salt .= chr(rand(32, 126));
        }
        return $salt;
    }

}