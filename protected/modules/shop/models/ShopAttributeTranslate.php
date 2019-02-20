<?php

/**
 * Class to access product translations
 *
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 */
class ShopAttributeTranslate extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{shop_attribute_translate}}';
    }

    public function rules() {
        return array(
            array('language_id', 'length', 'max' => 255),
        );
    }

}