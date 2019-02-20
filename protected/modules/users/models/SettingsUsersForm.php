<?php

class SettingsUsersForm extends FormSettingsModel
{

    const NAME = 'users';

    public $upload_avatar;
    public $upload_types;
    public $upload_size;
    public $registration;
    public $register_nomail;
    public $change_theme;
    public $min_password;
    public $bad_name;
    public $bad_email;
    public $favorites;
    public $favorite_limit;
    public $social_auth;
    public $enable_register_captcha;

    /**
     * Social settings
     */
    public $vkontakte,
        $facebook,
        $odnoklassniki,
        $twitter,
        $yandex_oauth,
        $github,
        $dropbox,
        $linkedin,
        $google_oauth,
        $mailru;


    public $vkontakte_client_id,
        $facebook_client_id,
        $odnoklassniki_client_id,
        $twitter_client_id,
        $github_client_id,
        $dropbox_client_id,
        $linkedin_client_id,
        $google_oauth_client_id,
        $mailru_client_id,
        $yandex_oauth_client_id;

    public $vkontakte_client_secret,
        $facebook_client_secret,
        $odnoklassniki_client_secret,
        $twitter_client_secret,
        $github_client_secret,
        $dropbox_client_secret,
        $linkedin_client_secret,
        $google_oauth_client_secret,
        $mailru_client_secret,
        $yandex_oauth_client_secret;

    /* remind mail template */
    public $remind_mail_tpl;


    /**
     * Default settings
     * @return array
     */
    public static function defaultSettings()
    {
        return array(
            'favorites' => true,
            'upload_avatar' => true,
            'upload_types' => 'jpg,gif,jpeg,png',
            'upload_size' => 51200,
            'registration' => true,
            'register_nomail' => false,
            'min_password' => 3,
            'change_theme' => false,
            'social_auth' => false,
            'bad_name' => 'root,admin,god,administrator,anonymous,guest',
            'bad_email' => 'mailspam.com,mailspamer.com',
            'favorite_limit' => 10,
            'enable_register_captcha' => false,
            'remind_mail_tpl' => '<p>Здравствуйте, {username}!<br /><br />Вы получили это письмо потому, что вы (либо кто-то, выдающий себя за вас)<br />попросили выслать новый пароль для вашей учётной записи.<br />Если вы не просили выслать пароль, то не обращайте внимания на<br />это письмо, если же подобные письма будут продолжать приходить, обратитесь<br />к администратору сайта.<br /><br />Перейдите по ссылке для активации нового пароля {active_url}<br /><br /><br />Ваш новый пароль: {recovery_password}</p>'
        );
    }


