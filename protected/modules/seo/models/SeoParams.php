<?php

/**
 * This is the model class for table "seo_params".
 *
 * The followings are the available columns in table 'seo_params':
 * @property integer $id
 * @property integer $url
 * @property string $name
 * @property string $content
 * @property string $param
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property SeoParams $url0
 */
class SeoParams extends CActiveRecord {

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
        return '{{seo_params}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url_id, name, content, active', 'required'),
            array('url_id, active', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('param', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, url_id, name, content, param, active', 'safe', 'on' => 'search'),
        );
    }



    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'url_id' => 'Url',
            'name' => 'Name',
            'content' => 'Content',
            'param' => 'Param',
            'active' => 'Active',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('url_id', $this->url_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('param', $this->param, true);
        $criteria->compare('active', $this->active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
