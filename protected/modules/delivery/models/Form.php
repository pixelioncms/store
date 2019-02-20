<?php

class Form extends CFormModel
{

    public $text;
    public $from;
    public $themename;

    public function rules()
    {
        return array(
            array('text, from, themename', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'themename' => 'Заголовок письма',
            'text' => 'Содержание письма',
            'from' => 'Кому отправить',
        );
    }

}

?>