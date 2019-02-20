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

class PHPBB3 extends GlobalClass {

    public $board_config = array();
    public $user_def_group = 2;

    public function __construct($version) {
        parent::__construct($version);
        include('forum/config.php');
        $this->db_user = $dbuser;
        $this->db_password = $dbpasswd;
        $this->db_host = $dbhost;
        $this->db_name = $dbname;
        $this->tbl_prefix = $table_prefix;
        if ($dbms == 'mysql') {
            $this->forum_db = $this->setDb('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . '');
            $this->forum_db->active = true;
        }

        $result = $this->forum_db->createCommand("SELECT * FROM {$this->tbl_prefix}config");
        foreach ($result->queryAll() as $row) {
            $this->board_config[$row['config_name']] = $row['config_value'];
        }

    }

    /**
     * Выход с форума
     * @return boolean
     */
    public function log_out() {
        $phpbb_key = $_COOKIE[$this->board_config['cookie_name'] . "_k"];
        $phpbb_sid = $_COOKIE[$this->board_config['cookie_name'] . "_sid"];
        $this->forum_db->createCommand("DELETE FROM {$this->tbl_prefix}sessions_keys WHERE key_id = '" . md5($phpbb_key) . "'")->execute();
        $this->forum_db->createCommand("DELETE FROM {$this->tbl_prefix}sessions WHERE session_id = '" . $phpbb_sid . "'")->execute();
        setcookie($this->board_config['cookie_name'] . "_u", "1", "0", "/", $this->board_config['cookie_domain']);
        setcookie($this->board_config['cookie_name'] . "_k", "", "0", "/", $this->board_config['cookie_domain']);
        setcookie($this->board_config['cookie_name'] . "_sid", "", "0", "/", $this->board_config['cookie_domain']);
    }

    /**
     * Авторизация на форуме
     * @param string $login
     * @param string $password
     * @return boolean
     */
    public function log_in($login, $password) {
        Yii::app()->request->enableCookieValidation = false;
        $user_name = strtolower(str_replace('|', '&#124;', $login));
        $result = $this->FDB->createCommand("SELECT user_id, username_clean, user_password, user_passchg, user_email, user_type FROM {$this->tbl_prefix}users WHERE username_clean = '" . $user_name . "'");
        $row = $result->queryRow();
        if ($user_name == $row['username_clean'] && $this->phpbb_check_hash($password, $row['user_password'])) {
            $expire = time() + 60 * 60 * 24 * 365;
            $phpbb_key = $this->unique_id(hexdec(substr(md5($this->unique_id()), 0, 8)));
            $phpbb_sid = md5($phpbb_key);
            $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}sessions_keys (key_id, user_id, last_ip, last_login) VALUES ('" . $phpbb_sid . "','" . $row['user_id'] . "','" . getenv("REMOTE_ADDR") . "','" . time() . "')")->execute();
            setcookie($this->board_config['cookie_name'] . "_u", $row['user_id'], $expire, "/", $this->board_config['cookie_domain']);
            setcookie($this->board_config['cookie_name'] . "_k", $phpbb_key, $expire, "/", $this->board_config['cookie_domain']);
            setcookie($this->board_config['cookie_name'] . "_sid", $phpbb_sid, $expire, "/", $this->board_config['cookie_domain']);
            return true;
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
        $user_name_clean = strtolower(str_replace('|', '&#124;', $login));
        if (strlen($password) != 34) {
            $phpbb_password = $this->phpbb_hash($password);
        } else {
            $phpbb_password = $password;
        }
        $email_hash = crc32(strtolower($user_email)) . strlen($user_email);
        $form_salt = $this->unique_id();
        $user_ip = getenv("REMOTE_ADDR");
        $num_users_new = $this->board_config['num_users'] + 1;
        list($lastID) = $this->FDB->createCommand("SELECT user_id FROM {$this->tbl_prefix}users ORDER BY user_id DESC LIMIT 1")->queryColumn();
        $reg_id = $lastID + 1;
        $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}users (user_id, group_id, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_lang, user_dateformat, user_form_salt) VALUES ('" . $reg_id . "', '" . $this->user_def_group . "', '" . $user_ip . "', '" . time() . "', '" . $login . "', '" . $user_name_clean . "', '" . $phpbb_password . "', '" . time() . "', '" . $user_email . "', '" . $email_hash . "', '" . $this->board_config['default_lang'] . "', '" . $this->board_config['default_dateformat'] . "', '" . $form_salt . "')")->execute();
        $this->FDB->createCommand("INSERT INTO {$this->tbl_prefix}user_group (group_id, user_id, group_leader, user_pending) VALUES ('2', '" . $reg_id . "', '0', '0')")->execute();
        $this->FDB->createCommand("UPDATE {$this->tbl_prefix}config SET config_value = '" . $reg_id . "' WHERE config_name = 'newest_user_id'")->execute();
        $this->FDB->createCommand("UPDATE {$this->tbl_prefix}config SET config_value = '" . $login . "' WHERE config_name = 'newest_username'")->execute();
        $this->FDB->createCommand("UPDATE {$this->tbl_prefix}config SET config_value = '" . $num_users_new . "' WHERE config_name = 'num_users'")->execute();
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

    }

