<?php

/**
 * Class to access news translations
 *
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 * @property string $title
 * @property string $short_text
 * @property string $full_text
 */
class NewsTranslate extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{news_translate}}';
    }

}