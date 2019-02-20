<?php

/**
 * This is the model class for table "ShopProductCategoryRef".
 *
 * The followings are the available columns in table 'ShopProductCategoryRef':
 * @property integer $id
 * @property integer $category
 * @property integer $product
 * @property boolean $is_main
 */
class ShopProductCategoryRef extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return ShopProductCategoryRef the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_product_category_ref}}';
    }
    
    public function relations() {
        return array(
            'active' => array(self::STAT, 'ShopProduct', 'id', 'condition'=>'`products`.`switch`=1'),
            'countProducts' => array(self::HAS_MANY, 'ShopProductCategoryRef', 'category'),
        );
    }
}