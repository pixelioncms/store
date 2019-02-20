<?php

/**
 * This is the model class for table "ShopRelatedProduct".
 *
 * The followings are the available columns in table 'ShopRelatedProduct':
 * @property integer $id
 * @property integer $product_id
 * @property integer $related_id
 */
class ShopRelatedProduct extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return ShopRelatedProduct the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_related_product}}';
    }

}