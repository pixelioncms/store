<?php

/**
 * This is the model class for table "ShopDeliveryMethods".
 *
 * The followings are the available columns in table 'ShopDeliveryMethods':
 * @property integer $id
 * @property string $name
 * @property float $price
 * @property float $free_from
 * @property boolean $switch
 * @property string $description
 * @property integer $ordern
 */
class ShopDeliveryMethod extends ActiveRecord
{

    const MODULE_ID = 'cart';

    /**
     * @var array
     */
    public $_payment_methods;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $translateModelName = 'ShopDeliveryMethodTranslate';

    public function init()
    {
        parent::init();
        $this->_attrLabels['payment_methods'] = self::t('PAYMENT_METHODS');
    }

    public function getGridColumns()
    {
        return array(
            'name' => array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/delivery/update", "id"=>$data->id))',
            ),
            'price' => array(
                'name' => 'price',
                'value' => '$data->price'
            ),
            'free_from' => array(
                'name' => 'free_from',
                'value' => '$data->free_from'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'ext.sortable.SortableColumn')
            ),
        );
    }

    public function getForm()
    {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'elements' => array(

                'name' => array(
                    'type' => 'text',
                ),
                'switch' => array(
                    'type' => 'dropdownlist',
                    'items' => array(
                        1 => Yii::t('app', 'YES'),
                        0 => Yii::t('app', 'NO')
                    )
                ),
                'price' => array(
                    'type' => 'text',
                ),
                'free_from' => array(
                    'type' => 'text',
                ),
                'description' => array(
                    'type' => 'textarea',
                ),
                'delivery_system' => array(
                    'type' => 'dropdownlist',
                    'items' => $this->getDeliverySystemsArray(),
                    'rel' => $this->id,
                    'empty' => '---'
                ),
                '<div id="delivery_configuration"></div>',
                'payment_methods' => array(
                    'type' => 'checkboxlist',
                    'items' => Html::listData(ShopPaymentMethod::model()->findAll(), 'id', 'name'),
                ),
                '<div id="payment_configuration"></div>'

            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        ), $this);
    }

    /**
     * @return array of available payment systems. e.g array(id=>name)
     */
    public function getDeliverySystemsArray()
    {
        // Yii::import('application.modules.shop.components.payment.PaymentSystemManager');
        $result = array();

        $systems = new DeliverySystemManager;

        foreach ($systems->getSystems() as $system) {
            $result[(string)$system->id] = $system->name;
        }

        return $result;
    }

    public function getDeliverySystemClass()
    {
        if ($this->delivery_system) {
            $manager = new DeliverySystemManager;
            return $manager->getSystemClass($this->delivery_system);
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopDeliveryMethod the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{shop_delivery_method}}';
    }

    /**
     * @return array
     */
    public function scopes()
    {
        $alias = $this->getTableAlias();
        return CMap::mergeArray(array(
            'orderByPosition' => array('order' => $alias . '.ordern ASC'),
            'orderByPositionDesc' => array('order' => $alias . '.ordern DESC'),
            'orderByName' => array(
                'with' => 'dm_translate',
                'order' => 'dm_translate.name ASC'
            ),
            'orderByNameDesc' => array(
                'with' => 'dm_translate',
                'order' => 'dm_translate.name DESC'
            ),
        ), parent::scopes());
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('delivery_system', 'safe'),
            array('name', 'required'),
            array('ordern', 'numerical', 'integerOnly' => true),
            array('price, free_from', 'numerical'),
            array('switch', 'boolean'),
            array('payment_methods', 'validatePaymentMethods'),
            array('name', 'length', 'max' => 255),
            array('description', 'type', 'type' => 'string'),
            array('id, name, description, ordern', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'categorization' => array(self::HAS_MANY, 'ShopDeliveryPayment', 'delivery_id'),
            'paymentMethods' => array(self::HAS_MANY, 'ShopPaymentMethod', array('payment_id' => 'id'), 'through' => 'categorization', 'order' => 'paymentMethods.ordern'),
            'dm_translate' => array(self::HAS_ONE, 'ShopDeliveryMethodTranslate', 'object_id'),
        );
    }

    public function defaultScope()
    {
        return array(
            'order' => 'ordern DESC',
        );
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return CMap::mergeArray(array(
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'relationName' => 'dm_translate',
                'translateAttributes' => array(
                    'name',
                    'description',
                ),
            ),
        ), parent::behaviors());
    }

    /**
     * Validate payment method exists
     * @param $attr
     * @return mixed
     */
    public function validatePaymentMethods($attr)
    {
        if (!is_array($this->$attr))
            return;

        foreach ($this->$attr as $id) {
            if (ShopPaymentMethod::model()->countByAttributes(array('id' => $id)) == 0)
                $this->addError($attr, self::t('ERROR_PAYMENT'));
        }
    }

    /**
     * After save event
     */
    public function afterSave()
    {
        // Clear payment relations
        ShopDeliveryPayment::model()->deleteAllByAttributes(array('delivery_id' => $this->id));

        foreach ($this->payment_methods as $pid) {
            $model = new ShopDeliveryPayment;
            $model->delivery_id = $this->id;
            $model->payment_id = $pid;
            $model->save(false, false, false);
        }

        return parent::afterSave();
    }

    /**
     * @param $data array ids of payment methods
     */
    public function setPayment_methods($data)
    {
        $this->_payment_methods = $data;
    }

    /**
     * @return array
     */
    public function getPayment_methods()
    {
        if ($this->_payment_methods)
            return $this->_payment_methods;

        $this->_payment_methods = array();
        foreach ($this->categorization as $row)
            $this->_payment_methods[] = $row->payment_id;
        return $this->_payment_methods;
    }

    /**
     * @return string order used delivery method
     */
    public function countOrders()
    {
        // Yii::import('mod.cart.models.Order');
        return Order::model()->countByAttributes(array('delivery_id' => $this->id));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('dm_translate');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('dm_translate.name', $this->name, true);
        $criteria->compare('dm_translate.description', $this->description, true);
        $criteria->compare('t.ordern', $this->ordern);
        $criteria->compare('t.switch', $this->switch);

        //$sort = new CSort;
        // $sort->defaultOrder = $this->getTableAlias() . '.ordern DESC';

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            //  'sort' => $sort,
        ));
    }

}
