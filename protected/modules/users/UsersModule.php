<?php

/**
 * Модуль пользователей
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users
 * @uses WebModule
 * @copyright (c) 2016, Andrew Semenov
 * @example path description
 * @version 1.0
 * @since 1.1
 * @category Module
 */
class UsersModule extends WebModule
{
    public $configFiles = array(
        'users' => 'SettingsUsersForm'
    );

    public function init()
    {
        Yii::trace('Loaded "users" module.');
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-users');
    }

    /**
     * Init admin-level models, componentes, etc...
     */
    public function initAdmin()
    {
        Yii::trace('Init users module admin resources.');
        parent::initAdmin();
    }


    /**
     * UrlManager rules
     * @return array
     */
    public function getRules()
    {
        return array(
            'users/login/*' => 'users/login/login',
            'users/login' => 'users/login/login',
            'users/register' => 'users/register/register',
            'users/register/captcha/*' => 'users/register/captcha',
            'users/profile' => 'users/profile/index',
            'users/signin/captcha/*' => 'users/signin/captcha',
            'users/signin' => 'users/signin/index',
            'users/profile/<user_id:([\d]+)>' => 'users/profile/view',
            'users/profile/orders' => 'users/profile/orders',
            //'users/profile/avatar' => 'users/profile/avatar',
            'users/profile/saveAvatar' => 'users/profile/saveAvatar',
            'users/profile/getAvatars' => 'users/profile/getAvatars',
            'users/logout' => 'users/login/logout',
            'users/remind/activatePassword/<key>' => array('users/remind/activatePassword'),
            'users/favorites/add' => 'users/favorites/add',
            'users/favorites/delete' => 'users/favorites/delete',
            'users/favorites/<action>/<page:([\d]+)>' => 'users/favorites/<action>',
            'users/favorites/<action>' => 'users/favorites/<action>',
            'users/ajax/<action>' => 'users/ajax/<action>',
        );
    }

    public function getAdminMenu()
    {
        return array(
            'users' => array(
                'label' => $this->name,
                'url' => $this->adminHomeUrl,
                'icon' => Html::icon($this->icon),
                'visible' => Yii::app()->user->openAccess(array('Users.Default.*', 'Users.Default.Index')),
                'active' => $this->getIsActive('admin/users/default'),
            ),
        );
    }

    public function getAdminSidebarMenu()
    {
        return array(
            $this->adminMenu['users'],
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/users/settings/index'),
                'active' => $this->getIsActive('admin/users/settings'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Users.Settings.*', 'Users.Settings.Index')),
            )
        );
    }

}
