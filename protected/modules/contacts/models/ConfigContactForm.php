<?php

class ConfigContactForm extends FormSettingsModel {

   // public $sendMail;
    public $address;
    public $tempMessage;
    public $phone;
    public $skype;
    public $form_emails;
    public $enable_captcha;

    public static function defaultSettings() {
        return array(
            'form_emails' => 'info@pixelion.com.ua',
            'tempMessage' => '<p>{if*sender_name*}Имя отправителя: <strong>{*sender_name*}</strong>{endif}</p>
<p>{if*sender_email*}Email отправитиля: <strong>{*sender_email*}</strong>{endif}</p>
<p>{if*sender_phone*}Телефон: <strong>{*sender_phone*}</strong>{endif}</p>
<p>{if*sender_message*}</p>
<p>==============================</p>
<p><strong>Сообщение:</strong></p>
<p>{*sender_message*}</p>
<p>{endif}</p>
<p>&nbsp;</p>
<p>______________________________</p>
<p><strong>IP-адрес:</strong> {*ip*} {if*ip_country*}({*ip_country*}){endif}</p>
<p>&nbsp;</p>
<p>{*browser_string*}</p>',
            'address' => 'г. Одесса, ул. М. Арнаутская 36',
            'phone' => '',
            'skype' => '',
            'enable_captcha' => 1
        );
    }

    public function getForm() {
        Yii::import('ext.tinymce.TinymceArea');
        Yii::import('ext.tageditor.TagEditor');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'general' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'skype' => array('type' => 'text'),
                        'phone' => array('type' => 'TagEditor','options'=>array('placeholder'=>'Добавить телефон')),
                        'address' => array('type' => 'text'),
                    ),
                ),
                'form_feedback' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_FB'),
                    'elements' => array(
                        'form_emails' => array('type' => 'TagEditor'),
                        'tempMessage' => array('type' => 'TinymceArea'),
                        'enable_captcha' => array('type' => 'checkbox'),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'label' => Yii::t('app', 'SAVE'),
                    'class' => 'btn btn-success',
                )
            )
                ), $this);
    }

    public function rules() {
        return array(
            array('enable_captcha', 'boolean'),
            array('form_emails, tempMessage', 'required'),
            array('tempMessage, address, phone, skype', 'type', 'type' => 'string'),
           // array('sendMail', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
        );
    }

}

?>