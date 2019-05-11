<?php

class TurbosmsSettingsForm extends WidgetFormModel {

    public $login;
    public $sender;
    public $password;

    public static function defaultSettings() {
        return array(
            'login' => '',
            'sender' => 'your_sender',
            'password' => '',
        );
    }

    public function rules() {
        return array(
            array('login, sender', 'type'),
            array('password, login, sender', 'required')
        );
    }

    public function getForm() {
        return array(
            'attributes' => array(
                'class' => 'form-horizontal',
                'type' => 'form',
            ),
            'elements' => array(
                'login' => array(
                    'label' => Yii::t('app', 'Логин'),
                    'type' => 'text',
                ),
                'password' => array(
                    'label' => Yii::t('app', 'Пароль'),
                    'type' => 'text',
                ),
                'sender' => array(
                    'label' => Yii::t('app', 'Отправитель'),
                    'type' => 'text',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        );
    }

}
