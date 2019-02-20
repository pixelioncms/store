<?php

/**
 * Class to access product translations
 *
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 * @property string $name
 * @property string $short_description
 * @property string $full_description
 */
class ShopProductTranslate extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('name, short_description, full_description', 'default', 'setOnEmpty' => true, 'value' => null),
        );
    }

    public function tableName()
    {
        return '{{shop_product_translate}}';
    }

}