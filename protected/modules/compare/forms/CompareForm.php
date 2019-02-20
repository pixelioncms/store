<?php

class CompareForm extends FormModel {


    public $type=0;

    const MODULE_ID = 'compare';
   /* public function init() {
        $this->attributes = array(
            'type'=>(int)Yii::app()->request->getParam('type',$this->type)
        );
    }*/
    public function rules() {
        return array(
            array('type', 'required'),
        );
    }

}
