<?php

class SettingsDatabaseForm extends FormSettingsModel {

    const NAME = 'database';

    public $backup = false; //no record in db
    public $backup_limit = 1;

    public static function defaultSettings() {
        return array(
            'backup' => 0,
            'backup_limit' => 1,
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'backup' => array(
                    'type' => 'checkbox',
                ),
                'backup_limit' => array('type' => 'text'),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => self::t('CREATE')
                )
            )
                ), $this);
    }

    public function rules() {
        return array(
            array('backup_limit', 'required'),
            array('backup_limit', 'numerical', 'integerOnly' => true),
            array('backup', 'boolean'),
        );
    }

    public function save($message = true) {
        unset($this->backup);
        parent::save($message);
    }

}
