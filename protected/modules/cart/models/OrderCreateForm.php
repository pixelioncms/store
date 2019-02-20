<?php

class OrderCreateForm extends FormModel {

    public $user_name;
    public $user_email;
    public $user_phone;
    public $user_city;
    public $user_address;
    public $user_comment;
    public $delivery_id;
    public $payment_id;

    public $registerGuest = false;
    private $_password;
    const MODULE_ID = 'cart';

    public function init() {
        if (!Yii::app()->user->isGuest && Yii::app()->controller instanceof Controller) {
            // NEED CONFINGURE
            $this->user_name = Yii::app()->user->getUsername();
            $this->user_phone = Yii::app()->user->phone;
            //$this->user_address = Yii::app()->user->address; //comment for april
            $this->user_email = Yii::app()->user->email;
        } else {
            $this->_password = User::encodePassword(CMS::gen((int) Yii::app()->settings->get('users', 'min_password') + 2));
        }
    }

    /**
     * Validation
     * @return array
     */
    public function rules() {
        return array(
            array('user_name, user_email', 'required'),
            array('user_email', 'email'),
            array('user_comment', 'length', 'max' => 500),
            array('user_address', 'length', 'max' => 255),
            array('user_email, user_city', 'length', 'max' => 100),
            array('user_phone', 'PhoneValidator'),
            array('delivery_id', 'validateDelivery'),
            array('payment_id', 'validatePayment'),
            array('registerGuest', 'boolean'),
        );
    }

    public function validateDelivery() {
        if (ShopDeliveryMethod::model()->countByAttributes(array('id' => $this->delivery_id)) == 0)
            $this->addError('delivery_id', self::t('VALID_DELIVERY'));
    }


    public function validatePayment() {
        if (ShopPaymentMethod::model()->countByAttributes(array('id' => $this->payment_id)) == 0)
            $this->addError('payment_id', self::t('VALID_PAYMENT'));
    }

    public function registerGuest() {
        if (Yii::app()->user->isGuest && $this->registerGuest) {
            $user = new User('registerFast');
            $user->password = $this->_password;
            $user->username = $this->user_name;
            $user->email = $this->user_email;
            $user->login = $this->user_email;
            $user->phone = $this->user_phone;
            if ($user->validate()) {
                $user->save();
                $this->sendRegisterMail();
                Yii::app()->authManager->assign('Authenticated', $user->id);
                Yii::app()->user->setFlash('success_register',Yii::t('CartModule.default', 'SUCCESS_REGISTER'));
            } else {
                $this->addError('registerGuest', 'Ошибка регистрации');
                Yii::app()->user->setFlash('error_register',Yii::t('CartModule.default', 'ERROR_REGISTER'));
                print_r($user->getErrors());
                die('error register');
            }
        }
    }
    
    private function sendRegisterMail(){
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = Yii::app()->settings->get('app','site_name');
        $mailer->Subject = 'Вы загеристрованы';
        $mailer->Body = 'Ваш пароль: '.$this->_password;
        $mailer->AddAddress($this->user_email);
        $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

}
