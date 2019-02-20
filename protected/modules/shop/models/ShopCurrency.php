<?php

/**
 * This is the model class for table "shop_currency".
 *
 * The followings are the available columns in table 'shop_currency':
 * @property integer $id
 * @property string $name
 * @property string $iso
 * @property string $symbol
 * @property float $rate
 * @property integer $is_main
 * @property integer $is_default
 */
class ShopCurrency extends ActiveRecord
{

    const MODULE_ID = 'shop';

    public function getGridColumns()
    {
        Yii::import('mod.shop.ShopModule');
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/currency/update", "id"=>$data->id))',
            ),
            array('name' => 'rate', 'value' => '$data->rate', 'htmlOptions' => array('class' => 'text-center')),
            array('name' => 'symbol', 'value' => '$data->symbol', 'htmlOptions' => array('class' => 'text-center')),
            array(
                'name' => 'is_main',
                'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
                'value' => '$data->is_main ? Yii::t("app", "YES") : Yii::t("app", "NO")',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'is_default',
                'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
                'value' => '$data->is_default ? Yii::t("app", "YES") : Yii::t("app", "NO")',
                'htmlOptions' => array('class' => 'text-center')
            ),

            array(
                'name' => 'format_price',
                'header'=>self::t('FORMAT_PRICE'),
                'value' => '$data->getFormatPrice()',
                'htmlOptions' => array('class' => 'text-center')
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn')
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
                'hidden' => array('delete' => array(1, 3))
            ),
        );
    }

    public function getFormatPrice(){
        return number_format(10555.50, $this->penny, $this->separator_thousandth, $this->separator_hundredth);
    }
    public function getForm()
    {
        $tab = new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__
            ),
            'showErrorSummary' => false,

            'elements' => array(
                'main' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'is_main' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                0 => Yii::t('app', 'NO'),
                                1 => Yii::t('app', 'YES')
                            ),
                            'hint' => self::t('HINT_MAIN')
                        ),
                        'is_default' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                0 => Yii::t('app', 'NO'),
                                1 => Yii::t('app', 'YES')
                            ),
                            'hint' => self::t('HINT_DEFAULT')
                        ),
                        'iso' => array('type' => 'text'),
                        'symbol' => array('type' => 'text'),
                        'rate' => array(
                            'type' => 'text',
                            'hint' => self::t('HINT_RATE')
                        ),
                    )
                ),
                'format' => array(
                    'type' => 'form',
                    'title' => self::t('FORMAT_PRICE'),
                    'elements' => array(
                        'penny' => array(
                            'type' => 'dropdownlist',
                            'items' => array(0 => Yii::t('app', 'NO'), 2 => Yii::t('app', 'YES'))
                        ),
                        'separator_thousandth' => array(
                            'type' => 'dropdownlist',
                            'items' => self::fpSeparator()
                        ),
                        'separator_hundredth' => array(
                            'type' => 'dropdownlist',
                            'items' => self::fpSeparator()
                        ),
                    )
                )
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        ), $this);

        return $tab;
    }

    public static function fpSeparator()
    {
        return array(
            ' ' => self::t('SPACE'),
            ',' => self::t('COMMA'),
            '.' => self::t('DOT')
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopCurrency the static model class
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
        return '{{shop_currency}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(

            array('name, iso, symbol, rate, penny', 'required'),
            array('is_main, is_default', 'numerical', 'integerOnly' => true),
            array('rate', 'numerical'),
            array('name', 'length', 'max' => 255),
            array('iso, symbol', 'length', 'max' => 10),
            array('id, name, iso, symbol, rate, is_main, is_default', 'safe', 'on' => 'search'),
            array('separator_hundredth, separator_thousandth', 'type', 'type' => 'string'),
        );
    }

    /*  public function validateRate($attr){
      $labels = $this->attributeLabels();
      $check = User::model()->countByAttributes(array(
      $attr => $this->$attr,
      ), 't.id != :id', array(':id' => (int) $this->id));

      if ($check > 0)
      $this->addError($attr, Yii::t('usersModule.site', 'ERROR_ALREADY_USED', array('{attr}' => $labels[$attr])));
      } */

    public function afterSave()
    {
        Yii::app()->cache->delete(Yii::app()->currency->cacheKey);

        if ($this->is_default)
            ShopCurrency::model()->updateAll(array('is_default' => 0), 'id != :id', array(':id' => $this->id));

        if ($this->is_main)
            ShopCurrency::model()->updateAll(array('is_main' => 0), 'id != :id', array(':id' => $this->id));

        parent::afterSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.iso', $this->iso, true);
        $criteria->compare('t.symbol', $this->symbol, true);
        $criteria->compare('t.rate', $this->rate);
        $criteria->compare('t.is_default', $this->is_default);

        return new ActiveDataProvider($this, array('criteria' => $criteria));
    }

}
