<?php

class SeoUrl extends ActiveRecord
{
    const MODULE_ID = 'seo';

    /**
     * Returns the static model of the specified AR class.
     * @return static the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{seo_url}}';
    }

    public function defaultScope()
    {
        return array(
            //'order' => 'url ASC'
            'order' => 'id DESC'
        );
    }

    public function getGridKeywords()
    {
        $keys = explode(',', $this->keywords);
        return '<span class="badge badge-secondary m-1">' . implode("</span><span class=\"badge badge-secondary m-1\">", $keys) . '</span>';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url', 'required'),
            //array('url', 'UniqueAttributesValidator', 'with' => 'url'),
            array('title, description, keywords, text, h1', 'type', 'type' => 'string'),
            array('domain', 'numerical', 'integerOnly' => true),
            array('title, description, keywords, text, h1, meta_robots', 'default', 'setOnEmpty' => true, 'value' => null),
            array('title, h1', 'length', 'max' => 150),
            array('meta_robots', 'robotsValidator'),

        );
    }

    public function robotsValidator($attr)
    {

        if ($this->$attr) {
            if (count($this->$attr) <= 2) {
                $this->$attr = implode(',', $this->$attr);
            } else {
                $this->addError($attr, 'Максимальное выбранное значеное 2 шт.');
            }
        }
    }


    public function afterFind()
    {

        if (!$this->getIsNewRecord()) {
            if ($this->meta_robots) {
                $this->meta_robots = explode(',', $this->meta_robots);
            }
        }
        parent::afterFind(); // TODO: Change the autogenerated stub
    }
    public function beforeSave()
    {

        $this->domain = self::getDomainId();
        return parent::beforeSave();
    }

    public static function getDomainId(){
        return array_search(Yii::app()->request->serverName,Yii::app()->params['domains']);
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'seoMains' => array(self::HAS_MANY, 'SeoMain', 'url'),
            'params' => array(self::HAS_MANY, 'SeoParams', 'url_id'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('h1', $this->h1, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('keywords', $this->keywords, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('domain', self::getDomainId(), true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,

        ));
    }

}
