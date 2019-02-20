<?php

Yii::import('mod.shop.models.ShopCategoryTranslate');

/**
 * This is the model class for table "shop_category".
 *
 * The followings are the available columns in table 'shop_category':
 * @property string $id
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property string $name
 * @property string $image
 * @property string $seo_alias
 * @property string $full_path
 * @property string $description
 */
class ShopCategory extends ActiveRecord
{

    const MODULE_ID = 'shop';
    const route = '/shop/admin/category';

    // public $aliasPathImage = 'uploads.shop.categories';

    public $translateModelName = 'ShopCategoryTranslate';
    public $image;

    /**
     * Multilingual attrs
     */
    public $name;
    public $description;

    public $seo_product_title;
    public $seo_product_keywords;
    public $seo_product_description;

    public function getForm()
    {
        //Yii::import('ext.bootstrap.fileinput.FileInput');
        Yii::import('ext.tinymce.TinymceArea');
        return array(
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => Yii::t('ShopModule.admin', 'Общая информация'),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                            'id' => 'title',
                        ),
                        'seo_alias' => array(
                            'type' => 'text',
                            'id' => 'alias',
                            'visible' => (Yii::app()->settings->get('app', 'translate_object_url')) ? false : true
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
                                        'url' => Yii::app()->createUrl('/admin/shop/category/removeFile'), // server delete action
                                        'key' => 1,
                                        'extra' => array('id' => $this->id)
                                    )
                                ),
                            ),*/
                        ),
                    ),
                ),
                'seo_product' => array(
                    'type' => 'form',
                    'title' => Yii::t('ShopModule.admin', 'SEO product'),
                    'elements' => array(
                        'seo_product_title' => array(
                            'type' => 'text',
                        ),
                        'seo_product_keywords' => array(
                            'type' => 'text',
                        ),
                        'seo_product_description' => array(
                            'type' => 'textarea',
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
     * @return ShopCategory the static model class
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
        return '{{shop_category}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, seo_alias', 'required'),
            array('image', 'FileValidator', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            array('seo_alias', 'TranslitValidator', 'translitAttribute' => 'name'),
            array('name, seo_alias', 'length', 'max' => 255),
            array('description, seo_alias, seo_product_title, seo_product_keywords, seo_product_description,', 'type', 'type' => 'string'),
            // Search
            array('id, name, seo_alias', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors()
    {

        return CMap::mergeArray(array(
            'upload' => array(
                'class' => 'app.behaviors.UploadfileBehavior',
                'attributes' => array('image'),
                'dir' => 'categories'
            ),
            'seo' => array(
                'class' => 'mod.seo.components.SeoBehavior',
                'url' => $this->getUrl()
            ),
            'NestedSetBehavior' => array(
                'class' => 'app.behaviors.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
            ),
            'MenuArrayBehavior' => array(
                'class' => 'app.behaviors.MenuArrayBehavior',
                'labelAttr' => 'name',
                // 'countProduct'=>false,
                'urlExpression' => 'array("/shop/category/view", "seo_alias"=>$model->full_path)',
            ),
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'relationName' => 'cat_translate',
                'translateAttributes' => array(
                    'name',
                    'description',
                    'seo_product_title',
                    'seo_product_keywords',
                    'seo_product_description',
                ),
            ),
        ), parent::behaviors());
    }

    /**
     * Find category by url.
     * Scope.
     *
     * @param string $url
     * @param string $alias
     * @return ShopProduct
     */
    public function withUrl($url, $alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias . '.seo_alias=:url',
            'params' => array(':url' => $url)
        ));
        return $this;
    }

    /**
     * Find category by url.
     * Scope.
     *
     * @param string $url
     * @param string $alias
     * @return ShopProduct
     */
    public function withFullPath($url, $alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias . '.full_path=:url',
            'params' => array(':url' => $url)
        ));
        return $this;
    }

    /**
     * @param $alias
     * @return ShopCategory
     */
    public function excludeRoot($alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias . '.id != 1',
        ));
        return $this;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return CMap::mergeArray(array(
            'countProducts' => array(self::STAT, 'ShopProductCategoryRef', 'category', 'condition' => '`t`.`switch`=1'),
            'manufacturer' => array(self::HAS_MANY, 'ShopManufacturer', 'cat_id'),
            'products' => array(self::MANY_MANY, 'ShopProduct', Yii::app()->db->tablePrefix . 'shop_product_category_ref(product, category)'), //array('product' => 'category')
            'cat_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
        ), parent::relations());
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $this->_attrLabels = array(
            'name' => Yii::t('ShopModule.default', 'Название'),
            'full_path' => Yii::t('ShopModule.default', 'Полный путь'),
            'description' => Yii::t('ShopModule.default', 'Описание'),
        );
        return CMap::mergeArray($this->_attrLabels, parent::attributeLabels());
    }

    public function beforeSave()
    {
        if (empty($this->seo_alias)) {
            // Create slug
            // Yii::import('ext.SlugHelper.SlugHelper');
            // $this->seo_alias = SlugHelper::run($this->name);
        }

        // Check if url available
        if ($this->isNewRecord) {
            $test = ShopCategory::model()
                ->withUrl($this->seo_alias)
                ->count();
        } else {
            $test = ShopCategory::model()
                ->withUrl($this->seo_alias)
                ->count('id!=:id', array(':id' => $this->id));
        }

        // Create unique url
        if ($test > 0)
            $this->seo_alias .= '-' . date('YmdHis');

        $this->rebuildFullPath();

        return parent::beforeSave();
    }

    public function afterDelete()
    {
        //Remove all products with this category set as main.
        $products = ShopProductCategoryRef::model()->findAllByAttributes(array(
            'category' => $this->id,
            'is_main' => '1'
        ));

        foreach ($products as $p) {
            $productModel = ShopProduct::model()->findByPk($p->product);
            if ($productModel)
                $productModel->delete();
        }

        // Remove all category-product relations
        ShopProductCategoryRef::model()->deleteAllByAttributes(array(
            'category' => $this->id,
            'is_main' => '0'
        ));


        $this->clearRouteCache();

        return parent::afterDelete();
    }

    public function afterSave()
    {
        $this->clearRouteCache();

        return parent::afterSave();
    }

    /**
     * Rebuild category full_path
     */
    public function rebuildFullPath()
    {
        // Create category full path.
        $ancestors = $this->ancestors()->language(Yii::app()->languageManager->active->code)->findAll();
        if (sizeof($ancestors)) {
            // Remove root category from path
            unset($ancestors[0]);

            $parts = array();
            foreach ($ancestors as $ancestor)
                $parts[] = $ancestor->seo_alias;

            $parts[] = $this->seo_alias;
            $this->full_path = implode('/', array_filter($parts));
        }

        return $this;
    }

    /**
     * @return array
     */
    public static function flatTree()
    {
        $result = array();
        $categories = ShopCategory::model()
            ->published()
            ->language(Yii::app()->languageManager->active->code)
            ->findAll(array('order' => 'lft'));
        array_shift($categories);

        foreach ($categories as $c) {

            if ($c->level > 2) {
                $result[$c->id] = str_repeat('--', $c->level - 1) . ' ' . $c->name;
            } else {
                $result[$c->id] = ' ' . $c->name;
            }
        }

        return $result;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('cat_translate');

        $criteria->compare('id', $this->id, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('cat_translate.name', $this->name, true);
        $criteria->compare('cat_translate.seo_alias', $this->seo_alias, true);
        $criteria->compare('seo_alias', $this->name, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = Yii::app()->createUrl('/shop/category/view', array('seo_alias' => $this->full_path));
        return urldecode($url);
    }

    public function clearRouteCache()
    {
        Yii::app()->cache->delete('ShopCategoryUrlRule');
    }

    public function keywords()
    {
        return $this->replaceMeta(Yii::app()->settings->get('shop', 'auto_gen_cat_tpl_keywords'));
    }

    public function description()
    {
        return $this->replaceMeta(Yii::app()->settings->get('shop', 'auto_gen_cat_tpl_description'));
    }

    public function title()
    {
        return $this->replaceMeta(Yii::app()->settings->get('shop', 'auto_gen_cat_tpl_title'));
    }

    public function replaceMeta($text)
    {
        $replace = array(
            "{category_name}" => $this->name,
            "{sub_category_name}" => ($this->parent()->find()->name == 'root') ? '' : $this->parent()->find()->name,
            "{current_currency}" => Yii::app()->currency->active->symbol,
        );
        return CMS::textReplace($text, $replace);
    }

}
