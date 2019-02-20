<?php

class MailForm extends FormModel {

    public $toemail;
    public $text;
    public $subject;


    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
            'showErrorSummary' => false,
            'elements' => array(
                'subject' => array('subject' => 'text'),
                'toemail' => array('type' => 'text'),
                'text' => array('type' => 'textarea'),
            ),
        ), $this);
    }

    public function rules() {
        return array(
            array('subject, toemail, text', 'required'),
            
        );
    }

    public function attributeLabels() {
        return array(
            'toemail' => 'Получатель',
            'subject' => 'Тема письма',
            'text' => 'Письмо',
        );
    }

}