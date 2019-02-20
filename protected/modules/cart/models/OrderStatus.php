<?php

/**
 * This is the model class for table "OrderStatus".
 *
 * The followings are the available columns in table 'OrderStatus':
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property integer $ordern
 */
class OrderStatus extends ActiveRecord {

    const MODULE_ID = 'cart';
    public $disallow_delete = array(1,2,3,4,5,6,7);
    public $disallow_update = array(1,2,3,4,5,6,7);
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OrderStatus the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getForm() {
        Yii::import('ext.colorpicker.ColorPicker');
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'name' => array(
                    'type' => 'text',
                ),
                'color' => array(
                    'type' => 'ColorPicker',
                    'mode' => 'textfield',
                    'fade' => false,
                    'slide' => false,
                    'curtain' => true,
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{order_status}}';
    }

    /**
     * @return array
     */
    public function scopes() {
        $alias = $this->getTableAlias();
        return array(
            'orderByPosition' => array('order' => $alias . '.ordern ASC'),
            'orderByPositionDesc' => array('order' => $alias . '.ordern DESC'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
            array('name, color', 'unique'),
            array('ordern', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('color', 'length', 'min' => 7, 'max' => 7),
            array('color', 'match', 'pattern'=>'/#([a-f0-9]{6}){1,2}\b/i'),
            array('id, name, color, ordern', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return bool
     */
    public function countOrders() {
        return Order::model()->countByAttributes(array('status_id' => $this->id));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('color', $this->color);
        $criteria->compare('ordern', $this->ordern);

        $sort = new CSort;
        $sort->defaultOrder = $this->getTableAlias() . '.ordern DESC';

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
    }

}
