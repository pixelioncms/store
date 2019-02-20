<?php

class CategoryBehavior extends CActiveRecordBehavior {

    private $_category_id;
    private $_model;
    public $router;

    public function attach($owner) {
        parent::attach($owner);
    }

    public function setCategory($category) {
        if (isset($category)) {
            $criteria = new CDbCriteria;
            $criteria->params = array(':cid' => $category);
            if (is_numeric($category)) {
                $criteria->addCondition('id = :cid', 'OR');
            } else {
                $criteria->addCondition('seo_alias = :cid', 'OR');
            }
            $this->_model = CategoriesModel::model()->find($criteria);
            $this->setCategory_id($this->_model->id);
        }
        return $this->_model;
    }

    public function getCategoryUrl() {
        return $this->router;
    }

    public function getCategory_id() {
        return $this->_category_id;
    }

    public function setCategory_id($id) {
        $this->_category_id = $id;
    }

}
