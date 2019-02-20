<?php

class ComponentsModel extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{components}}';
    }

    public function rules() {
        return array(
            array('name', 'safe', 'on' => 'search'),
        );
    }


    public function search() {
        $criteria = new CDbCriteria;

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
