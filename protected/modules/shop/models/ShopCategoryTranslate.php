<?php

/**
 * Class to access product translations
 *
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 */
class ShopCategoryTranslate extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{shop_category_translate}}';
    }


}