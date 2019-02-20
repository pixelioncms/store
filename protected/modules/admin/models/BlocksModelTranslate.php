<?php

/**
 * Модель переводов блоков сайта {@see BlocksModel} 
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @license http://corner-cms.com/license.txt CORNER CMS License
 * @link http://corner-cms.com CORNER CMS
 * @package module
 * @subpackage admin.models
 * @uses CActiveRecord
 * 
 * @property int $id
 * @property int $object_id
 * @property int $language_id
 * @property string $name
 * @property string $content
 */
class BlocksModelTranslate extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{blocks_translate}}';
    }

}
