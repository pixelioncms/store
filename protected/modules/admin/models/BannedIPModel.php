<?php

/**
 * Модель блокировки IP адресов
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage admin.models
 * @uses ActiveRecord
 */
class BannedIPModel extends ActiveRecord
{

    const MODULE_ID = 'admin';

    public function getForm()
    {
        Yii::import('ext.tinymce.TinymceArea');
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'ip_address' => array(
                    'type' => 'text',
                    'hint' => self::t('HINT_IP_ADDRESS')
                ),
                'reason' => array('type' => 'TinymceArea'),
                'time' => array(
                    'type' => 'dropdownlist',
                    'items' => self::bannedTime(),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        ), $this);
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{banned_ip}}';
    }

    public function rules()
    {
        return array(
            array('ip_address, time', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('ip_address, reason', 'type', 'type' => 'string'),
            array('ip_address', 'length', 'max' => 50),
            array('ip_address, time, date_create', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave()
    {
        $this->timetime = time() + $this->time;
        return parent::beforeSave();
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('reason', $this->reason, true);
        $criteria->compare('ip_address', $this->ip_address, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('time', $this->time, true);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function bannedTime()
    {
        return array(
            3600 => Yii::t('app','FOR_HOUR'),
            86400 => Yii::t('app','FOR_DAY'),
            604800 => Yii::t('app','FOR_WEEK'),
            2628000 => Yii::t('app','FOR_MONTH'),
            15768000 => Yii::t('app','FOR_6MONTH'),
            31536000 => Yii::t('app','FOR_YEAR'),
            0 => Yii::t('app','FOR_PERMANENTLY'),
        );
    }

    public function getBanTime($t)
    {
        $times = self::bannedTime();
        return $times[$t];
    }

}
