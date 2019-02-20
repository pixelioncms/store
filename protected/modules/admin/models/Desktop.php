<?php

class Desktop extends ActiveRecord {

    const MODULE_ID = 'admin';

    public function accessControlDesktop() {
        if (!$this->isNewRecord) {
            if ($this->user_id) {
                if ($this->user_id != Yii::app()->user->id) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function accessPrivateDesktop() {
        if (!$this->isNewRecord) {
            if ($this->private) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'addons' => array('type' => 'checkbox'),
                'name' => array('type' => 'text'),
                'columns' => array(
                    'type' => 'dropdownlist',
                    'items' => array(1 => 1, 2 => 2, 3 => 3),
                ),
                'private' => array(
                    'type' => 'checkbox',
                    'hint' => 'Приватный стол от всех.'
                ),
                'user_id' => array(
                    'type' => 'dropdownlist',
                    'items' => Html::listData(User::model()->findAll(), 'id', 'login'),
                    'empty' => 'Пустро',
                    'hint' => 'Только владелец и СуперАдмин сможет управлять своим рабочем столом'
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
    }

    public function tableName() {
        return '{{desktop}}';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function relations() {
        return array(
            'widgets' => array(self::HAS_MANY, 'DesktopWidgets', 'desktop_id'),
        );
    }

    public function rules() {
        return array(
            array('name, columns', 'required'),
            array('name', 'length', 'max' => 255),
            array('columns, user_id, private', 'numerical', 'integerOnly' => true),
            array('addons', 'boolean'),
            array('name, columns', 'safe', 'on' => 'search'),
        );
    }

}
