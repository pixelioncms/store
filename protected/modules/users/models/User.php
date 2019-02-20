<?php

/**
 * This is the model class for table "user".
 * The followings are the available columns in table 'user':
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @uses ActiveRecord
 * @package modules.users.models
 * @property integer $id
 * @property string $username Имя пользовотеля
 * @property string $login Логин
 * @property string $password sha1(Пароль)
 * @property string $email Почта
 * @property integer $date_registration Дата регистрации
 * @property integer $last_login
 * @property string $login_ip IP-адрес входа пользователя
 * @property string $recovery_key Password recovery key
 * @property string $recovery_password
 * @property boolean $banned
 */
class User extends ActiveRecord
{

    const MODULE_ID = 'users';
    const route = '/users/admin/default';

    public $disallow_delete = array(1);
    public $new_password;
    public $confirm_password;
    public $verifyCode;
    public $duration;
    public $role_id;

    public function getGridColumns()
    {
        $columns = array();

        $columns['avatar'] = array(
            'name' => 'avatar',
            'type' => 'raw',
            'filter' => false,
            'value' => 'Html::image($data->getAvatarUrl("50x50"), $data->username, array("class"=>"img-thumbnail img-avatar"))',
            'htmlOptions' => array('class' => 'text-center', 'style' => 'width:80px;')
        );
        $columns['username'] = array(
            'type' => 'raw',
            'name' => 'username',
            'value' => '$data->username',
        );
        $columns['login'] = array(
            'header' => Yii::t('app', 'LOGIN'),
            'name' => 'login',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'Html::link(Html::encode($data->login),array("update","id"=>$data->id))',
        );
        $columns['email'] = array(
            'type' => 'raw',
            'name' => 'email',
            'value' => '$data->emailLink',
            'htmlOptions' => array('class' => 'text-center'),
        );
        $columns['phone'] = array(
            'type' => 'raw',
            'name' => 'phone',
            'value' => '$data->phone',
            'htmlOptions' => array('class' => 'text-center'),
        );
        $columns['address'] = array(
            'type' => 'raw',
            'name' => 'address',
            'value' => '$data->address',
            'htmlOptions' => array('class' => 'text-center'),
        );
        $columns['subscribe'] = array(
            'type' => 'html',
            'name' => 'subscribe',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
            'value' => '$data->subscribe ? "<span class=\"badge badge-success\">".Yii::t("app", "YES")."</span>" : "<span class=\"badge badge-danger\">".Yii::t("app", "NO")."</span>"'
        );
        $columns['timezone'] = array(
            'type' => 'raw',
            'name' => 'timezone',
            'value' => '$data->timezone',
            'htmlOptions' => array('class' => 'text-center'),
        );
        $columns['role_id'] = array(
            'name' => 'role_id',
            'type' => 'raw',
            'header' => Yii::t('UsersModule.User', 'ROLE_ID'),
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->getRolesGrid()'
        );
        $columns['gender'] = array(
            'type' => 'raw',
            'name' => 'gender',
            'filter' => self::getSelectGender(),
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'CMS::gender("$data->gender")'
        );
        $columns['last_login'] = array(
            'type' => 'raw',
            'name' => 'last_login',
            'value' => 'CMS::date("$data->last_login")',
            'htmlOptions' => array('class' => 'text-center'),
        );
        $columns['login_ip'] = array(
            'type' => 'raw',
            'name' => 'login_ip',
            'value' => 'CMS::ip("$data->login_ip", 1)',
            'htmlOptions' => array('class' => 'text-center'),
        );

        $buttons = array();
        if (Yii::app()->hasModule('cart')) {
            $buttons['orders'] = array(
                'icon' => 'icon-cart',
                'label' => 'Заказы',
                'url' => 'Yii::app()->createUrl("/admin/cart/default/index", array("user_id"=>$data->id))',
                'visible' => (Yii::app()->hasModule('cart')) ? 'true' : 'false'
            );
        }


        $columns['DEFAULT_CONTROL'] = array(
            'class' => 'ButtonColumn',
            'template' => '{orders}{update}{delete}',
            'buttons' => $buttons
        );
        $columns['DEFAULT_COLUMNS'] = array(
            array(
                'header' => Yii::t('default', 'ONLINE'),
                'class' => 'IdColumn',
                'type' => 'raw',
                'value' => '$data->isUserOnline()',
                'htmlOptions' => array('class' => 'text-center')
            ),
        );

        return $columns;
    }

