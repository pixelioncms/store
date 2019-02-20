<?php

Yii::import('mod.shop.models.*');

/**
 * Global events
 */
class ShopModuleEvents {

    /**
     * @var array
     */
    public $classes = array(
        'ShopProduct',
        'ShopCategory',
        'ShopAttribute',
        'ShopManufacturer',
        'ShopDeliveryMethod',
    );

    /**
     * @return array of events to subscribe module
     */
    public function getEvents() {
        return array(
            array('LanguageModel', 'onAfterSave', array($this, 'insertTranslations')),
            array('LanguageModel', 'onAfterDelete', array($this, 'deleteTranslations')),
            array('ShopManufacturer', 'onAfterDelete', array($this, 'deleteManufacturer')),
            array('ShopCategory', 'onAfterDelete', array($this, 'deleteCategory')),
        );
    }

    /**
     * `On after create new language` event.
     * Create default translation for each product object.
     * @param $event
     */
    public function insertTranslations($event) {
        if ($event->sender->isNewRecord) {
            foreach ($this->classes as $class)
                $this->_insert($class, $event);
        }
    }

    /**
     * @param $class
     * @param $event
     */
    public function _insert($class, $event) {
        $objects = $class::model()
                ->language(Yii::app()->languageManager->default->code)
                ->findAll();

        if ($objects) {
            foreach ($objects as $obj)
                $obj->createTranslation($event->sender->getPrimaryKey());
        }
    }

    /**
     * Delete product translations after deleting language
     * @param $event
     */
    public function deleteTranslations($event) {
        foreach ($this->classes as $class)
            $this->_delete($class . 'Translate', $event);
    }

    /**
     * @param $class
     * @param $event
     */
    private function _delete($class, $event) {
        $objects = $class::model()->findAll(array(
            'condition' => 'language_id=:lang_id',
            'params' => array(':lang_id' => $event->sender->getPrimaryKey())
                ));

        if ($objects) {
            foreach ($objects as $obj)
                $obj->delete();
        }
    }

    /**
     * @param $event CEvent
     */
    public function deleteManufacturer($event) {
        Yii::app()->db->createCommand()->delete('{{shop_discount_manufacturer}}', 'manufacturer_id=:id', array(':id' => $event->sender->getPrimaryKey()));
    }

    /**
     * @param $event CEvent
     */
    public function deleteCategory($event) {
        Yii::app()->db->createCommand()->delete('{{shop_discount_category}}', 'category_id=:id', array(':id' => $event->sender->getPrimaryKey()));
    }

}
