<?php

Yii::import('mod.shop.models.ShopAttributeGroupsTranslate');

/**
 * This is the model class for table "ShopAttributeGroups".
 *
 * The followings are the available columns in table 'ShopAttributeGroups':
 * @property integer $id
 * @property string $name
 */
class ShopAttributeGroups extends ActiveRecord {

    /**
     * @var string attr name
     */
    public $name;

    /**
     * @var string
     */
    public $translateModelName = 'ShopAttributeGroupsTranslate';

    const MODULE_ID = 'shop';

    public function getGridColumns() {
        Yii::import('mod.shop.ShopModule');
        return array(
            array('name' => 'name', 'value' => '$data->name'),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'ext.sortable.SortableColumn')
            ),
        );
    }

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'name' => array(
                    'type' => 'text',
                    'hint' => self::t('HINT_NAME')
                ),
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
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopAttribute the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_attribute_groups}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
            array('ordern', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array

      public function defaultScope() {
      $t = $this->getTableAlias();
      return array(
      'sorting' => 'ordern ASC',
      );
      } */

    /**
     * @return array
     */
    public function scopes() {
        $t = $this->getTableAlias();
        return CMap::mergeArray(array(
            'sorting' => array('order' => 'ordern DESC'),
        ),parent::scopes());
    }

    /**
     * @return array
     */
    public function behaviors() {
        return CMap::mergeArray(array(
                    'TranslateBehavior' => array(
                        'class' => 'app.behaviors.TranslateBehavior',
                        'relationName' => 'attrgroup_translate',
                        'translateAttributes' => array(
                            'name',
                        ),
                    ),
                        ), parent::behaviors());
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'attrgroup_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'attr' => array(self::HAS_MANY, 'ShopAttribute', 'id'),
        );
    }
    public function defaultScope2() {
               // $t = $this->getTableAlias();
        return array(
            'order' => 'ordern ASC',
        );
    }
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('attrgroup_translate');

        $criteria->compare('`t`.`id`', $this->id);
        $criteria->compare('`attrgroup_translate`.`name`', $this->name, true);
      //  $criteria->compare('`t`.`ordern`', $this->ordern);
        //$criteria->scopes = array('sorting');
        $sort = new CSort;
       // $sort->defaultOrder = '`t`.`ordern` ASC';
        $sort->attributes = array(
            '*',
            'name' => array(
                'asc' => '`attrgroup_translate`.`name`',
                'desc' => '`attrgroup_translate`.`name` DESC',
            ),
        );

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
    }

}
