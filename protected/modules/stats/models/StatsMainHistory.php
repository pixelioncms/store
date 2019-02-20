<?php

class StatsMainHistory extends ActiveRecord {

    const MODULE_ID = 'stats';

    /**
     * Returns the static model of the specified AR class.
     * @return Page the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{main_history}}';
    }



    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            //array('day, dt, tm, refer, ip, proxy, host, lang, user, req', 'type', 'type' => 'string'),
            array('refer, user, req', 'required'),
            array('proxy, host, ip', 'length', 'min' => 64),
            array('day', 'length', 'min' => 3),
            array('dt', 'length', 'min' => 8),
            array('tm', 'length', 'min' => 5),
            array('lang', 'length', 'min' => 2),
            //array('title, seo_alias', 'required'),
           // array('category_id', 'numerical', 'integerOnly' => true),
           // array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
           // array('title, seo_alias, seo_title, seo_description, seo_keywords', 'length', 'max' => 255),
            //array('id, user_id, category_id, title, seo_alias, short_text, full_text, seo_title, seo_description, seo_keywords, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions. Used in admin search.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user.username', $this->user_id, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
