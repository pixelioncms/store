<?php

class ContactForm extends FormModel
{

    const MODULE_ID = 'contacts';

    public $name;
    public $email;
    public $msg;
    public $phone;
    public $verifyCode;

    public function init()
    {
        parent::init();
        if (!Yii::app()->user->isGuest) {
            $this->phone = Yii::app()->user->phone;
            $this->email = Yii::app()->user->email;
            $this->name = Yii::app()->user->username;
        }
    }

    public function myCaptcha($attr, $params)
    {
        if (Yii::app()->request->isAjaxRequest)
            return;

        CValidator::createValidator('captcha', $this, $attr, $params)->validate($this);
    }

    public function rules()
    {
        $rules = array();
        if (Yii::app()->settings->get('contacts', 'enable_captcha')) {
            $rules['captcha'][] = array('verifyCode', 'required');
            $rules['captcha'][] = array('verifyCode', 'required', 'on' => 'insert', 'message' => Yii::t('default', 'message.verifyCode.required'));
            // comment for reCaptcha
            // $rules['captcha'][] = array('verifyCode', 'myCaptcha', 'allowEmpty' => !extension_loaded('gd'));
        } else {
            $rules['captcha'] = array();
        }
        return CMap::mergeArray(array(
            array('email, msg, name', 'required'),
            array('phone', 'safe'),
            array('phone', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => ' Что-то не так'),

            //array('phone', 'required'),
            // array('email', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
            array('email', 'email'),
        ), $rules['captcha']);
    }

    public function sendMessage()
    {
        $config = Yii::app()->settings->get('contacts');
        $mails = explode(',', $config->form_emails);
        $tpldata = array();
        $tpldata['sender_name'] = $this->name;
        $tpldata['sender_email'] = $this->email;
        $tpldata['sender_message'] = CHtml::encode($this->msg);
        $tpldata['sender_phone'] = $this->phone;
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = Yii::t('ContactsModule.default', 'FB_FORM_NAME');
        $mailer->Subject = Yii::t('ContactsModule.default', 'FB_FROM_MESSAGE', array(
            '{name}' => (isset($this->name)) ? Html::encode($this->name) : $this->email,
            '{site_name}' => Yii::app()->settings->get('app', 'site_name')
        ));
        $mailer->Body = Html::text(Yii::app()->etpl->template($tpldata, $config->tempMessage));
        // $mailer->Body = 'TEST';
        foreach ($mails as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->isHtml(true);
        $mailer->AddReplyTo($this->email);
        Yii::log('send');
        $mailer->Send();
    }

    public function performAjaxValidation()
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] == 'contact_form') {


            $result = CJSON::decode(CActiveForm::validate($this));
            // print_r($result);
            if ($result) {
                echo CActiveForm::validate($this);
                Yii::app()->end();
            }

        }
    }

}
