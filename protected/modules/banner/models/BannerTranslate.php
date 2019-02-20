<?php

/**
 * Class to access news translations
 *
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 * @property string $content
 */
class BannerTranslate extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{banner_translate}}';
    }

}