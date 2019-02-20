<?php

/**
 * This is the model class for table "wishlist_products".
 *
 * The followings are the available columns in table 'wishlist_products':
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package module
 * @subpackage commerce.wishlist.models
 * @uses CActiveRecord
 * 
 * @property integer $id
 * @property integer $wishlist_id
 * @property integer $product_id
 * @property integer $user_id
 */
class WishlistProducts extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WishlistProducts the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{wishlist_products}}';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'wishlist_id' => 'Wishlist',
            'product_id' => 'Product',
            'user_id' => 'User',
        );
    }

}
