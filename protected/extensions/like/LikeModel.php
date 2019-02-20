<?php

class LikeModel extends ActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{like}}';
    }

    public function rules() {
        return array(
            array('rate, object_id, model', 'required'),
        );
    }
    public function attributeLabels() {
        return array(
            'rate'=>'rate'
        );
    }

}