    public function _hash_crypt_private($password, $setting, &$itoa64) {
        $output = '*';

        // Check for correct hash
        if (substr($setting, 0, 3) != '$H$' && substr($setting, 0, 3) != '$P$') {
            return $output;
        }

        $count_log2 = strpos($itoa64, $setting[3]);

        if ($count_log2 < 7 || $count_log2 > 30) {
            return $output;
        }

        $count = 1 << $count_log2;
        $salt = substr($setting, 4, 8);

        if (strlen($salt) != 8) {
            return $output;
        }

        /**
         * We're kind of forced to use MD5 here since it's the only
         * cryptographic primitive available in all versions of PHP
         * currently in use.  To implement our own low-level crypto
         * in PHP would result in much worse performance and
         * consequently in lower iteration counts and hashes that are
         * quicker to crack (by non-PHP code).
         */
        if (PHP_VERSION >= 5) {
            $hash = md5($salt . $password, true);
            do {
                $hash = md5($hash . $password, true);
            } while (--$count);
        } else {
            $hash = pack('H*', md5($salt . $password));
            do {
                $hash = pack('H*', md5($hash . $password));
            } while (--$count);
        }

        $output = substr($setting, 0, 12);
        $output .= $this->_hash_encode64($hash, 16, $itoa64);

        return $output;
    }

    /**
     * Generate salt for hash generation
     */
    public function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6) {
        if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31) {
            $iteration_count_log2 = 8;
        }

        $output = '$H$';
        $output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
        $output .= $this->_hash_encode64($input, 6, $itoa64);

        return $output;
    }

    /**
     *
     * @version Version 0.1 / slightly modified for phpBB 3.0.x (using $H$ as hash type identifier)
     *
     * Portable PHP password hashing framework.
     *
     * Written by Solar Designer <solar at openwall.com> in 2004-2006 and placed in
     * the public domain.
     *
     * There's absolutely no warranty.
     *
     * The homepage URL for this framework is:
     *
     * 	http://www.openwall.com/phpass/
     *
     * Please be sure to update the Version line if you edit this file in any way.
     * It is suggested that you leave the main version number intact, but indicate
     * your project name (after the slash) and add your own revision information.
     *
     * Please do not change the "private" password hashing method implemented in
     * here, thereby making your hashes incompatible.  However, if you must, please
     * change the hash type identifier (the "$P$") to something different.
     *
     * Obviously, since this code is in the public domain, the above are not
     * requirements (there can be none), but merely suggestions.
     *
     *
     * Hash the password
     */
    public function phpbb_hash($password) {
        $itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $random_state = $this->unique_id();
        $random = '';
        $count = 6;

        if (($fh = @fopen('/dev/urandom', 'rb'))) {
            $random = fread($fh, $count);
            fclose($fh);
        }

        if (strlen($random) < $count) {
            $random = '';

            for ($i = 0; $i < $count; $i += 16) {
                $random_state = md5($this->unique_id() . $random_state);
                $random .= pack('H*', md5($random_state));
            }
            $random = substr($random, 0, $count);
        }

        $hash = $this->_hash_crypt_private($password, $this->_hash_gensalt_private($random, $itoa64), $itoa64);

        if (strlen($hash) == 34) {
            return $hash;
        }

        return md5($password);
    }

    /**
     * Check for correct password
     *
     * @param string $password The password in plain text
     * @param string $hash The stored password hash
     *
     * @return bool Returns true if the password is correct, false if not.
     */
    public function phpbb_check_hash($password, $hash) {
        if (strlen($password) > 4096) {
            // If the password is too huge, we will simply reject it
            // and not let the server try to hash it.
            return false;
        }

        $itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        if (strlen($hash) == 34) {
            return ($this->_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
        }

        return (md5($password) === $hash) ? true : false;
    }

    /**
     * Generates avatar filename from the database entry
     */
    public function get_avatar_filename($avatar_entry) {
        global $config;


        if ($avatar_entry[0] === 'g') {
            $avatar_group = true;
            $avatar_entry = substr($avatar_entry, 1);
        } else {
            $avatar_group = false;
        }
        $ext = substr(strrchr($avatar_entry, '.'), 1);
        $avatar_entry = intval($avatar_entry);
        return $config['avatar_salt'] . '_' . (($avatar_group) ? 'g' : '') . $avatar_entry . '.' . $ext;
    }

    /**
     * Encode hash
     */
    public function _hash_encode64($input, $count, &$itoa64) {
        $output = '';
        $i = 0;

        do {
            $value = ord($input[$i++]);
            $output .= $itoa64[$value & 0x3f];

            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }

            $output .= $itoa64[($value >> 6) & 0x3f];

            if ($i++ >= $count) {
                break;
            }

            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }

            $output .= $itoa64[($value >> 12) & 0x3f];

            if ($i++ >= $count) {
                break;
            }

            $output .= $itoa64[($value >> 18) & 0x3f];
        } while ($i < $count);

        return $output;
    }

    /**
     * Return unique id
     * @param string $extra additional entropy
     */
    public function unique_id($extra = 'c') {
        static $dss_seeded = false;
        global $config;

        $val = $config['rand_seed'] . microtime();
        $val = md5($val);
        $config['rand_seed'] = md5($config['rand_seed'] . $val . $extra);

        /* if ($dss_seeded !== true && ($config['rand_seed_last_update'] < time() - rand(1, 10))) {
          set_config('rand_seed_last_update', time(), true);
          set_config('rand_seed', $config['rand_seed'], true);
          $dss_seeded = true;
          } */

        return substr($val, 4, 16);
    }

}