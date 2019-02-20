<?php

class Timeline extends ActiveRecord {

    const MODULE_ID = 'admin';

    /**
     * Returns the static model of the specified AR class.
     * @return static the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{timeline}}';
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
                //  array('mid, name, room_id', 'required'),
                //   array('name', 'length', 'max' => 255),
                //  array('id, room_id, name, range_slide, switch', 'safe', 'on' => 'search'),
        );
    }

   /* public function behaviors() {
        $a = array();
        $a['timezone'] = array(
            'class' => 'app.behaviors.TimezoneBehavior',
            'attributes' => array('datetime'),
        );

        return $a;
    }*/

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
        );
    }

    public function search($params) {

        //       $timezone = new DateTime('now');
        // $timezone->setTimezone(new DateTimeZone('UTC'));

        $dateStart = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $dateEnd = mktime(0, 0, -1, date('m'), date('d') + 1, date('Y'));

        // $dateStart = mktime(0, 0, 0, $timezone->format('m'), $timezone->format('d'), $timezone->format('Y'));
        // $dateEnd = mktime(0, 0, -1, $timezone->format('m'), $timezone->format('d') + 1, $timezone->format('Y'));


        $criteria = new CDbCriteria;
        $criteria->addBetweenCondition('datetime', date('Y-m-d H:i:s', $dateStart), date('Y-m-d H:i:s', $dateEnd));
        $criteria->limit = $params['limit'];
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'id DESC',
                    ),
                    //'pagination' => false
                    'pagination' => array(
                        'pageSize' => $params['limit'],
                    //'route'=>'default/index'
                    )
                ));
    }

}
