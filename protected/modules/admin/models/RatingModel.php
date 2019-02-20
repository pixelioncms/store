<?php

/**
 * This is the model class for table "rating".
 *
 * The followings are the available columns in table 'rating':
 * @property integer $id
 * @property integer $article_id
 * @property integer $user_id
 * @property integer $value
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Article $article
 */
class RatingModel extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Rating the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{rating}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('mid, user_id, host', 'required'),
            array('mid, user_id', 'numerical', 'integerOnly' => true),
// The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, mid, user_id, value', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'product' => array(self::BELONGS_TO, 'Product', 'article_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'mid' => 'Article',
            'user_id' => 'User',
            'value' => 'Value',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
// Warning: Please modify the following code to remove attributes that
// should not be searched.
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('mid', $this->mid);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('value', $this->value);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
