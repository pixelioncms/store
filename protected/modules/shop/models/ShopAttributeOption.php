<?php

Yii::import('mod.shop.models.ShopAttributeOptionTranslate');

/**
 * Shop options for dropdown and multiple select
 * This is the model class for table "ShopAttributeOptions".
 *
 * The followings are the available columns in table 'ShopAttributeOptions':
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 * @property integer $position
 */
class ShopAttributeOption extends ActiveRecord
{

    public $translateModelName = 'ShopAttributeOptionTranslate';

    /**
     * @var string multilingual attr
     */
    public $value;
    public $spec;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CActiveRecord the static model class
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
        return '{{shop_attribute_option}}';
    }

    public function relations()
    {
        return array(
            'option_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'productsCount' => array(self::STAT, 'ShopProductAttributesEav', 'value'),
            'attribute' => array(self::BELONGS_TO, 'ShopAttribute', 'attribute_id'),
            //'productsCount' => array(self::STAT, 'ShopProductAttributesEav', array('value'=>'id')),
        );
    }

    public function rules()
    {
        return array(
            array('id, value, attribute_id, ordern', 'safe', 'on' => 'search'),
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
                'relationName' => 'option_translate',
                'translateAttributes' => array(
                    'value',
                ),
            ),
            'timezone' => array(
                'class' => 'app.behaviors.TimezoneBehavior',
                'attributes' => array('date_create'),
            )
        ), parent::behaviors());
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            // Записываем в кеш данные об атрибуте
            // чтобы в EEavBehavior избавится от не нужных данных в запросах.
            Yii::app()->cache->delete("attribute_" . $this->attribute->name);

            $options = Yii::app()->cache->get("attribute_" . $this->attribute->name);
            if ($options === false) {
                $options[$this->attribute->name] = array();
                if ($this->attribute->options) {
                    foreach ($this->attribute->options as $option) {
                        $options[$this->attribute->name][] = $option->id;
                    }
                }
                Yii::app()->cache->set("attribute_" . $this->attribute->name, $options);
            }
            return true;
        }
    }


    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('option_translate');

        $criteria->compare('`t`.`id`', $this->id);
        $criteria->compare('`option_translate`.`value`', $this->value, true);
        $criteria->compare('`t`.`ordern`', $this->ordern);
        if (isset($_GET['id'])) {
            $criteria->compare('`t`.`attribute_id`', $_GET['id']);
        }
        $sort = new CSort;
        $sort->defaultOrder = '`t`.`ordern` ASC';
        $sort->attributes = array(
            '*',
            'value' => array(
                'asc' => '`option_translate`.`value`',
                'desc' => '`option_translate`.`value` DESC',
            ),
        );

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
    }

}
