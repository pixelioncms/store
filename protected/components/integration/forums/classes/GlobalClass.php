<?php

class GlobalClass {
    public $forum_path = '/home/pix1/pixelion.com.ua/forum';
    public $ver;
    public $forum_db;
    public $db_user;
    public $db_password;
    public $db_name = 'localhost';
    public $db_host;
    public $tbl_prefix;

    public function __construct($version) {
        $this->forum_ver = $version;
    }

    public function setDb($dsn) {
        return new CDbConnection($dsn, $this->db_user, $this->db_password);
    }

    public function createUser($user_name, $user_pass, $user_email) {
        $db = Yii::app()->db;
        $user_password = User::encodePassword($user_pass);
        $user_name = Html::text($user_name);
        $user_email = Html::text($user_email);
        $result = $db->createCommand("SELECT * FROM {$db->tablePrefix}user WHERE login='$user_name' OR email='$user_email'");
        if (count($result->queryColumn())==1){
            return false;
        }
        $db->createCommand("INSERT INTO {$db->tablePrefix}user (id, login, email, password, date_registration) VALUES (NULL, '$user_name', '$user_email', '$user_password', now())")->execute();
        Yii::app()->authManager->assign('Authenticated', $result->queryScalar());
        return true;
    }
}

?>
