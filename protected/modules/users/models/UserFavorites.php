<?php

class UserFavorites extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_favorites}}';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('owner_title, model_class, url', 'length', 'max' => 255),
            array('object_id, user_id', 'numerical', 'integerOnly' => true),
            array('object_id, owner_title, model_class, user_id, date_create', 'safe', 'on' => 'search'),
        );
    }

    public function scopes() {
        return array(
            'currentUser' => array(
                'condition' => '`t`.`user_id`=:userid',
                'params' => array(
                    ':userid' => Yii::app()->user->getId(),
                )
            )
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'mtitle' => 'Name',
            'text' => 'Text',
            'position' => 'Position',
            'display' => 'Display',
            'ordern' => 'Ordern',
        );
    }

    public function search() {

        $criteria = new CDbCriteria;
        $criteria->scopes = array('currentUser');
        $criteria->compare('id', $this->id);
        $criteria->compare('owner_title', $this->owner_title, true);

        // $criteria->condition = '`t`.`user_id`=:userid';
        // $criteria->params = array(
        //     ':userid' => Yii::app()->user->getId()
        // );

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => Yii::app()->settings->get('users','favorite_limit'),
                        'pageVar' => 'page',
                    ),
                ));
    }

}
