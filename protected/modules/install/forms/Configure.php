<?php

Yii::import('mod.admin.models.*');
Yii::import('mod.users.models.*');

class Configure extends FormModel {

    public $site_name;
    public $admin_login;
    public $admin_email;
    public $admin_password;

    public function rules() {
        return array(
            array('site_name, admin_login, admin_email, admin_password', 'required'),
            array('admin_email', 'email'),
            array('admin_login', 'length', 'max' => 255),
            array('admin_password', 'length', 'min' => 4, 'max' => 40),
        );
    }

    public function attributeLabels() {
        return array(
            'site_name' => Yii::t('InstallModule.default', 'SITE_NAME'),
            'admin_login' => Yii::t('InstallModule.default', 'ADMIN_LOGIN'),
            'admin_email' => Yii::t('InstallModule.default', 'ADMIN_EMAIL'),
            'admin_password' => Yii::t('InstallModule.default', 'ADMIN_PASSWORD'),
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'showErrorSummary' => false,
            'attributes' => array('id' => __CLASS__),
            'elements' => array(
                '<div class="form-group"><div class="text-center"><h4>' . Yii::t('InstallModule.default', 'ADMIN_ACCOUNT') . '</h4></div></div>',
                'site_name' => array('type' => 'text', 'class' => 'form-control'),
                'admin_login' => array('type' => 'text', 'class' => 'form-control'),
                'admin_password' => array('type' => 'text', 'class' => 'form-control'),
                'admin_email' => array('type' => 'text', 'class' => 'form-control'),
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

    public function install($data) {
        if ($this->hasErrors())
            return false;

        Yii::import('app.addons.DbConnection');
        $config = require(Yii::getPathOfAlias('application.config') . DS . '_db.php');
        $db = $config;
        $conn = new DbConnection($db['connectionString'], $db['username'], $db['password']);
        $conn->charset = $db['charset'];
        $conn->tablePrefix = $db['tablePrefix'];
        Yii::app()->setComponent('db', $conn);


        $model = User::model()->findByPk(1);

        if (!$model)
            $model = new User;

        // Set user data
        $model->login = $this->admin_login;
        $model->email = $this->admin_email;
        $model->password = $this->admin_password;
        $model->date_registration = date('Y-m-d H:i:s');
        $model->last_login = date('Y-m-d H:i:s');
        $model->active = true;
        $model->save(false, false, false);

        $settings = array();
        // Update app settings

        $settings['app'] = array(
            'site_name' => $this->site_name,
            'admin_email' => $this->admin_email,
            'license_key' => $data['license']['license_key'],
            'theme'=>Yii::app()->themeManager->themeNames[0]
        );

        $settings['database'] = array(
                'tables_engine' => $data['db']['db_tables_engine'],

        );


        if (Yii::app()->hasComponent('settings')) {
            foreach (array('SettingsAppForm', 'SettingsDatabaseForm', 'SettingsSecurityForm') as $class) {
                if (method_exists(new $class, 'defaultSettings')) {
                    if (isset($settings[$class::NAME])) {
                        $array = CMap::mergeArray($class::defaultSettings(), $settings[$class::NAME]);
                    } else {
                        $array = $class::defaultSettings();
                    }
                    Yii::app()->settings->set($class::NAME, $array);
                }
            }
        }
        
        
        foreach (Yii::app()->getModules() as $mod => $data) {
            if ($mod != 'install') {
                $module = Yii::app()->getModule($mod);
                if ($module instanceof WebModule) {
                    if (method_exists($module, 'afterInstall')) {
                        $module->afterInstall();
                    }


                    Yii::app()->db->createCommand()->insert('{{modules}}', array(
                        'name' => $mod,
                        'access' => 0,
                    ));
                }
            }
        }
        
        return true;
    }

}
