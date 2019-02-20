<?php

/**
 * Behavior for commentabe models
 */
class CommentBehavior extends CActiveRecordBehavior {

    /**
     * @var string model primary key attribute
     */
    public $pk = 'id';
    public $class_name;

    /**
     * @var string alias to class. e.g: application.store.models.ShopProduct or pages.models.Page
     */
    public $model;

    /**
     * @var string attribute name to present comment owner in admin panel. e.g: name - references to Page->name
     */
    public $owner_title;

    /**
     * @return string pk name
     */
    public function getObjectPkAttribute() {
        return $this->pk;
    }

    public function getModelName() {
        return $this->model;
    }

    public function getOwnerTitle() {
        $attr = $this->owner_title;
        return $this->getOwner()->$attr;
    }

    public function attach($owner) {
        parent::attach($owner);
    }

    /**
     * @param CEvent $event
     * @return mixed
     */
    public function afterDelete($event) {
        if (Yii::app()->hasModule('comments')) {
            Yii::import('mod.comments.models.Comments');

            $pk = $this->getObjectPkAttribute();
            Comments::model()->deleteAllByAttributes(array(
                'model' => $this->getModelName(),
                'object_id' => $this->getOwner()->$pk
            ));
        }
        return parent::afterDelete($event);
    }

    /**
     * @return string approved comments count for object
     */
    public function getCommentsCount() {
        Yii::import('mod.comments.models.Comments');
        $pk = $this->getObjectPkAttribute();
        return Comments::model()
                        ->active()
                        ->countByAttributes(array(
                            'model' => $this->getModelName(),
                            'object_id' => $this->getOwner()->$pk
                        ));
    }

}
