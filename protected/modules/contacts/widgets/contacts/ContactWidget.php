<?php

class ContactWidget extends CWidget {

    public function run() {
        //Yii::import('mod.contacts.models.*');
        //$model = ContactsManagers::model()->findAll();
        $model = Yii::app()->controller->getContacts();
        if ($model) {
            $this->render($this->skin, array('model' => $model));
        }
    }

}
