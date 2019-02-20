<?php

/**
 * 
 * array(
  'class' => 'mod.shop.components.AttributesColumns',
  'attrname' => 'size',
  'header' => 'Размеры',
  'htmlOptions' => array('class' => 'text-center')
  );
 */
Yii::import('ext.adminList.columns.DataColumn');

class AttributesColumns extends DataColumn {

    /**
     * @var array model attributes loaded with getEavAttributes method
     */
    protected $_attributes;

    /**
     * @var array of ShopAttribute models
     */
    protected $_models;
    private $query;
    private $filterName;

    public function getFilterCellContent() {
        $get = Yii::app()->request->getParam('ShopProduct');
        if (isset($get['eav'])) {
            $selected = (!empty($get['eav'][$this->name])) ? $get['eav'][$this->name] : false;
        } else {
            $selected = false;
        }
        if (is_string($this->filter)) {
            return $this->filter;
        } elseif ($this->filter !== false && $this->filter !== null && $this->name !== null) {
            if (is_array($this->filter)) {
                return CHtml::dropDownList('ShopProduct[eav][' . $this->filterName . ']', $selected, $this->filter, array('id' => false, 'prompt' => '', 'class' => 'form-control'));
            } elseif ($this->filter === null) {
                return CHtml::textField('ShopProduct[eav][' . $this->filterName . ']', $this->name, array('id' => false, 'class' => 'form-control'));
            }
        }
    }

    /**
     * Initializes the column.
     * This method registers necessary client script for the checkbox column.
     */
    public function init() {
        $this->query = ShopAttribute::model()
                ->sorting()
                ->findByAttributes(array('name' => $this->name));
        $query = $this->query;
        if ($query) {
            $this->header = $query->title;
        }

        $this->filterName = $query->name;
        if ($query->type == $query::TYPE_YESNO) {
            $this->filter = array(
                1 => Yii::t('app', 'YES'),
                2 => Yii::t('app', 'NO')
            );
        } elseif (in_array($query->type, array($query::TYPE_SELECT_MANY, $query::TYPE_CHECKBOX_LIST, $query::TYPE_DROPDOWN, $query::TYPE_RADIO_LIST))) {
            $this->filter = Html::listData($query->options, 'id', 'value');
        } elseif (in_array($query->type, array($query::TYPE_TEXT, $query::TYPE_TEXTAREA))) {
            $get = Yii::app()->request->getParam('ShopProduct');
            if (isset($get['eav'])) {
                $selected = (!empty($get['eav'][$this->name])) ? $get['eav'][$this->name] : false;
            } else {
                $selected = null;
            }
            $this->filter = Html::textField('ShopProduct[eav][' . $this->filterName . ']', $selected, array('class' => 'form-control'));
        }



        if ($this->name === null)
            throw new CException(Yii::t('zii', 'Either "name" must be specified for AttributesColumns.'));
    }

    public function getList($data) {
        $this->_attributes = $data->getEavAttributes();
        $dataResult = $this->getModels($data, false);
        $result = array();
        if ($dataResult) {
            foreach ($dataResult as $model) {
                $result['eav_'.$model->name] = array(
                    'class' => 'mod.shop.components.AttributesColumns',
                    'filter' => true,
                    //'filter' => CHtml::dropDownList('Provider[onoff]', 'onoff', Html::listData($model->options, 'id', 'value')),
                    'header' => $model->title,
                    'name' => $model->name,
                    'value' => (isset($this->_attributes[$model->name])) ? $model->renderValue($this->_attributes[$model->name]) : false
                );
            }
        }
        return $result;
    }

    protected function renderHeaderCellContent() {

        if ($this->query) {
            echo $this->query->title;
        } else {
            parent::renderHeaderCellContent();
        }
    }

    /**
     * Renders the data cell content.
     * This method renders a checkbox in the data cell.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row, $data) {
        if ($this->value !== null)
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        elseif ($this->name !== null)
            $value = CHtml::value($data, $this->name);
        else
            $value = $this->grid->dataProvider->keys[$row];


        $this->_attributes = $data->getEavAttributes();
if($this->getModels($value)){
        foreach ($this->getModels($value) as $model) {

            $dataResult[$model->name] = array(
                'title' => $model->title,
                'value' => (isset($this->_attributes[$model->name])) ? $model->renderValue($this->_attributes[$model->name]) : '<span class="label label-default">не указано</span>'
            );
        }

        if (!empty($dataResult)) {
            if (isset($dataResult[$this->name])) {
                echo $dataResult[$this->name]['value'];
            }
        }
}
    }

    protected function getModels($data, $useCondition = true) {
        //$this->_models = array();
        //$cacheId = 'product_attributes_' . strtotime($data->date_update) . '_' . $data->id;
        //$this->_models = Yii::app()->cache->get($cacheId);
        //if ($this->_models === false) {
        $cr = new CDbCriteria;
        if ($useCondition)
            $cr->addInCondition('t.name', array_keys($this->_attributes));

        $query = ShopAttribute::model()
                ->displayOnFront()
                ->sorting()
                ->findAll($cr);

        foreach ($query as $m) {
            $this->_models[$m->name] = $m;
        }
        //  Yii::app()->cache->set($cacheId, $this->_models, Yii::app()->settings->get('app', 'cache_time'));
        // }
        return $this->_models;
    }

}
