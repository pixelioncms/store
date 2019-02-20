<?php

/**
 * Shop type attributes
 * This is the model class for table "shop_type_attribute".
 *
 * The followings are the available columns in table 'shop_type_attribute':
 * @property integer $id
 * @property integer $type_id
 * @property integer $attribute_id
 */
class ShopTypeAttribute extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopTypeAttribute the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_type_attribute}}';
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'attribute' => array(self::BELONGS_TO, 'ShopAttribute', 'attribute_id'),
        );
    }

}