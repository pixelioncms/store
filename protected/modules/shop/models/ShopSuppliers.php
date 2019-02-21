<?php

class ShopSuppliers extends ActiveRecord {

    const MODULE_ID = 'shop';

    public function getForm() {
        Yii::import('app.widgets.intl-tel-input.TelInputWidget');
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'elements' => array(
                'name' => array(
                    'type' => 'text',
                ),
                'phone' => array(
                    'type' => 'TelInputWidget',
                ),
                'email' => array(
                    'type' => 'text',
                    'beforeContent' => '<i class="icon-envelope"></i>',
                ),
                'address' => array(
                    'type' => 'textarea',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            ),
                ), $this);
    }

    public function getGridName(){
        return Html::link(Html::encode($this->name), array("/shop/admin/suppliers/update", "id"=>$this->id)).'<br/>'.$this->address;
    }
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopProductType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_suppliers}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
            array('email', 'email'),
            array('name, email, phone', 'length', 'max' => 255),
            array('address, email, phone', 'type', 'type' => 'string'),
            array('id, name, address, email, phone', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('address', $this->address, true);

        return new ActiveDataProvider($this, array('criteria' => $criteria));
    }

}
