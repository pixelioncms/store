<?php

class ContactsModule extends WebModule
{
    public $configFiles = array(
        'contacts' => 'ConfigContactForm'
    );

    public function init()
    {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
        $this->setIcon('icon-phone');
    }

    public function getPhones()
    {
        $cfg = Yii::app()->settings->get($this->id);
        if ($cfg['phone']) {
            return explode(',', $cfg['phone']);
        } else {
            return false;
        }
    }

    public function getEmails()
    {
        $cfg = Yii::app()->settings->get($this->id);
        if ($cfg['form_emails']) {
            return explode(',', $cfg['form_emails']);
        } else {
            return false;
        }
    }

    public function getAddress()
    {
        $cfg = Yii::app()->settings->get($this->id);
        if ($cfg['address']) {
            return $cfg['address'];
        } else {
            return false;
        }
    }

    public function afterUninstall()
    {
        $db = Yii::app()->db->createCommand();
        $db->dropTable(ContactsMaps::model()->tableName());
        $db->dropTable(ContactsMarkers::model()->tableName());
        $db->dropTable(ContactsRouter::model()->tableName());
        $db->dropTable(ContactsRouterTranslate::model()->tableName());
        //$db->dropTable(ContactsCites::model()->tableName());
        //$db->dropTable(ContactsCitesTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules()
    {
        return array(
            'contacts' => 'contacts/default/index',
            'contacts/city/<city>' => 'contacts/default/index',
            'contacts/captcha' => 'contacts/default/captcha',
        );
    }

    public function getAdminMenu()
    {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'visible' => Yii::app()->user->openAccess(array('Contacts.Default.*', 'Contacts.Default.Index')),
                        'url' => $this->adminHomeUrl,
                        'icon' => Html::icon($this->icon),
                        'active' => $this->getIsActive(array('contacts', 'contacts/maps', 'contacts/markers', 'contacts/router')),
                    ),
                )
            )
        );
    }

    public function getAdminSidebarMenu()
    {
        $c = Yii::app()->controller->id;
        return array(
            array(
                'label' => $this->name,
                'url' => $this->adminHomeUrl,
                'active' => $this->getIsActive(array('admin/contacts/default')),
                'icon' => Html::icon($this->icon),
                'visible' => Yii::app()->user->openAccess(array('Contacts.Default.*', 'Contacts.Default.Index')),
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'MAPS'),
                'url' => array('/admin/contacts/maps/index'),
                'active' => $this->getIsActive('admin/contacts/maps'),
                'icon' => Html::icon('icon-location-map'),
                'visible' => Yii::app()->user->openAccess(array('Contacts.Maps.*', 'Contacts.Maps.Index')),
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'MARKERS'),
                'url' => array('/admin/contacts/markers/index'),
                'active' => $this->getIsActive('admin/contacts/markers'),
                'icon' => Html::icon('icon-location-marker'),
                'visible' => Yii::app()->user->openAccess(array('Contacts.Markers.*', 'Contacts.Markers.Index')),
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'ROUTER'),
                'url' => array('/admin/contacts/router/index'),
                'active' => $this->getIsActive('admin/contacts/router'),
                'icon' => Html::icon('icon-location-route'),
                'visible' => Yii::app()->user->openAccess(array('Contacts.Router.*', 'Contacts.Router.Index')),
            ),
            /*array(
                'label' => Yii::t('ContactsModule.default', 'CITES'),
                'url' => array('/admin/contacts/cites/index'),
                'active' => $this->getIsActive('admin/cites'),
                'icon' => Html::icon('icon-earth'),
                'visible' => Yii::app()->user->openAccess(array('Contacts.Cites.*', 'Contacts.Cites.Index')),
            ),*/
        );
    }

    public function getVersion()
    {
        return '1.0b';
    }

}
