<?php

class SettingsSecurityForm extends FormSettingsModel {

    const NAME = 'security';

    public $backup_db;
    public $backup_time;
    public $backup_time_cache;

    public static function defaultSettings() {
        return array(
            'backup_time_cache' => CMS::time() + 3600,
            'backup_time' => 3600,
            'backup_db' => 1,
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'backup_db' => array(
                    'type' => 'checkbox',
                    'hint'=>self::t('HINT_BACKUP_DB'),
                    'help' => self::t('HELP_BACKUP_DB'),
                ),
                'backup_time' => array('type' => 'text', 'value' => $this->backup_time / 60),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public function rules() {
        return array(
            array('backup_db, backup_time, backup_time_cache', 'required'),
            array('backup_db, backup_time, backup_time_cache', 'numerical', 'integerOnly' => true),
        );
    }

}
