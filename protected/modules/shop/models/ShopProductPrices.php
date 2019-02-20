<?php

/**
 * Class
 *
 * @property int $id
 */
class ShopProductPrices extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{shop_product_prices}}';
    }

    public function rules()
    {
        return array(
            array('value', 'commaToDot'),
            array('order_from, value', 'required'),
            array('order_from', 'numerical', 'integerOnly' => true),
            array('order_from', 'safe', 'on' => 'search'),
        );
    }

    public function commaToDot($attr)
    {
        $this->{$attr} = str_replace(',', '.', $this->{$attr});
    }

    public function attributeLabels()
    {
        return array(
            'value' => Yii::t('ShopModule.ShopProduct', 'PRICE'),
            'order_from' => Yii::t('ShopModule.ShopProduct', 'ORDER_FROM')
        );
    }

}