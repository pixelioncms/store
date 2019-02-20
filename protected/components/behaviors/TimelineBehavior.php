<?php

/**
 * Usage:
 * Attach behavior and enter translateable attributes
 *   'timeline'=>array(
 *       'class'=>'ext.behaviors.TimelineBehavior',
 *   ),
 */
class TimelineBehavior extends CActiveRecordBehavior {

    public $attributes = 'title';

    /**
     * @param $owner
     */
    public function attach($owner) {
        return parent::attach($owner);
    }

    public function afterSave($event) {
        $model = $this->owner;
        if ($model->scenario == 'update' || $model->scenario == 'create') {
            $localkey = ($model->isNewRecord) ? 'CREATE_RECORD' : 'UPDATE_RECORD';
            Yii::app()->timeline->set($localkey, array(
                '{model}' => get_class($model),
                '{pk}' => $model->primaryKey,
                '{module_name}' => $this->getModuleName()
            ));
        }
        return true;
    }

    public function afterDelete($event) {
        Yii::app()->timeline->set('DELETE_RECORD', array(
            '{model}' => get_class($this->owner),
            '{pk}' => $this->owner->primaryKey,
            '{module_name}' => $this->getModuleName()
        ));
        return true;
    }

    private function getModuleName() {
        $model = $this->owner;
        return Yii::t(ucfirst($model::MODULE_ID) . 'Module.default', 'MODULE_NAME');
    }

}