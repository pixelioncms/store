<?php

/**
 * Rights module class file.
 *
 * @author Christoffer Niska <cniska@live.com>
 * @copyright Copyright &copy; 2010 Christoffer Niska
 * @version 1.3.0
 * 
 * DO NOT CHANGE THE DEFAULT CONFIGURATION VALUES!
 * 
 * You may overload the module configuration values in your rights-module 
 * configuration like so:
 * 
 * 'modules'=>array(
 *     'rights'=>array(
 *         'userNameColumn'=>'name',
 *         'flashSuccessKey'=>'success',
 *         'flashErrorKey'=>'error',
 *     ),
 * ),
 */
class RightsModule extends WebModule {

    public $access = 0;

    /**
     * @property string the name of the role with superuser privileges.
     */
    public $superuserName = 'Admin';

    /**
     * @property string the name of the guest role.
     */
    public $authenticatedName = 'Authenticated';

    /**
     * @property string the name of the user model class.
     */
    public $userClass = 'User';

    /**
     * @property string the name of the id column in the user table.
     */
    public $userIdColumn = 'id';

    /**
     * @property string the name of the username column in the user table.
     */
    public $userNameColumn = 'login';

    /**
     * @property boolean whether to enable business rules.
     */
    public $enableBizRule = true;

    /**
     * @property boolean whether to enable data for business rules.
     */
    public $enableBizRuleData = false;

    /**
     * @property boolean whether to display authorization items description 
     * instead of name it is set.
     */
    public $displayDescription = true;

    /**
     * @property string the flash message key to use for success messages.
     */
    public $flashSuccessKey = 'success';

    /**
     * @property string the flash message key to use for error messages.
     */
    public $flashErrorKey = 'RightsError';

    /**
     * @property boolean whether to install rights when accessed.
     */
    public $install = false;

    /**
     * @property string the base url to Rights. Override when module is nested.
     */
    public $baseUrl = '/admin/rights/default/index';

    /**
     * @property boolean whether to enable debug mode.
     */
    public $debug = false;

    // private $_assetsUrl;

    /**
     * Initializes the "rights" module.
     */
    public function init() {

        // Set required classes for import.
        $this->setImport(array(
            $this->id . '.components.*',
            $this->id . '.components.behaviors.*',
            $this->id . '.components.dataproviders.*',
            $this->id . '.controllers.*',
            $this->id . '.models.*',
        ));

        // Set the required components.
        $this->setComponents(array(
            'authorizer' => array(
                'class' => 'RAuthorizer',
                'superuserName' => $this->superuserName,
            ),
            'generator' => array(
                'class' => 'RGenerator',
            ),
        ));
    }

    /**
     * @return RightsAuthorizer the authorizer component.
     */
    public function getAuthorizer() {
        return $this->getComponent('authorizer');
    }

    /**
     * @return RightsInstaller the installer component.
     */
    public function getInstaller() {
        return $this->getComponent('installer');
    }

    /**
     * @return RightsGenerator the generator component.
     */
    public function getGenerator() {
        return $this->getComponent('generator');
    }

    public function getAdminMenu() {
        return array(
            'system' => array(
                'items' => array(
                    array(
                        'label' => Rights::t('default', 'MODULE_NAME'),
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon('icon-user'),
                        'visible' => Yii::app()->user->checkAccess('Rights.Default.*'),
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->action->id;
        return array(
            array(
                'label' => Rights::t('default', 'Assignments'),
                'url' => array('admin/default/index'),
                'active' => ($c == 'view') ? true : false
            ),
            array(
                'label' => Rights::t('default', 'Permissions'),
                'url' => array('admin/authItem/permissions'),
                'active' => ($c == 'permissions') ? true : false
            ),
            array(
                'label' => Rights::t('default', 'Roles'),
                'url' => array('admin/authItem/roles'),
                'active' => ($c == 'roles') ? true : false
            ),
            array(
                'label' => Rights::t('default', 'Tasks'),
                'url' => array('admin/authItem/tasks'),
                'active' => ($c == 'tasks') ? true : false
            ),
            array(
                'label' => Rights::t('default', 'Operations'),
                'url' => array('admin/authItem/operations'),
                'active' => ($c == 'operations') ? true : false
            ),
        );
    }

    public function getName() {
        return Rights::t('default', 'MODULE_NAME');
    }

    public function getDescription() {
        return Rights::t('default', 'Управление правами доступа.');
    }

}
