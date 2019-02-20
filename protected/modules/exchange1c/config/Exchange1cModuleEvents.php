<?php

class Exchange1cModuleEvents {

    /**
     * @return array
     */
    public function getEvents() {
        return array(
            array('ShopCategory', 'onAfterDelete', array($this, 'deleteExternalCategory')),
            array('ShopAttribute', 'onAfterDelete', array($this, 'deleteExternalAttribute')),
            array('ShopProduct', 'onAfterDelete', array($this, 'deleteExternalProduct')),
            array('ShopManufacturer', 'onAfterDelete', array($this, 'deleteExternalManufacturer')),
        );
    }

    /**
     * @param $event
     */
    public function deleteExternalCategory($event) {
        Yii::import('mod.exchange1c.components.C1ExternalFinder');
        $this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_CATEGORY);
    }

    /**
     * @param $event
     */
    public function deleteExternalAttribute($event) {
        Yii::import('mod.exchange1c.components.C1ExternalFinder');
        $this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE);
    }

    /**
     * @param $event
     */
    public function deleteExternalProduct($event) {
        Yii::import('mod.exchange1c.components.C1ExternalFinder');
        $this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_PRODUCT);
    }

    /**
     * @param $event
     */
    public function deleteExternalManufacturer($event) {
        Yii::import('mod.exchange1c.components.C1ExternalFinder');
        $this->deleteRecord($event->sender, C1ExternalFinder::OBJECT_TYPE_MANUFACTURER);
    }

    /**
     * @param CActiveRecord $model
     * @param $type
     */
    protected function deleteRecord(CActiveRecord $model, $type) {
        Yii::app()->db->createCommand()->delete('{{exchange1c}}', 'object_id=:object_id AND object_type=:object_type', array(
            ':object_id' => $model->getPrimaryKey(),
            ':object_type' => $type,
        ));
    }

}