    public function getForm()
    {
        Yii::import('ext.tageditor.TagEditor');
        Yii::import('ext.tinymce.TinymceArea');
        $tab = new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'main' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'upload_avatar' => array('type' => 'checkbox'),
                        'upload_types' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => Yii::t('app', 'CREATE', 0)
                            )
                        ),
                        'upload_size' => array(
                            'type' => 'text',
                            'hint' => '1Мб = 1048576 байт.'
                        ),
                        'min_password' => array('type' => 'text'),
                        'change_theme' => array('type' => 'checkbox'),
                        'favorites' => array('type' => 'checkbox'),
                        'favorite_limit' => array('type' => 'text'),
                    )
                ),
                'register' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_REGISTER'),
                    'elements' => array(
                        'registration' => array('type' => 'checkbox'),
                        'register_nomail' => array('type' => 'checkbox'),
                        'enable_register_captcha' => array('type' => 'checkbox'),
                        'bad_name' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => Yii::t('app', 'CREATE', 0)
                            )
                        ),
                        'bad_email' => array(
                            'type' => 'TagEditor',
                            'options' => array(
                                'defaultText' => Yii::t('app', 'CREATE', 0)
                            )
                        ),
                    )
                ),
                'social' => array(
                    'visible' => (Yii::app()->hasComponent('eauth')) ? true : false,
                    'type' => 'form',
                    'title' => self::t('TAB_SOCIAL'),
                    'elements' => $this->getSocialArrayForm()
                ),
                'remind' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_REMIND'),
                    'elements' => array(
                        'remind_mail_tpl' => array(
                            'type' => 'TinymceArea',
                            'hint' => Html::link('Документация', 'javascript:void(0)', array('onclick' => '$("#docs").dialog("open"); return false;'))
                        ),
                    )
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        ), $this);
        return $tab;
    }

    private function getSocialApp($app)
    {
        return self::t('Registration application: {app_link}', array('{app_link}' => CHtml::link($app::APP_URL, $app::APP_URL, array('target' => '_blank'))));
    }

    private function eauthRules()
    {
        $r = array();
        if (Yii::app()->hasComponent('eauth')) {
            foreach ($this->getSocialArray() as $key => $val) {
                $name = $this->getServiceName($key);
                $r[] = array($name, 'boolean');
                // $r[] = array($name . '_client_id', 'length', 'max' => 255);
                // $r[] = array($name . '_client_secret', 'length', 'max' => 255);
                //$r[] = array($name . '_client_secret', 'safe');
                //$r[] = array($name . '_client_id', 'safe');
            }
        }
        return $r;
    }

    private function getSocialArrayForm()
    {
        $r = array();
        if (Yii::app()->hasComponent('eauth')) {
            $r['social_auth'] = array('type' => 'checkbox');
            foreach ($this->getSocialArray() as $key => $val) {
                $name = $this->getServiceName($key);
                $r[$name] = array('type' => 'checkbox', 'hint' => $this->getSocialApp($key));
                $r[$name . '_client_id'] = array('type' => 'text');
                $r[$name . '_client_secret'] = array('type' => 'text');
            }
        }
        return $r;
    }

    /**
     *
     * for view settings
     */
    public function getJsonSocialClasses()
    {
        $r = array();
        foreach ($this->getSocialArray() as $key => $val) {
            $name = $this->getServiceName($key);
            $r['#' . get_class($this) . '_' . $name] = array(
                '.field_' . $name . '_client_secret',
                '.field_' . $name . '_client_id'
            );
        }
        return CJSON::encode($r);
    }

    private function getSocialArray()
    {
        return array(
            'DropboxOAuthService' => 'Dropbox',
            'LinkedinOAuthService' => 'Linkedin',
            'GoogleOAuthService' => 'Google',
            'MailruOAuthService' => 'Mail.ru',
            'YandexOAuthService' => 'Yandex',
            'GitHubOAuthService' => 'GitHub',
            'TwitterOAuthService' => 'Twitter',
            'OdnoklassnikiOAuthService' => 'Одноклассники',
            'FacebookOAuthService' => 'Facebook',
            'VKontakteOAuthService' => 'В контакте',
        );
    }

    // public function init() {
    // $test = new GoogleOAuthService();
    // echo $test->name;
    //   parent::init();
    // }

    private function getServiceName($name)
    {
        $service = new $name;
        return $service->name;
    }

    public function rules()
    {

        $rules = array();
        $rules[] = array('favorite_limit, upload_types, upload_size, min_password, remind_mail_tpl', 'required');
        $rules[] = array('bad_name, bad_email', 'length', 'max' => 255);
        $rules[] = array('favorite_limit', 'length', 'max' => 2);
        //if (Yii::app()->hasComponent('eauth')) {
        //    $rules[] = array('db_client_id, db_client_secret, lin_client_id, lin_client_secret, go_client_id, go_client_secret, mailru_client_id, mailru_client_secret, ya_client_id, ya_client_secret, gh_client_id, gh_client_secret, fb_client_id, fb_client_secret, vk_client_id, vk_client_secret, ok_client_id, ok_client_secret, tw_client_id, tw_client_secret', 'length', 'max' => 255);
        //    $rules[] = array('facebook, vkontakte, odnoklassniki, twitter, github, yandex, mailru, google_oauth, linkedin, dropbox', 'boolean');
        //}
        $rules[] = array('favorite_limit, min_password', 'numerical', 'integerOnly' => true);
        $rules[] = array('favorites, register_nomail, registration, upload_avatar, enable_register_captcha', 'boolean');
        $rules[] = array('change_theme, social_auth', 'boolean');


        // return $rules;
        return CMap::mergeArray($rules, $this->eauthRules());
    }

    public function tpl_remind()
    {
        return array(
            '{username}',
            '{recovery_password}',
            '{active_url}',
        );
    }
}
