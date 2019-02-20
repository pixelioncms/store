<?php

Yii::import('mod.admin.models.*');

class Db extends FormModel {

    public $db_host = 'localhost';
    public $db_name;
    public $db_user;
    public $db_password;
    public $db_prefix = 'cms_';
    public $db_charset = 'utf8';
    public $db_type = 'mysql';
    public $db_tables_engine;

    public function rules() {
        return array(
            array('db_host, db_name, db_user, db_prefix, db_charset, db_type, db_tables_engine', 'required'),
            array('db_password', 'checkDbConnection'),
        );
    }

    public function attributeLabels() {
        return array(
            'db_host' => Yii::t('InstallModule.default', 'DB_HOST'),
            'db_name' => Yii::t('InstallModule.default', 'DB_NAME'),
            'db_user' => Yii::t('InstallModule.default', 'DB_USER'),
            'db_prefix' => Yii::t('InstallModule.default', 'DB_PREFIX'),
            'db_password' => Yii::t('InstallModule.default', 'DB_PASSWORD'),
            'db_charset' => Yii::t('InstallModule.default', 'DB_CHARSET'),
            'db_type' => Yii::t('InstallModule.default', 'DB_TYPE'),
            'db_tables_engine' => Yii::t('InstallModule.default', 'DB_TABLES_ENGINE'),
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'showErrorSummary' => true,
            'attributes' => array('id' => __CLASS__),
            'elements' => array(
                'db_host' => array('type' => 'text', 'class' => 'form-control'),
                'db_name' => array('type' => 'text', 'class' => 'form-control'),
                'db_user' => array('type' => 'text', 'class' => 'form-control'),
                'db_password' => array('type' => 'text', 'class' => 'form-control'),
                'db_prefix' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'hint' => '<a href="javascript:void(0)" onClick="$(\'#Db_db_prefix\').val(makeid());">' . Yii::t('InstallModule.default', 'AUTO_GEN') . '</a>'
                ),
                'db_charset' => array(
                    'type' => 'dropdownlist',
                    'items' => array('utf8' => 'UTF-8', 'cp1251' => 'cp1251', 'latin1' => 'latin1'),
                    'class' => 'form-control',
                    'hint' => Yii::t('InstallModule.default', 'DB_CHARSET_HINT')
                ),
                'db_type' => array(
                    'type' => 'dropdownlist',
                    'items' => array("mysql" => 'MySQL/MariaDB', "sqlite" => 'SQLite', "pgsql" => 'PostgreSQL', "mssql" => 'SQL Server', "oci" => 'Oracle'),
                    'class' => 'form-control',
                    'hint' => Yii::t('InstallModule.default', 'DB_TYPE_HINT')
                ),
                'db_tables_engine' => array(
                    'type' => 'dropdownlist',
                    'items' => array("MyISAM" => 'MyISAM', "InnoDB" => 'InnoDB'),
                    'class' => 'form-control',
                ),
            ),
            'buttons' => array(
                'previous' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-default',
                    'label' => Yii::t('InstallModule.default', 'BACK')
                ),
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('InstallModule.default', 'NEXT')
                )
            )
                ), $this);
    }

    public function install() {
        if ($this->hasErrors())
            return false;

        //Yii::app()->cache->flush();
        Yii::import('app.addons.DbConnection');
        $conn = new DbConnection($this->getDsn(), $this->db_user, $this->db_password);

        $conn->charset = $this->db_charset;
        $conn->tablePrefix = $this->db_prefix;
        Yii::app()->setComponent('db', $conn);
        Yii::app()->db->importSqlFile(Yii::getPathOfAlias('mod.install.data') . DS . 'dump.sql', $this->db_tables_engine);

        $this->writeConnectionSettings();
        // Activate languages
        //Yii::app()->languageManager->setActive();
    }



    public function getDsn() {
        if ($this->db_type == 'pgsql') {
            return strtr('pgsql:host={host};port=5432;dbname={db_name}', array(
                '{host}' => $this->db_host,
                '{db_name}' => $this->db_name,
            ));
        } elseif ($this->db_type == 'oci') {
            return strtr('oci:dbname=//{host}/{db_name}', array(
                '{host}' => $this->db_host,
                '{db_name}' => $this->db_name,
            ));
        } elseif ($this->db_type == 'sqlite') {
            return strtr('sqlite:dbname={host}/{db_name}', array(
                '{host}' => $this->db_host,
                '{db_name}' => $this->db_name,
            ));
        } else {
            return strtr('{db_type}:host={host};dbname={db_name}', array(
                '{host}' => $this->db_host,
                '{db_name}' => $this->db_name,
                '{db_type}' => $this->db_type,
            ));
        }
    }

    public function checkDbConnection() {
        if (!$this->hasErrors()) {
            $connection = new CDbConnection($this->getDsn(), $this->db_user, $this->db_password);
            try {
                $connection->connectionStatus;
            } catch (CDbException $e) {
                $this->addError('db_password', Yii::t('InstallModule.default', 'ERROR_CONNECT_DB'));
            }
        }
    }

    private function writeConnectionSettings() {
        $configFiles[] = Yii::getPathOfAlias('application.config') . DS . '_db.php';
        $configFiles[] = Yii::getPathOfAlias('application.config') . DS . '_db_dev.php';
        foreach ($configFiles as $file){
            $content = file_get_contents($file);
            $content = preg_replace("/\'connectionString\'\s*\=\>\s*\'.*\'/", "'connectionString'=>'{$this->getDsn()}'", $content);
            $content = preg_replace("/\'username\'\s*\=\>\s*\'.*\'/", "'username'=>'{$this->db_user}'", $content);
            $content = preg_replace("/\'password\'\s*\=\>\s*\'.*\'/", "'password'=>'{$this->db_password}'", $content);
            $content = preg_replace("/\'tablePrefix\'\s*\=\>\s*\'.*\'/", "'tablePrefix'=>'{$this->db_prefix}'", $content);
            $content = preg_replace("/\'charset\'\s*\=\>\s*\'.*\'/", "'charset'=>'{$this->db_charset}'", $content);
            file_put_contents($file, $content);
        }


    }
}
