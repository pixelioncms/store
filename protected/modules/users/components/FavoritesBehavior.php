<?php

Yii::import('mod.users.models.UserFavorites');

class FavoritesBehavior extends CActiveRecordBehavior {

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
    public function getObjectPk() {
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
        $pk = $this->getObjectPk();
        UserFavorites::model()->deleteAllByAttributes(array(
            'model_class' => $this->getModelName(),
            'object_id' => $this->getOwner()->$pk
        ));

        return parent::afterDelete($event);
    }

    public function getFavoritesCount() {
        $pk = $this->getObjectPk();
        return UserFavorites::model()
                        ->countByAttributes(array(
                            'model_class' => $this->getModelName(),
                            'object_id' => $this->getOwner()->$pk
        ));
    }

}