    public function getRolesGrid()
    {
        return '<span class="badge badge-secondary" style="margin-left: 5px">' . implode("</span><span class=\"badge badge-secondary\" style=\"margin-left: 5px\">", $this->role()) . '</span>';

    }

    // class User
    public function getDisplayName()
    {
        return ($this->isService) ? $this->username : $this->login;
    }

    public function getDisplayUrl()
    {
        return ($this->isService) ? $this->username : $this->login;
    }

    public function getFullName()
    {
        return $this->login;
    }

    public function getSuggest($q)
    {
        $c = new CDbCriteria();
        $c->addSearchCondition('login', $q, true, 'OR');
        $c->addSearchCondition('email', $q, true, 'OR');
        return $this->findAll($c);
    }

    public function init()
    {

        //@todo error with install module
        if (isset(Yii::app()->user->isSuperuser) && !Yii::app()->user->isSuperuser) {
            $this->disallow_update = array(1);
        }

        /**
         * проблема для установки.
         */
        /*  $this->_attrLabels['confirm_password'] = self::t('CONFIRM_PASSWORD');

         */
        $this->_attrLabels['confirm_password'] = self::t('CONFIRM_PASSWORD');
        //$this->_attrLabels['verifyCode'] = self::t('VERIFY_CODE');
        $this->_attrLabels['new_password'] = self::t('NEW_PASSWORD');
        $this->_attrLabels['role_id'] = self::t('ROLE_ID');


        if ($this->scenario == 'insert') {
            $this->timezone = CMS::timezone();
            $this->_attrLabels['new_password'] = self::t('PASSWORD');
        }
        return parent::init();
    }

