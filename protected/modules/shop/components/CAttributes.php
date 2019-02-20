<?php

class CAttributes {

    public $model;
    public $attributes;
    protected $_models;

    public function __construct($model) {
        $this->model = $model;
        $this->attributes = $model->getEavAttributes();
    }

    /**
     * @return array of used attribute models
     */
    public function getModels($lang = false) {
        if (is_array($this->_models))
            return $this->_models;

        $this->_models = array();
        $cr = new CDbCriteria;

        $mdl = ShopAttribute::model();

        $alias = $mdl->getTableAlias(false);



        $cr->addInCondition($alias.'.name', array_keys($this->attributes));
        $query = $mdl
                ->displayOnFront()
                //->language(($lang) ? $lang : Yii::app()->languageManager->active->id)
                ->sorting()
                ->findAll($cr);

        foreach ($query as $m)
            $this->_models[$m->name] = $m;

        return $this->_models;
    }

    public function getData() {
        foreach (Yii::app()->languageManager->languages as $lang => $l) {
            $result[$lang] = array();
            foreach ($this->getModels($l->id) as $data) {
                $result[$lang][$data->name] = (object) array(
                            'name' => $data->title,
                            'value' => $data->renderValue($this->attributes[$data->name]),
                );
            }
        }

        return (object) $result[Yii::app()->language];
    }

    /**
     * Для авто заполнение short_description товара
     * @param type $object Модель товара
     * @return string
     */
    public function getStringAttr() {
        $data = array();
        foreach ($this->getModels() as $model)
            $data[$model->title] = $model->renderValue($this->attributes[$model->name]);
        $content = '';
        if (!empty($data)) {
            $numItems = count($data);
            $i = 0;
            foreach ($data as $title => $value) {
                if (++$i === $numItems) { //last element
                    $content .= Html::encode($title) . ': ' . Html::encode($value);
                } else {
                    $content .= Html::encode($title) . ': ' . Html::encode($value) . ' / ';
                }
            }
        }
        return $content;
    }

}
