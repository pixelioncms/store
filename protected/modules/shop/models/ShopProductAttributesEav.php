<?php

/**
 * This is the model class for table "ShopProductAttributesEav".
 *
 * The followings are the available columns in table 'ShopProductAttributesEav':
 */
class ShopProductAttributesEav extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return ShopProductAttributesEav the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_product_attribute_eav}}';
    }

}