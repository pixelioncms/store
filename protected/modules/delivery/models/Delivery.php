<?php

class Delivery extends ActiveRecord {

    const MODULE_ID = 'delivery';

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
            ),
            'elements' => array(
                'name' => array('type' => 'text'),
                'email' => array('type' => 'text'),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            ),
                ), $this);
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{delivery}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('email, name', 'required'),
            array('email', 'validateUserEmail'),
            array('email', 'email'),
            array('name, email', 'type', 'type' => 'string'),
            array('name, email', 'length', 'max' => 100),
            array('email', 'length', 'min' => 6),
            array('id, email, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('date_create'),
        );
        return $a;
    }

    public function validateUserEmail($attr) {
        $labels = $this->attributeLabels();
        $checkUser = User::model()->countByAttributes(array(
            'email' => $this->$attr,
                ), 't.id != :id AND subscribe=:subscribe', array(':id' => (int) Yii::app()->user->id, ':subscribe' => 1));

        $checkEmail = Delivery::model()->countByAttributes(array(
            'email' => $this->$attr,
        ));

        if ($checkUser > 0)
            $this->addError($attr, Yii::t('DeliveryModule.default', 'SUBSCRIBE_USER_ALREADY', array('{attr}' => $labels[$attr])));
        if ($checkEmail > 0)
            $this->addError($attr, Yii::t('DeliveryModule.default', 'SUBSCRIBE_USER_ALREADY', array('{attr}' => $this->$attr)));
    }



    /**
     * Send recovery email
     */
    public function sendRecoveryMessage() {
        $this->confirmation_key = $this->generateKey(10);
        //$this->user->recovery_password = $this->generateKey(15);
        $this->save(false, false, false);

        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
        $mailer->Subject = Yii::t('DeliveryModule.default', 'SUBSCRIBE_SUBJECT_TITLE',array(
            '{site_name}'=>Yii::app()->settings->get('app', 'site_name')
        ));
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
        $replace = array(
            '{site_name}'=>Yii::app()->settings->get('app', 'site_name'),
            '{name}' => $this->name,
            '{recovery_key}' => $this->confirmation_key,
            '{active_url}' => Yii::app()->createAbsoluteUrl('/delivery/default/confirmed', array('key' => $this->confirmation_key)),
        );
        return CMS::textReplace('<p>Здраствуйте, {name}</p>
        <p>Вы подписались на расскилку интернет-магазина "{site_name}"</p>
        <p>Для подтверждения пройдите по ссылке {active_url}</p>', $replace);
    }

    /**
     * Generate key
     * @return string
     */
    public function generateKey($size) {
        $result = '';
        $chars = '1234567890qweasdzxcrtyfghvbnuioplkjnm';
        while (mb_strlen($result, 'utf8') < $size)
            $result .= mb_substr($chars, rand(0, mb_strlen($chars, 'utf8')), 1);

        if (Delivery::model()->countByAttributes(array('confirmation_key' => $result)) > 0)
            $this->generateKey($size);

        return strtoupper($result);
    }
    /**
     * Activate new user password
     * @static
     * @param $key
     * @return bool
     */
    public static function activeNewPassword($key)
    {
        $model = Delivery::model()->findByAttributes(array('confirmation_key' => $key));

        if (!$model)
            return false;

        $model->confirmation_key = NULL;
        $model->confirmed = true;
        $model->save(false, false, false);
        return $model;
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('date_create', $this->date_create, true);

        return new ActiveDataProvider($this, array('criteria' => $criteria));
    }

}
