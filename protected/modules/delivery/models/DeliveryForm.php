<?php

class DeliveryForm extends FormModel {

    const MODULE_ID = 'delivery';

    public $text;
    public $from;
    public $themename;

    public function rules() {
        return array(
            array('text, from, themename', 'required'),
        );
    }

}
