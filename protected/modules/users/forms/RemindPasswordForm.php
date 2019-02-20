<?php

class RemindPasswordForm extends FormModel {

    const MODULE_ID = 'users';

    /**
     * @var string
     */
    public $email;

    /**
     * @var User
     */
    public $user;

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'validateEmail'),
        );
    }

    /**
     * Validate user email and send email message
     */
    public function validateEmail($attr) {
        $this->user = User::model()->findByAttributes(array(
            $attr => $this->$attr
        ));

        if ($this->user)
            return true;
        else
            $this->addError($attr, self::t('ERROR_VALID_EMAIL'));
    }

    /**
     * Send recovery email
     */
    public function sendRecoveryMessage() {
        $this->user->recovery_key = $this->generateKey(10);
        $this->user->recovery_password = $this->generateKey(15);
        $this->user->save(false, false, false);

        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
        $mailer->Subject = Yii::t('UsersModule.default', 'REMIN_PASS');
        $mailer->Body = $this->emailBody();
        $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
        $mailer->isHtml(true);
        $mailer->AddAddress($this->email);
        $mailer->Send();
    }

    /**
     * Email message body
     */
    private function emailBody() {
        $config = Yii::app()->settings->get('users');
        $replace = array(
            '{username}' => $this->user->username,
            '{recovery_password}' => $this->user->recovery_password,
            '{active_url}' => Yii::app()->createAbsoluteUrl('/users/remind/activatePassword', array('key' => $this->user->recovery_key)),
        );
        return CMS::textReplace($config->remind_mail_tpl, $replace);
    }

    /**
     * Generate key and password
     * @return string
     */
    public function generateKey($size) {
        $result = '';
        $chars = '1234567890qweasdzxcrtyfghvbnuioplkjnm';
        while (mb_strlen($result, 'utf8') < $size)
            $result .= mb_substr($chars, rand(0, mb_strlen($chars, 'utf8')), 1);

        if (User::model()->countByAttributes(array('recovery_key' => $result)) > 0)
            $this->generateKey($size);

        return strtoupper($result);
    }

}