    public function getForm()
    {
        Yii::import('ext.bootstrap.fileinput.FileInput');
        Yii::import('app.jui.JuiDatePicker');
        Yii::import('app.widgets.intl-tel-input.TelInputWidget');
        $tab = new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'per_page' => array(
                    'type' => 'text',
                    'hint' => self::t('HINT_PER_PAGE'),
                ),
                'login' => array(
                    'type' => 'text',
                    'disabled' => $this->isService,
                    'afterContent' => '<i class="icon-user"></i>',
                ),
                'username' => array(
                    'type' => 'text',
                    'afterContent' => '<i class="icon-user"></i>'
                ),
                'email' => array(
                    'type' => 'text',
                    'afterContent' => '@'
                ),
                'address' => array('type' => 'text', 'afterContent' => '<i class="icon-location"></i>'),
                'phone' => array(
                    'type' => 'TelInputWidget',
                ),
                'subscribe' => array('type' => 'checkbox', 'visible' => Yii::app()->getModule('delivery') ? true : false),
                'message' => array('type' => 'checkbox'),
                'date_birthday' => array(
                    'type' => 'JuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                        'maxDate' => -365 * 10,
                        'changeMonth' => true,
                        'changeYear' => true
                    )
                ),
                'timezone' => array(
                    'type' => 'dropdownlist',
                    'items' => TimeZoneHelper::getTimeZoneData(),
                    'empty' => Yii::t('app', 'DEFAULT')
                ),
                'role_id' => array(
                    'type' => 'dropdownlist',
                    'items' => Rights::getAuthItemSelectOptions(2),
                    'empty' => Yii::t('app', 'DEFAULT')
                ),
                'language' => array(
                    'type' => 'dropdownlist',
                    'items' => Yii::app()->languageManager->getLangsByArray(),
                    'empty' => Yii::t('app', 'DEFAULT')
                ),
                'gender' => array(
                    'type' => 'dropdownlist',
                    'items' => self::getSelectGender(),
                    'disabled' => $this->isService
                ),
                'avatar' => array(
                    'type' => 'FileInput',
                    'visible' => ($this->isService) ? false : true,
                    'options' => array(
                        'showUpload' => false,
                        'showPreview' => true,
                        'overwriteInitial' => true,
                        'maxFileSize' => 1500,
                        'showClose' => false,
                        'showCaption' => false,
                        'browseLabel' => '',
                        'removeLabel' => '',
                        'browseIcon' => '<i class="icon-folder-open"></i>',
                        'removeIcon' => '<i class="icon-delete"></i>',
                        'elErrorContainer' => '#kv-avatar-errors',
                        'msgErrorClass' => 'alert alert-danger',
                        'defaultPreviewContent' => '<img src="' . $this->getAvatarUrl(Yii::app()->settings->get('users', 'avatar_size')) . '" alt="Your Avatar">',
                        'layoutTemplates' => "{main2: '{preview}  {remove} {browse}'}",
                        'allowedFileExtensions' => array("jpg", "png", "gif"),
                        'initialPreviewConfig' => array(
                            'width' => '120px',
                        ),
                        'previewSettings' => array(
                            'image' => array('width' => "auto", 'height' => "auto"),
                        )
                    ),
                    'afterContent' => '<div id="kv-avatar-errors" style="display:none"></div>'
                ),
                // 'login_ip' => array('type' => 'text', 'disabled' => $this->isService),
                'new_password' => array(
                    'type' => 'password',
                    'disabled' => $this->isService,
                    'afterContent' => '<i class="icon-key"></i>'
                ),
                'banned' => array('type' => 'checkbox'),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                ),
            )
        ), $this);
        return $tab;
    }

    public static function getRoles($user_id)
    {
        foreach (Rights::getAssignedRoles($user_id) as $role) {
            echo '<span class="badge badge-secondary">' . $role->name . '</span><br/>';
        }
    }


    public function role()
    {
        $a = array();
        foreach (Rights::getAssignedRoles($this->id) as $role) {
            $a[] = $role->description;
            // echo '<span class="badge badge-secondary">' . $role->description . '</span><br/>';
        }
        return $a;
    }

    public function getRolesList()
    {
        $result = array();
        foreach (Rights::getAssignedRoles($this->id) as $role) {
            $result[] = $role;
        }
        return $result;
    }

    public function getIsService()
    {
        return (isset($this->service) && !empty($this->service)) ? true : false;
    }

    public function getThemes()
    {
        $themesNames = Yii::app()->themeManager->themeNames;
        return array_combine($themesNames, $themesNames);
    }

    public function getEmailLink()
    {

        Yii::app()->clientScript->registerScript('sendEmail', '
       function sendEmailer(mail){

          if($("#sendEmailer").length == 0)
    {
        var div =  $("<div id=\"sendEmailer\"/ class=\"fluid\">");
        $(div).attr("title", "Оптавить письмо:");
        $("body").append(div);
    }

    var dialog = $("#sendEmailer");
    dialog.html("Загрузка формы...");
    dialog.load("/admin/app/ajax/sendMailForm?mail="+mail+"");

    dialog.dialog({
        modal: true,
        width: "50%",
        buttons: {
            "Отправить": function() {
                $.ajax("/admin/app/ajax/sendMailForm", {
                    type:"post",
                    data: {
                        token: $(link_clicked).attr("data-token"),
                        data: $("#sendEmailer form").serialize()
                    },
                    success: function(data){
                        $(dialog).dialog("close");
                        dialog.html("Письмо отправлено!");
                        
                    },
                    error: function(){
                        $.jGrowl("Ошибка", {
                            position:"bottom-right"
                        });
                    }
                });
            },
            "Отмена": function() {
                $( this ).dialog( "close" );
            }
        }
    });
}
        ', CClientScript::POS_HEAD);


        if (!empty($this->email)) {
            $em = CHtml::link($this->email, Yii::app()->createAbsoluteUrl('admin/delivery', array('send' => $this->email)), array('onClick' => 'sendEmailer("' . $this->email . '"); return false;'));
        } else {
            $em = $this->service;
        }
        return $em;
    }

    public function isUserOnline()
    {
        // $session = Session::model()->find(array('condition' => '`t`.`user_login`=:login', 'params' => array(':login' => $this->login)));
        $session = Session::model()->find(array('condition' => '`t`.`user_name`=:login', 'params' => array(':login' => $this->login)));
        if (isset($session)) {
            if (Yii::app()->controller instanceof AdminController) {
                return '<span class="badge badge-success" title="' . CMS::date($this->last_login) . '">' . Yii::t('default', 'USER_ONLINE', 1) . '</span>';
            } else {
                return true;
            }
        } else {
            if (Yii::app()->controller instanceof AdminController) {
                return '<span class="badge badge-secondary" title="' . CMS::date($this->last_login) . '">' . Yii::t('default', 'USER_ONLINE', 0) . '</span>';
            } else {
                return false;
            }
        }
    }

    public function scopes()
    {
        return CMap::mergeArray(array(
            'subscribe' => array(
                'condition' => '`t`.`subscribe`=:subs',
                'params' => array(':subs' => 1)
            ),
            'lastRecord' => array(
                'order' => 'id DESC',
                'limit' => 1,
            ),
        ), parent::scopes());
    }

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $config = Yii::app()->settings->get('users');
        $rules = array();
        $validLogin = (Yii::app()->settings->get('app', 'forum') != null) ? 'checkBadLogin' : 'checkBadEmail';
        if ($validLogin == 'checkBadLogin') {
            $rules[] = array('login', 'required', 'on' => 'register');
        } else {
            $rules[] = array('login', 'email', 'on' => 'register');
        }

        if (isset($config->enable_register_captcha) && $config->enable_register_captcha) {
            $rules[] = array('verifyCode', 'required', 'on' => 'register');
            $rules[] = array('verifyCode', 'captcha', 'on' => 'register', 'allowEmpty' => YII_DEBUG);
        }

        if (Yii::app()->hasModule('message')) {
            $rules[] = array('message', 'boolean');
        }
        if (Yii::app()->hasModule('delivery')) {
            $rules[] = array('subscribe', 'boolean');
        }

        return CMap::mergeArray(array(
            //new fields
            //Регистрация
            array('username', 'checkBadName', 'on' => 'register'),
            array('login', $validLogin, 'on' => 'register'),
            array('password, confirm_password', 'required', 'on' => 'register'),
            array('password, confirm_password', 'checkPasswords', 'on' => 'register'),
            //array('login', 'email', 'on' => 'register'),
            array('login', 'required'),
            array('login', 'checkIfAvailable'),
            array('banned, edit_mode', 'boolean'),
            array('avatar', 'file',
                'types' => $config->upload_types,
                'allowEmpty' => true,
                'maxSize' => $config->upload_size,
                'wrongType' => Yii::t('app', 'WRONG_TYPES', array('{types}' => $config->upload_types)),
                'on' => 'update'
            ),
            array('email', 'email'),
            array('date_registration', 'required', 'on' => 'update'),
            array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
            array('username, password, email, theme, avatar, login_ip, service, phone, address, timezone, role_id', 'length', 'max' => 255),
            array('new_password', 'length', 'min' => $config->min_password),
            array('password', 'length', 'min' => $config->min_password),
            array('gender, language, subscribe', 'numerical', 'integerOnly' => true),
            array('id, username, email, date_registration, last_login, banned, avatar, language, address, phone', 'safe', 'on' => 'search'),
        ), $rules);
    }

    public function checkPasswords()
    {
        if ($this->password != $this->confirm_password) {
            $this->addError('password', 'Пароли не совподают');
            $this->addError('confirm_password', '');
        }
    }

    /**
     * Check if username/email is available
     */
    public function checkIfAvailable($attr)
    {
        $labels = $this->attributeLabels();
        $check = User::model()->countByAttributes(array(
            $attr => $this->$attr,
        ), 't.id != :id', array(':id' => (int)$this->id));

        if ($check > 0)
            $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_ALREADY_USED', array('{attr}' => $labels[$attr])));
    }

    public function checkBadLogin($attr)
    {

    }

    public function checkBadName($attr)
    {
        $labels = $this->attributeLabels();
        $names_array = explode(',', Yii::app()->settings->get('users', 'bad_name'));
        if (in_array($this->username, $names_array))
            $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_BAD_NAMES', array('{attr}' => $labels[$attr], '{name}' => $this->username)));
    }

    public function checkBadEmail($attr)
    {
        $config = Yii::app()->settings->get('users', 'bad_email');
        if (!empty($config)) {
            $mails = explode(',', $config);

            foreach ($mails as $mail) {
                if (preg_match('#' . $mail . '$#iu', $this->email))
                    $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_BAD_EMAILS', array('{email}' => $mail)));
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
            //'ordersCount' => array(self::STAT, 'Order', 'user_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
            'commentsCount' => array(self::STAT, 'Comment', 'user_id'),
            'session' => array(self::STAT, 'Session', 'user_id'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_registration', $this->date_registration, true);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('last_login', $this->last_login);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     *  Encodes user password
     *
     * @param string $string
     * @return string
     */
    public static function encodePassword($string)
    {
        return sha1($string);
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        // Set new password

        if ($this->isNewRecord) {

            if (!$this->date_registration)
                $this->date_registration = date('Y-m-d H:i:s');

            $this->login_ip = Yii::app()->request->userHostAddress;

            if (!$this->hasErrors())
                $this->password = self::encodePassword($this->password);
        }
        if ($this->new_password) {
            $this->password = self::encodePassword($this->new_password);
        }
        if (empty($this->language)) {
            $this->language = new CDbExpression('NULL');
        }
        if (empty($this->theme)) {
            $this->theme = new CDbExpression('NULL');
        }
        if (empty($this->timezone)) {
            $this->timezone = new CDbExpression('NULL');
        }
        // $this->uploadFile('avatar', '/uploads/users/avatar/');
        return parent::beforeSave();
    }

    public function getAdminEditUrl()
    {
        return Yii::app()->createUrl('/admin/users/default/update', array('id' => $this->id));
    }

    /**
     * Activate new user password
     * @static
     * @param $key
     * @return bool
     */
    public static function activeNewPassword($key)
    {
        $user = User::model()->findByAttributes(array('recovery_key' => $key));

        if (!$user)
            return false;

        $user->password = self::encodePassword($user->recovery_password);
        $user->recovery_key = '';
        $user->recovery_password = '';
        $user->save(false, false, false);
        return true;
    }

    /**
     * @return int
     */
    public function getOrdersTotalPrice()
    {
        $result = 0;

        foreach ($this->orders as $order)
            $result += $order->full_price;

        return $result;
    }

    public function getProfileUrl()
    {
        return Yii::app()->createUrl('/users/profile/view', array('user_id' => $this->id));
    }

    //Пол
    public static function getSelectGender()
    {
        return array(
            0 => Yii::t('app', 'GENDER', 0),
            1 => Yii::t('app', 'GENDER', 1),
            2 => Yii::t('app', 'GENDER', 2)
        );
    }

    public static function getUserPanel($username, $id)
    {
        $txt = '<ul class="navi nav-pills">';
        $txt .= '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">' . $username . '<b class="caret"></b></a>';
        $txt .= '<ul class="dropdown-menu">';
        $txt .= '<li><a href="/admin/users/default/update?id=' . $id . '"><span class="iconb icon-wrench"></span>' . Yii::t('app', 'UPDATE', 1) . '</a></li>';
        $txt .= '<li><a href="/admin/users/default/update?id=' . $id . '"><span class="iconb icon-wrench"></span>' . Yii::t('app', 'UPDATE', 1) . '</a></li>';
        $txt .= '</ul>';
        $txt .= '</li>';
        $txt .= '</ul>';

        return $txt;
    }

    public function getAvatarUrl($size = false)
    {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = $this->avatar;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {
            if (empty($ava)) {
                $returnUrl = CMS::processImage($size, 'user.png', 'users.avatars', 'user_avatar');
            } else {
                $returnUrl = CMS::processImage($size, $ava, 'users.avatar', 'user_avatar');
            }
            return $returnUrl;
        } else {
            return $ava;
        }
    }

}
