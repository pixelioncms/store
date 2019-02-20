<?php

//Yii::import('application.modules.shop.models.ShopManufacturer');
//Yii::import('application.modules.shop.models.ShopCategory');

/**
 * Global events
 */
class DiscountsModuleEvents {

    /**
     * @return array of events to subscribe module
     */
    public function getEvents() {
        return array(
            array('ShopManufacturer', 'onAfterDelete', array($this, 'deleteManufacturer')),
            array('ShopCategory', 'onAfterDelete', array($this, 'deleteCategory')),
        );
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
