<?php

Yii::import('mod.shop.models.ShopManufacturerTranslate');

/**
 * This is the model class for table "shop_manufacturer".
 *
 * The followings are the available columns in table 'shop_manufacturer':
 * @property integer $id
 * @property string $name
 * @property string $seo_alias
 * @property string $description
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @method ShopManufacturer orderByName()
 */
class ShopManufacturer extends ActiveRecord {

    const MODULE_ID = 'shop';
    const route = '/shop/admin/manufacturer';

    public $translateModelName = 'ShopManufacturerTranslate';
    public $image;

    /**
     * Multilingual attrs
     */
    public $name;
    public $description;

    public function getGridColumns() {
        $columns = array();

        $columns[] = array(
            'header' => self::t('IMAGE'),
            'type' => 'raw',
            'filter' => false,
            'htmlOptions' => array('class' => 'image text-center'),
            'value' => '$data->renderGridImage()',
        );
        $columns[] = array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->name), array("/shop/manufacturer/index", "seo_alias"=>$data->seo_alias))',
        );
        $columns[] = array(
            'header' => self::t('PRODUCTS_COUNT'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'Html::link($data->productsCount,array("/admin/shop/products","ShopProduct[manufacturer_id]"=>$data->id))',
        );
        $columns['DEFAULT_CONTROL'] = array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        );

        $columns['DEFAULT_COLUMNS'][] = array('class' => 'CheckBoxColumn');
        if (Yii::app()->user->openAccess(array("Shop.Manufacturer.*", "Shop.Manufacturer.Sortable"))) {
            $columns['DEFAULT_COLUMNS'][] = array('class' => 'ext.sortable.SortableColumn');
        }
        return $columns;
    }

    public function getForm() {
        Yii::import('ext.tinymce.TinymceArea');
       // Yii::import('ext.bootstrap.fileinput.FileInput');
        return array(
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL_INFO'),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text', 'id' => 'title',
                        ),
                        'seo_alias' => array(
                            'type' => 'text', 'id' => 'alias',
                            'visible' => (Yii::app()->settings->get('app', 'translate_object_url')) ? false : true
                        ),
                        'cat_id' => array(
                            'type' => 'dropdownlist',
                            'items' => ShopCategory::flatTree(),
                            'empty' => '---',
                        ),
                        'description' => array(
                            'type' => 'TinymceArea',
                        ),
                    ),
                ),
                'image' => array(
                    'type' => 'form',
                    'title' => Yii::t('ShopModule.admin', 'Изображение'),
                    'elements' => array(
                        'image' => array(
                            'type' => 'file',
                            'hint' => $this->getFileHtmlButton('image')
                            /*'options' => array(
                                'showUpload' => false,
                                'showPreview' => true,
                                'overwriteInitial' => true,
                                'showRemove' => false,
                                'showClose' => false,
                                'showCaption' => false,
                                'browseLabel' => '',
                                'removeLabel' => '',
                                'elErrorContainer' => '#kv-avatar-errors',
                                'msgErrorClass' => 'alert alert-danger',
                                'initialPreview' => $this->getInitialPreview(),
                                'allowedFileExtensions' => array("jpg", "png", "gif"),
                                'initialPreviewConfig' => array(
                                    array(
                                        'width' => '120px',
                                        'url' => Yii::app()->createUrl('/admin/shop/manufacturer/removeFile'), // server delete action
                                        'key' => 1,
                                        'extra' => array('id' => $this->id)
                                    )
                                ),
                            ),*/
                        ),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopManufacturer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_manufacturer}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('image', 'FileValidator', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            array('name, seo_alias', 'required'),
            array('seo_alias', 'TranslitValidator', 'translitAttribute' => 'name'), //LocalUrlValidator
            array('ordern, cat_id', 'numerical'),
            array('description', 'type', 'type' => 'string'),
            array('name, seo_alias', 'length', 'max' => 255),
            array('id, name, seo_alias, description, ordern', 'safe', 'on' => 'search'),
        );
    }


    public function renderGridImage() {
        return (!empty($this->image)) ? Html::link(Html::image($this->getImageUrl("50x50"), $this->name, array('class' => 'img-thumbnail')), $this->getOriginalImageUrl()) : Html::link(Html::image(CMS::placeholderUrl(array('size' => '50x50')), "", array('class' => 'img-thumbnail')), CMS::placeholderUrl(array('size' => '500x500')));
    }

    /**
     * Find manufacturer by url.
     * Scope.
     * @param string $seo_alias
     * @return ShopProduct
     */
    public function withUrl($seo_alias) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $seo_alias)
        ));
        return $this;
    }

    /**
     * Find manufacturer only image.
     * Scope.
     * @return ShopManufacturer
     */
    public function onlyImage() {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'image!=""',
        ));
        return $this;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return CMap::mergeArray(array(
                    'man_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
                    'productsCount' => array(self::STAT, 'ShopProduct', 'manufacturer_id', 'select' => 'count(t.id)'),
                        ), parent::relations());
    }

    public function scopes() {
        return CMap::mergeArray(array(
                    'orderByName' => array(
                        'order' => 'man_translate.name'
                    ),
                        ), parent::scopes());
    }

    /**
     * @return array
     */
    public function behaviors() {
        return CMap::mergeArray(array(
                    'seo' => array(
                        'class' => 'mod.seo.components.SeoBehavior',
                        'url' => $this->getUrl()
                    ),
                    'upload' => array(
                        'class' => 'app.behaviors.UploadfileBehavior',
                        'attributes' => array('image'),
                        'dir' => 'manufacturer'
                    ),
                    'TranslateBehavior' => array(
                        'class' => 'app.behaviors.TranslateBehavior',
                        'relationName' => 'man_translate',
                        'translateAttributes' => array(
                            'name',
                            'description',
                        ),
                    ),
                        ), parent::behaviors());
    }

    public function afterDelete() {
        // Clear product relations
        ShopProduct::model()->updateAll(array(
            'manufacturer_id' => new CDbExpression('NULL'),
                ), 'manufacturer_id = :id', array(':id' => $this->id));

        return parent::afterDelete();
    }

    /**
     * @return string
     */
    public function getUrl() {
        $url = Yii::app()->createUrl('/shop/manufacturer/view', array('seo_alias' => $this->seo_alias));
        return urldecode($url);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('man_translate');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.ordern', $this->ordern);
        $criteria->compare('man_translate.name', $this->name, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('man_translate.description', $this->description, true);

        $sort = new CSort;
        $sort->attributes = array(
            '*',
            'name' => array(
                'asc' => 'man_translate.name',
                'desc' => 'man_translate.name DESC',
            ),
        );

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
    }

}
