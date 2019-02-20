<?php

//http://seegatesite.com/bootstrap/simple_sidebar_menu.html
class AdminModule extends WebModule
{

    protected $access = 0;

    const MODULE_ID = 'admin';

    public function init()
    {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('icon-home');
    }

    public function getAdminMenu()
    {
        return array(
            'system' => array(
                //  'visible'=>  Yii::app()->user->isSuperuser,
                'active' => false,
                'items' => array(
                    array(
                        'label' => Yii::t('app', 'MODULES'),
                        'url' => array('/admin/app/modules'),
                        'icon' => Html::icon('icon-puzzle'),
                        'active' => $this->getIsActive('admin/modules'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Modules.*', 'Admin.Modules.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'LANGUAGES'),
                        'url' => array('/admin/app/languages'),
                        'icon' => Html::icon('icon-language'),
                        'active' => $this->getIsActive('admin/languages'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Languages.*')),
                    ),
                    array(
                        'label' => Yii::t('app', 'FILE_EDITOR'),
                        'url' => array('/admin/app/fileEditor'),
                        'icon' => Html::icon('icon-edit'),
                        'active' => $this->getIsActive('admin/fileEditor'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.FileEditor.*', 'Admin.FileEditor.Index')),
                    ),
                    array(
                        'label' => Yii::t('app', 'CATEGORIES'),
                        'url' => array('/admin/app/categories'),
                        'icon' => Html::icon('icon-books'),
                        'active' => $this->getIsActive('admin/categories'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Categories.*', 'Admin.Categories.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'ENGINE_MENU'),
                        'url' => array('/admin/app/menu'),
                        'icon' => Html::icon('icon-menu'),
                        'active' => $this->getIsActive('admin/menu'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Menu.*', 'Admin.Menu.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'WIDGETS'),
                        'url' => array('/admin/app/widgets'),
                        'icon' => Html::icon('icon-chip'),
                        'active' => $this->getIsActive('admin/widgets'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Widgets.*', 'Admin.Widgets.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'BLOCKS'),
                        'url' => array('/admin/app/blocks'),
                        'icon' => Html::icon('icon-blocks'),
                        'active' => $this->getIsActive('admin/blocks'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Blocks.*', 'Admin.Blocks.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'TEMPLATE'),
                        'url' => array('/admin/app/template'),
                        'active' => $this->getIsActive('admin/template'),
                        'icon' => Html::icon('icon-template'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Template.*', 'Admin.Template.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'DATABASE'),
                        'url' => array('/admin/app/database'),
                        'icon' => Html::icon('icon-database'),
                        'active' => $this->getIsActive('admin/database'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Database.*', 'Admin.Database.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'SECURITY'),
                        'url' => array('/admin/app/security'),
                        'icon' => Html::icon('icon-security'),
                        'active' => $this->getIsActive('admin/security'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Security.*', 'Admin.Security.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => array('/admin/app/settings'),
                        'icon' => Html::icon('icon-settings'),
                        'active' => $this->getIsActive('admin/settings'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Settings.*', 'Admin.Settings.Index'))
                    ),
                    array(
                        'label' => Yii::t('app', 'SVC'),
                        'url' => array('/admin/app/service'),
                        'icon' => Html::icon('icon-operator'),
                        'active' => $this->getIsActive('admin/service'),
                        'visible' => Yii::app()->user->openAccess(array('Admin.Service.*', 'Admin.Service.Index'))
                    ),
                ),
            )
        );
    }

    public function getName()
    {
        return Yii::t('app', 'CMS');
    }

    public function getDescription()
    {
        return Yii::t('app', 'CMS');
    }

    public function getAdminSidebarMenu()
    {
        if (in_array(Yii::app()->controller->id, array('default', 'Desktop'))) {
            $desk = array();
            $desktops = Desktop::model()->findAll();
            if ($desktops) {
                foreach ($desktops as $desktop) {
                    if ($desktop->accessControlDesktop()) {
                        $icon = ($desktop->user_id) ? Html::image(Yii::app()->user->getAvatarUrl('50x50'), '', array('height' => 30)) : Html::icon('icon-home');
                        $desk[] = array(
                            'label' => $desktop->name,
                            'url' => array('/admin/?d=' . $desktop->id),
                            'icon' => $icon,
                            'active' => (Yii::app()->request->getParam('d') == $desktop->id) ? true : false
                        );
                    }
                }
            }
            return $desk;
        } else {
            return $this->adminMenu['system']['items'];
        }
    }

}
