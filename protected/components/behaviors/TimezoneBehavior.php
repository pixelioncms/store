<?php

/**
 * @version 1.0
 * @author Andrew S. <andrew.panix@gmail.com>
 * @name $attributes Array attributes model
 */
class TimezoneBehavior extends CActiveRecordBehavior {

    public $attributes = array();

    public function afterFind($event) {
        $owner = $this->getOwner();
        if (!in_array($owner->scenario, array('insert','update'))) {
            foreach ($this->attributes as $attr) {
                if (isset($owner->$attr)) {
                    $date = new DateTime($owner->$attr);
                    $date->setTimezone(new DateTimeZone(CMS::timezone()));
                    $owner->setAttribute($attr, $date->format('Y-m-d H:i:s'));
                } else {
                    //throw exception
                }
            }
        }
        parent::afterFind($event);
    }

}
