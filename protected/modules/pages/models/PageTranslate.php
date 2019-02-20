<?php

/**
 * Class to access page translations
 *
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 * @property string $title
 * @property string $full_text
 */
class PageTranslate extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('title, full_text', 'default', 'setOnEmpty' => true, 'value' => null),
        );
    }

    public function tableName()
    {
        return '{{page_translate}}';
    }

}