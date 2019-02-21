<?php

/**
 * Shop product types
 * This is the model class for table "shop_product_type".
 *
 * The followings are the available columns in table 'shop_product_type':
 * @property integer $id
 * @property string $name
 * @property string $categories_preset
 * @property int $main_category preset
 */
class ShopProductType extends ActiveRecord
{

    const MODULE_ID = 'shop';
    const route = '/shop/admin/productType';

    public function getGridColumns()
    {
        Yii::import('mod.shop.ShopModule');
        return array(
            'name' => array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/productType/update", "id"=>$data->id))',
            ),
            'attributes' => array(
                'header' => self::t('ATTRIBUTES'),
                'type' => 'raw',
                'value' => '$data->getAttributesGrid()',
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn'),
            ),
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{shop_product_type}}';
    }

    public function getAttributesGrid()
    {
        $content = '';
        if ($this->shopAttributes) {
            foreach ($this->shopAttributes as $s) {
                //print_r($s->title);
                $link = Html::link($s->title, array('/admin/shop/attribute/update', 'id' => $s->id));
                $content .= Html::tag('span', array('class' => 'badge badge-secondary'), $link, true) . ' ';
            }
        }
        //return implode(' ', $content);
        return $content;
    }

    public function scopes()
    {
        $alias = $this->getTableAlias(true);
        return array(
            'orderByName' => array('order' => $alias . '.name'),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'attributeRelation' => array(self::HAS_MANY, 'ShopTypeAttribute', 'type_id'),
            'shopAttributes' => array(self::HAS_MANY, 'ShopAttribute', array('attribute_id' => 'id'), 'through' => 'attributeRelation', 'scopes' => 'applyTranslateCriteria'),
            'shopConfigurableAttributes' => array(self::HAS_MANY, 'ShopAttribute', array('attribute_id' => 'id'), 'through' => 'attributeRelation', 'condition' => 'use_in_variants=1'),
            'productsCount' => array(self::STAT, 'ShopProduct', 'type_id'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Clear and set type attributes
     * @param $attributes array of attributes id. array(1,3,5)
     * @return mixed
     */
    public function useAttributes($attributes)
    {
        // Clear all relations
        ShopTypeAttribute::model()->deleteAllByAttributes(array('type_id' => $this->id));

        if (empty($attributes))
            return false;

        foreach ($attributes as $attribute_id) {
            if ($attribute_id) {
                $record = new ShopTypeAttribute;
                $record->type_id = $this->id;
                $record->attribute_id = $attribute_id;
                $record->save(false, false, false);
            }
        }
    }

    public function afterDelete()
    {
        // Clear type attribute relations
        ShopTypeAttribute::model()->deleteAllByAttributes(array('type_id' => $this->id));
        return parent::afterDelete();
    }

    public function __toString()
    {
        return $this->name;
    }

}