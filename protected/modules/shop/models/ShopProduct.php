<?php

Yii::import('mod.shop.models.ShopProductTranslate');
Yii::import('mod.shop.models.ShopProductCategoryRef');

/**
 * This is the model class for table "ShopProduct".
 *
 * The followings are the available columns in table 'ShopProduct':
 * @property integer $id
 * @property integer $manufacturer_id
 * @property boolean $use_configurations
 * @property array $configurations array of product pks
 * @property array $configurable_attributes array of ShopAttribute pks used to configure product
 * @property integer $type_id
 * @property string $name
 * @property string $seo_alias
 * @property float $price Product price. For configurable product its min_price
 * @property float $max_price for configurable products. Used in ShopProduct::priceRange to display prices on category view
 * @property boolean $switch
 * @property string $short_description
 * @property string $full_description
 * @property string $sku
 * @property string $quantity
 * @property string $auto_decrease_quantity
 * @property string $availability
 * @property integer $views
 * @property integer $added_to_cart_count
 * @property string $date_create
 * @property string $date_update
 * @property integer $votes
 * @property integer $rating
 * @property integer $score
 * @property string $discount Скидка
 * @property string $markup Наценка
 * @method ShopProduct published() Find Only active products
 * @method ShopProduct newest() Order products by creating date
 * @method ShopProduct byViews() Order by views count
 * @method ShopProduct byAddedToCart() Order by views count
 * @method ShopProduct withEavAttributes
 */
class ShopProduct extends ActiveRecord
{


    public $enableAttachment = array('path' => 'product');

    /**
     * @var null Id if product to exclude from search
     */
    public $exclude = null;

    /**
     * @var array of related products
     */
    private $_related;

    /**
     * @var array of attributes used to configure product
     */
    private $_configurable_attributes;
    private $_configurable_attribute_changed = false;

    /**
     * @var array
     */
    private $_configurations;

    /**
     * @var string
     */
    public $translateModelName = 'ShopProductTranslate';

    /**
     * Multilingual attrs
     */
    public $name;
    public $short_description;
    public $full_description;
    // public $image;
    public $quantity = 1; //default value 
    /**
     * @var float min/max price
     */
    public $aggregation_price;

    /**
     * @var integer used only to render admin form
     */
    public $main_category_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return ShopProduct the static model class
     */
    const MODULE_ID = 'shop';
    const route = '/shop/admin/products';

    public function init()
    {
        $c = Yii::app()->settings->get('shop');

        //  $this->enableAttachment['watermark'] = $c['watermark_active'];
        // $this->enableAttachment['watermark_path'] = Yii::getPathOfAlias('webroot.uploads') . '/' . $c['watermark_image'];
        $this->enableAttachment = CMap::mergeArray($this->enableAttachment, array(
            'watermark' => $c->watermark_active,
            'watermark_offsetX' => $c->watermark_offsetX,
            'watermark_offsetY' => $c->watermark_offsetY,
            'watermark_path' => Yii::getPathOfAlias('webroot.uploads') . '/' . $c->watermark_image
        ));
        parent::init();
    }

    public function attributeLabels()
    {
        return CMap::mergeArray(array(
            'main_category_id' => self::t('MAIN_CATEGORY_ID')
        ), parent::attributeLabels());
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getUrl()
    {
        return Yii::app()->createUrl('/shop/product/view', array('seo_alias' => $this->seo_alias));
    }

    public function getProductLabel()
    {
        $result = array();
        $result['label'] = Yii::t('ShopModule.default', 'PRODUCT_LABEL', $this->label);
        switch ($this->label) {
            case 1:
                $result['class'] = 'new';
                break;
            case 2:
                $result['class'] = 'hot';
                break;
            case 3:
                $result['class'] = 'sale';
                break;
            default:
                $result = false;
        }
        return $result;
    }

    public function renderGridImage()
    {
        return ($this->attachmentsMain) ? Html::link(Html::image($this->getMainImageUrl("50x50"), $this->getMainImageTitle(), array('class' => 'img-thumbnail')), $this->getMainImageUrl(), array('title' => $this->name, 'data-fancybox' => 'gallery')) : Html::image($this->getMainImageUrl("50x50"), $this->getMainImageTitle(), array('class' => 'img-thumbnail'));
    }


    public function getGridColumns()
    {
        Yii::import('mod.shop.components.AttributesColumns');
        $attributesColumns = new AttributesColumns('shopproduct-grid');
        $attributesList = $attributesColumns->getList($this);

        $columns = array();
        $columns['id'] = array(
            'name' => 'id',
            'class' => 'IdColumn',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => false,
        );
        $columns['image'] = array(
            'name' => 'image',
            'type' => 'raw',
            'header' => 'Картинка',
            'htmlOptions' => array('class' => 'image text-center'),
            'filter' => false,
            //'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
            'value' => '$data->renderGridImage()'
        );
        $columns['name'] = array(
            'name' => 'name',
            //'class'=>'EditableColumn',
            //'inputValue'=>'$data->name',
            'type' => 'raw',
            'value' => '$data->getGridTitle()',
            'htmlOptions' => array('class' => 'text-left')
        );
        $columns['manufacturer_id'] = array(
            'class' => 'EditableColumn',
            'editable' => array(
                'type' => 'dropdownlist',
                'modelAlias' => 'mod.shop.models',
                'items' => Html::listData(ShopManufacturer::model()->published()->orderByName()->findAll(), 'id', 'name'),
                'value' => '$data->manufacturer_id',
                'url' => 'Yii::app()->controller->createUrl("/admin/shop/products/update", array("id"=>$data->primaryKey))'
            ),
            'name' => 'manufacturer_id',
            'type' => 'raw',
            'value' => '$data->manufacturer ? Html::link(Html::encode($data->manufacturer->name),$data->manufacturer->getUpdateUrl()) : ""',
            'filter' => Html::listData(ShopManufacturer::model()->published()->orderByName()->findAll(), 'id', 'name'),
            'htmlOptions' => array('class' => 'text-center')
        );
        $columns['supplier_id'] = array(
            'name' => 'supplier_id',
            'type' => 'raw',
            'value' => '$data->supplier_id ? Html::encode($data->supplier->name) : ""',
            'filter' => Html::listData(ShopSuppliers::model()->findAll(), 'id', 'name'),
            'htmlOptions' => array('class' => 'text-center')
        );
        $columns['type_id'] = array(
            'name' => 'type_id',
            'type' => 'raw',
            'value' => '$data->type_id ? Html::link(Html::encode($data->type->name),$data->type->getUpdateUrl()) : ""',
            'filter' => Html::listData(ShopProductType::model()->findAll(), 'id', 'name'),
            'htmlOptions' => array('class' => 'text-center')
        );
        $flatTree = ShopCategory::flatTree();
        $columns['main_category_id'] = array(
            'class' => 'EditableColumn',
            'editable' => array(
                'type' => 'dropdownlist',
                'modelAlias' => 'mod.shop.models',
                'items' => $flatTree,
                'value' => '$data->mainCategory->id',
                'url' => 'Yii::app()->controller->createUrl("/admin/shop/products/update", array("id"=>$data->primaryKey))'
            ),
            'type' => 'raw',
            'header' => 'Категория/и',
            'name' => 'main_category_id',
            'htmlOptions' => array('class' => 'text-center'),
            //'value' => '$data->getCategories()',
            'value' => '$data->mainCategory ? Html::link(Html::encode($data->mainCategory->name),$data->mainCategory->getUpdateUrl()) : ""',
            //'filter' => ShopCategory::flatTree()
            // 'filter' => Html::listData(ShopCategory::model()->findAll(), 'id', 'name')
            'filter' => $flatTree,
        );
        $columns['switch'] = array(
            'name' => 'switch',
            'filter' => array(1 => Yii::t('app', 'Показанные'), 0 => Yii::t('app', 'Скрытые')),
            'value' => '$data->switch ? Yii::t("app", "Показан") : Yii::t("app", "Скрыт")'
        );
        $columns['price'] = array(
            'class' => 'EditableColumn',
            'editable' => array(
                'type' => 'text',
                'modelAlias' => 'mod.shop.models',
                'value' => '$data->price',
                'url' => 'Yii::app()->controller->createUrl("/admin/shop/products/update", array("id"=>$data->primaryKey))'
            ),
            'name' => 'price',
            'type' => 'raw',
            'value' => '$data->getGridPrice()',
            'filter' => false,
            'htmlOptions' => array('class' => 'text-center')
        );
        $columns['sku'] = array(
            'class' => 'EditableColumn',
            'editable' => array(
                'type' => 'text',
                'modelAlias' => 'mod.shop.models',
            ),
            'name' => 'sku',
            'value' => '$data->sku',
            'htmlOptions' => array('class' => 'text-center')
        );
        $columns['date_create'] = array(
            'name' => 'date_create',
            'value' => 'CMS::date($data->date_create)',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => Yii::app()->controller->widget('app.jui.JuiDatePicker',
                array(
                    'model' => $this,
                    'attribute' => 'date_create',
                    // 'language' => 'pl',
                    // 'i18nScriptFile' => 'jquery.ui.datepicker-ja.js',
                    'htmlOptions' => array(
                        'id' => 'Product_date_create',
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'options' => array(  // (#3)
                        'showOn' => 'focus',
                        'dateFormat' => 'yy-mm-dd',
                        'showOtherMonths' => true,
                        'selectOtherMonths' => true,
                        'changeMonth' => true,
                        'changeYear' => true,
                        'showButtonPanel' => true,
                    )
                ),
                true),
        );
        $columns['date_update'] = array('name' => 'date_update', 'value' => 'CMS::date($data->date_update)', 'htmlOptions' => array('class' => 'text-center'));
        $columns['quantity'] = array(
            // 'class' => array(),
            'class' => 'EditableColumn',
            'editable' => array(
                'type' => 'text',
                'modelAlias' => 'mod.shop.models'
            ),
            'name' => 'quantity',
            'value' => '$data->quantity',
            'htmlOptions' => array('class' => 'text-center')
        );

        foreach ($attributesList as $at) {
            $columns['eav_' . $at['name']] = array(
                'class' => $at['class'],
                'name' => $at['name'],
                'header' => '<b>(Атрибут)</b> ' . $at['header'],
                'htmlOptions' => array('class' => 'text-center')
            );
        }


        $columns['DEFAULT_CONTROL'] = array(
            'class' => 'ButtonColumn',
            //  'group' => true,
            'template' => '{switch}{update}{delete}',
        );

        $columns['DEFAULT_COLUMNS'][] = array('class' => 'CheckBoxColumn');
        if (Yii::app()->user->openAccess(array("Shop.Products.*", "Shop.Products.Sortable"))) {
            $columns['DEFAULT_COLUMNS'][] = array('class' => 'ext.sortable.SortableColumn');
        }

        return $columns;
    }

    public function getLabelData()
    {
        $result = array();
        $new = (int)Yii::app()->settings->get('shop', 'label_new_days');
        if (isset($this->label[0])) {
            $result[] = array(
                'class' => 'success new',
                'value' => Yii::t('ShopModule.default', 'PRODUCT_LABEL_NEW'),
                'tooltip' => ''
            );
        } else {
            if (CMS::time() - (86400 * $new) <= strtotime($this->date_create)) {
                $result[] = array(
                    'class' => 'success new',
                    'value' => 'Новый',
                    'tooltip' => 'от ' . date('Y-m-d', strtotime($this->date_create)) . ' до ' . date('Y-m-d', CMS::time() - (86400 * 7))
                );
            }
        }
        if (isset($this->label[1])) {
            $result[] = array(
                'class' => 'danger discount',
                'value' => Yii::t('ShopModule.default', 'PRODUCT_LABEL_DISCOUNT'),
                'tooltip' => ''
            );
        } else {
            if (isset($this->appliedDiscount)) {
                $result[] = array(
                    'class' => 'danger discount',
                    'value' => '-' . $this->discountSum,
                    'tooltip' => '-' . $this->discountSum . ' до ' . $this->discountEndDate
                );
            }
        }
        if (isset($this->label[2])) {
            $result[] = array(
                'class' => 'warning hot',
                'value' => Yii::t('ShopModule.default', 'PRODUCT_LABEL_HOT'),
                'tooltip' => ''
            );
        }
        return $result;
    }


    public function getGridTitle()
    {
        $lab = '';
        foreach ($this->getLabelData() as $label) {
            $lab .= "<span class=\"badge badge-{$label['class']}\" data-toggle=\"tooltip\" title=\"{$label['tooltip']}\">{$label['value']}</span>";
        }
        // return $lab . Html::link(Html::encode($this->name), $this->getUpdateUrl());
        return Html::link(Html::encode($this->name), $this->getUpdateUrl()) . " (просм. {$this->views})<br/>" . $lab;
    }

    public function getGridPrice()
    {
        $discountPrice = '';
        if ($this->currency_id) {
            $price = $this->getFrontPrice();
            if ($this->appliedDiscount) {
                $discountPrice = ' / <s class="text-danger">' . Yii::app()->currency->number_format(Yii::app()->currency->convert($this->originalPrice, $this->getCurrencyBy())) . '</s>';
            }
        } else {
            $price = $this->getFrontPrice();
            if ($this->appliedDiscount) {
                $discountPrice = ' / <s class="text-danger">' . Yii::app()->currency->number_format(Yii::app()->currency->convert($this->originalPrice, $this->getCurrencyBy())) . '</s>';
            }
        }
        return '<span class="text-success">' . Yii::app()->currency->number_format($price) . '</span> ' . $discountPrice . ' <sup style="font-size:11px;">' . Yii::app()->currency->active->symbol . '</sup>';
    }

    public function getCurrencyBy($param = 'id')
    {
        if ($this->currency_id) {
            return Yii::app()->currency->currencies[$this->currency_id]->$param;
        } else {
            if ($this->manufacturer) {
                if ($this->manufacturer->currency_id) {
                    return Yii::app()->currency->currencies[$this->manufacturer->currency_id]->$param;
                }
            }
            return Yii::app()->currency->active->$param;
        }
    }


    public function beginCartForm()
    {

        $form = Html::form(array('/cart/add'), 'post', array('id' => 'form-add-cart-' . $this->id));
        $form .= Html::hiddenField('product_id', $this->id, array('id' => 'product_id-' . $this->id));
        $form .= Html::hiddenField('product_price', $this->price, array('id' => 'product_price-' . $this->id));
        $form .= Html::hiddenField('use_configurations', $this->use_configurations, array('id' => 'use_configurations-' . $this->id));
        $form .= Html::hiddenField('currency_rate', Yii::app()->currency->active->rate, array('id' => 'currency_rate-' . $this->id));
        //echo Html::hiddenField('currency_id', $model->currency_id);
        //echo Html::hiddenField('supplier_id', $model->supplier_id);
        $form .= Html::hiddenField('configurable_id', 0, array('id' => false)); //,array('id'=>'configurable_id-'.$this->id)

        return $form;
    }

    public function endCartForm()
    {
        return Html::endForm();
    }

    public function getForm()
    {
        Yii::import('app.jui.JuiDatePicker');
        Yii::import('ext.tinymce.TinymceArea');
        return array(
            //'positionTabs' => 'vertical',
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text', 'id' => 'title',
                            'visible' => (!empty(Yii::app()->settings->get('shop', 'auto_gen_product_title'))) ? false : true
                        ),
                        'seo_alias' => array(
                            'type' => 'text', 'id' => 'alias',
                            'visible' => (!empty(Yii::app()->settings->get('shop', 'auto_gen_product_title'))) ? false : true
                        ),
                        'sku' => array(
                            'type' => 'text',
                        ),
                        /* 'price' => array(
                             'type' => $this->use_configurations ? 'hidden' : 'text',
                             'afterContent'=>'&nbsp;&nbsp;<span class="col-form-label">Валюта</span>&nbsp;&nbsp;'.Html::activeDropDownList(
                                 $this,
                                 'currency_id',
                                 Html::listData(ShopCurrency::model()->findAll(array('condition' => '`t`.`is_default`=:int', 'params' => array(':int' => 0))), 'id', 'name'),
                                 array('class'=>'form-control','empty' => ''.Yii::app()->currency->main->symbol.' (по умолчанию)')
                             ).'&nbsp;&nbsp;<span class="col-form-label">за</span>&nbsp;&nbsp;'.Html::activeDropDownList(
                                     $this,
                                     'unit',
                                     $this->getUnits(),
                                     array('class'=>'form-control')
                                 ),
                             'hint'=>Html::link(Html::icon('icon-add').' Добавить цену','#',array('class'=>'btn btn-link btn-sm btn-success2','id'=>'add-price'))
                         ),*/
                        Yii::app()->controller->renderPartial('_prices', array('model' => $this), true, false),
                        'price_purchase' => array(
                            'type' => 'text',
                        ),
                        /*'label' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getProductLabels(),
                            'empty' => Yii::t('app', 'EMPTY_LIST', 1)
                        ),*/
                        'label' => array(
                            'type' => 'checkboxlist',
                            'items' => self::getProductLabels(),
                        ),
                        /*'currency_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopCurrency::model()->findAll(array('condition' => '`t`.`is_default`=:int', 'params' => array(':int' => 0))), 'id', 'name'),
                            'empty' => '&mdash; Не привязывать &mdash;',
                            'visible' => true
                        ),*/
                        'main_category_id' => array(
                            'type' => 'dropdownlist',
                            'items' => ShopCategory::flatTree(),
                            'empty' => Yii::t('app', 'EMPTY_LIST', 1)
                        ),
                        //'dasdsa',
                        'manufacturer_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopManufacturer::model()->findAll(), 'id', 'name'),
                            'empty' => self::t('EMPTY_MANUFACTURER'),
                        ),
                        'supplier_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopSuppliers::model()->findAll(), 'id', 'name'),
                            'empty' => self::t('EMPTY_SUPPLIERS'),
                            'visible' => false
                        ),
                        /* 'short_description' => array(
                          'type' => 'textarea',
                          'class' => 'editor',
                          'hint' => (Yii::app()->settings->get('shop', 'auto_fill_short_desc')) ? Yii::t('ShopModule.admin', 'MODE_ENABLE', array(
                          '{mode}' => Yii::t('ShopModule.SettingsShopForm', 'AUTO_FILL_SHORT_DESC')
                          )) : null
                          ), */
                        'short_description' => array(
                            'type' => 'TinymceArea',
                        ),
                        'full_description' => array(
                            'type' => 'TinymceArea',
                        ),
                    ),
                ),
                'warehouse' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_WAREHOUSE'), //'icon-drawer-3',
                    'elements' => array(
                        'quantity' => array(
                            'type' => 'text',
                        ),
                        'discount' => array(
                            'type' => 'text',
                            'hint' => self::t('HINT_DISCOUNT'),
                        ),
                        'auto_decrease_quantity' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                0 => Yii::t('app', 'NO'),
                                1 => Yii::t('app', 'YES')
                            ),
                            'hint' => self::t('HINT_AUTO_DECREASE_QUANTITY'),
                        ),
                        'availability' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getAvailabilityItems()
                        ),
                        'archive' => array(
                            'type' => 'checkbox'
                        )
                    ),
                ),
                'other' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_OTHER'),
                    'elements' => array(
                        'ordern' => array('type' => 'text'),
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                1 => Yii::t('app', 'YES'),
                                0 => Yii::t('app', 'NO')
                            ),
                            'visible' => Yii::app()->user->openAccess(array("Shop.Products.*", "Shop.Products.Switch")),
                            'hint' => self::t('HINT_SWITCH'),
                        ),
                        'date_create' => array(
                            'type' => 'JuiDatePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                            'afterContent' => '<i class="icon-calendar-2"></i>'
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
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{shop_product}}';
    }

    public function getMainImageUrl($size = false)
    {
        $params = array();
        if ($size) {
            $params['size'] = $size;
        }
        if ($this->attachmentsMain) {
            $params['id'] = $this->attachmentsMain->id;
            return Yii::app()->createUrl('/core/attachment', $params);
        } else {
            return CMS::placeholderUrl($params);
        }
    }

    public function ________getMainImageUrlOld($size = '100x100')
    {
        if ($this->attachmentsMain) {
            if ($size == 'original') {
                return $this->attachmentsMain->getOriginalUrl('product');
            } else {
                return $this->attachmentsMain->getImageUrl('product', $size);
            }
        } else {
            return CMS::placeholderUrl(array('size' => $size));
        }
    }

    /**
     * @deprecated
     * @param $value
     * @return mixed
     */
    public static function formatPrice($value)
    {
        return Yii::app()->currency->number_format($value);
    }

    public function getRatingUrl()
    {
        return Yii::app()->createUrl('/shop/rating', array('id' => $this->id));
    }

    public function scopes()
    {
        $alias = $this->getTableAlias(true);
        return CMap::mergeArray(array(
            'newToDay' => array(
                'condition' => $alias . '.date_create BETWEEN :fr AND :to AND ' . $alias . '.switch=1',
                'params' => array(
                    ':fr' => date('Y-m-d H:i:s', strtotime(date('Y-m-d'))),
                    ':to' => date('Y-m-d H:i:s', strtotime(date('Y-m-d')) + 86400)
                )
            ),
            'newest' => array('order' => $alias . '.date_create DESC'),
            'byViews' => array('order' => $alias . '.views DESC'),
            'byAddedToCart' => array('order' => $alias . '.added_to_cart_count DESC'),
        ), parent::scopes());
    }

    public static function getCSort()
    {
        $sort = new CSort;

        //dublicate with defaultScope()
        // $sort->defaultOrder = 't.ordern DESC';
        $sort->attributes = array(
            '*',
            'name' => array(
                'asc' => 'translate.name',
                'desc' => 'translate.name DESC',
            ),
        );

        return $sort;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array();
        if (empty(Yii::app()->settings->get('shop', 'auto_gen_product_title'))) {
            $rules[] = array('name, seo_alias', 'required');
        } else {
            $rules[] = array('sku', 'required');
        }
        $rules[] = array('video, discount, markup', 'default', 'setOnEmpty' => true, 'value' => null);
        $rules[] = array('price', 'commaToDot');
        $rules[] = array('price, price_purchase, type_id, manufacturer_id, main_category_id, supplier_id, currency_id, ordern, unit', 'numerical');
        $rules[] = array('switch, archive', 'boolean');
        $rules[] = array('use_configurations', 'boolean', 'on' => 'insert');
        $rules[] = array('quantity, availability, manufacturer_id, auto_decrease_quantity', 'numerical', 'integerOnly' => true);
        $rules[] = array('price, main_category_id', 'required');
        $rules[] = array('seo_alias', 'unique', 'on' => 'insert');
        $rules[] = array('seo_alias', 'TranslitValidator', 'translitAttribute' => 'name'); //Off for import
        $rules[] = array('date_create', 'date', 'format' => 'yyyy-M-d H:m:s');
        $rules[] = array('name, seo_alias, sku', 'length', 'max' => 255);
        $rules[] = array('short_description, full_description, discount, video, label', 'type', 'type' => 'string');
        // Search
        $rules[] = array('id, name, switch, seo_alias, price, sku, short_description, full_description, date_create, date_update, manufacturer_id, ordern, label', 'safe', 'on' => 'search');

        return $rules;
    }

    public function getUnits()
    {
        return array(
            1 => self::t('UNIT_THING'),
            2 => self::t('UNIT_METER'),
            3 => self::t('UNIT_BOX'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return CMap::mergeArray(array(
            'supplier' => array(self::BELONGS_TO, 'ShopSuppliers', 'supplier_id'),
            'currency' => array(self::BELONGS_TO, 'ShopCurrency', 'currency_id'),
            'manufacturer' => array(self::BELONGS_TO, 'ShopManufacturer', 'manufacturer_id', 'scopes' => array('applyTranslateCriteria')),
            'manufacturerActive' => array(self::BELONGS_TO, 'ShopManufacturer', 'manufacturer_id', 'condition' => '`manufacturerActive`.`switch`=1'),
            'manufacturerPublished' => array(self::BELONGS_TO, 'ShopManufacturer', 'manufacturer_id', 'scopes' => array('applyTranslateCriteria', 'published')),
            'productsCount' => array(self::STAT, 'ShopProduct', 'manufacturer_id', 'select' => 'count(t.id)'),
            'type' => array(self::BELONGS_TO, 'ShopProductType', 'type_id'),
            //   'typeGet' => array(self::BELONGS_TO, 'ShopProductType', 'type_id', 'condition' => 'typeGet.id=:tid', 'params' => array(':tid' => (int) $_GET['ShopProduct']['type_id'])), //Специально для Добавлние товара.
            'commentsCount' => array(self::STAT, 'Comments', 'object_id', 'condition' => '`t`.`model`="mod.shop.models.ShopProduct" AND `t`.`switch`=1'),
            'related' => array(self::HAS_MANY, 'ShopRelatedProduct', 'product_id'),
            //@todo test prices relation
            'prices' => array(self::HAS_MANY, 'ShopProductPrices', 'product_id'),
            'relatedProducts' => array(self::HAS_MANY, 'ShopProduct', array('related_id' => 'id'), 'through' => 'related'),
            'relatedProductCount' => array(self::STAT, 'ShopRelatedProduct', 'product_id'),
            'categorization' => array(self::HAS_MANY, 'ShopProductCategoryRef', 'product'),
            'categories' => array(self::HAS_MANY, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization'),
            'mainCategory' => array(self::HAS_ONE, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization', 'condition' => 'categorization.is_main = 1', 'scopes' => 'applyTranslateCriteria'),
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            // Product variation
            'variants' => array(self::HAS_MANY, 'ShopProductVariant', array('product_id'), 'with' => array('attribute', 'option'), 'order' => 'option.ordern'),
        ), parent::relations());
    }

    public function getMainImageTitle()
    {
        if ($this->attachmentsMain)
            return ($this->attachmentsMain->alt_title) ? $this->attachmentsMain->alt_title : $this->name;
    }

    public function afterFind()
    {
        if (!is_null($this->label)) {
            $this->label = explode(',', $this->label);
            // foreach ($modules as $mod) {
            // $mods[] = $mod;
            //}
            //  $this->label=$modules;
        } else {
            $this->label = array();
        }

        parent::afterFind();

    }

    /**
     * Find product by seo_alias.
     * Scope.
     * @param string ShopProduct seo_alias
     * @return ShopProduct
     */
    public function withUrl($seo_alias)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => '`t`.`seo_alias`=:url',
            'params' => array(':url' => $seo_alias)
        ));
        return $this;
    }

    /**
     * Find product by label.
     * Scope.
     * @param array ShopProduct label
     * @return ShopProduct
     */
    public function applyLabels(array $values)
    {
        $match = implode('%', $values);
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "label LIKE :match",
            'params' => array(':match' => "%$match%")
        ));
        return $this;
    }

    /**
     * Filter products by category
     * Scope
     * @param ShopCategory|string|array $categories to search products
     * @return ShopProduct
     */
    public function applyCategories($categories, $select = 't.*')
    {
        if ($categories instanceof ShopCategory)
            $categories = array($categories->id);
        else {
            if (!is_array($categories))
                $categories = array($categories);
        }

        $criteria = new CDbCriteria;

        if ($select)
            $criteria->select = $select;
        $criteria->join = 'LEFT JOIN `{{shop_product_category_ref}}` `categorization` ON (`categorization`.`product`=`t`.`id`)';
        $criteria->addInCondition('categorization.category', $categories);
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Filter products by EAV attributes.
     * Example: $model->applyAttributes(array('color'=>'green'))->findAll();
     * Scope
     * @param array $attributes list of allowed attribute models
     * @return ShopProduct
     */
    public function applyAttributes(array $attributes)
    {
        if (empty($attributes))
            return $this;
        return $this->withEavAttributes($attributes);
    }

    /**
     * Filter product by manufacturers
     * Scope
     * @param string|array $manufacturers
     * @return ShopProduct
     */
    public function applyManufacturers($manufacturers)
    {
        if (!is_array($manufacturers))
            $manufacturers = array($manufacturers);

        if (empty($manufacturers))
            return $this;

        sort($manufacturers);
        $criteria = new CDbCriteria;
        $criteria->addInCondition('manufacturer_id', $manufacturers);
        // $criteria->addCondition('`t`.`manufacturer_id`=' . $manufacturers);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * Filter products by min_price
     * @param $value
     * @return $this
     */
    public function applyMinPrice($value)
    {
        if ($value) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('t.price >= ' . (int)$value);
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    /**
     * Filter products by max_price
     * @param $value
     * @return $this
     */
    public function applyMaxPrice($value)
    {
        if ($value) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('t.price <= ' . (int)$value);
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param $params
     * @param $additionalCriteria
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($params = array(), $additionalCriteria = null)
    {
        $criteria = new CDbCriteria;

        $criteria->with = array(
            'categorization' => array('together' => true),
            'translate',
            'type',
        );

        if ($additionalCriteria !== null)
            $criteria->mergeWith($additionalCriteria);

        if ($this->manufacturer_id) {
            $manufacturerCr = new CDbCriteria;
            $manufacturerCr->with = array('manufacturer');
            $criteria->mergeWith($manufacturerCr);
        }


        if (isset($_GET['ShopProduct']['eav'])) {
            $result = array();
            foreach ($_GET['ShopProduct']['eav'] as $name => $eav) {
                if (!empty($eav)) {
                    $result[$name][] = $eav;
                }
            }
            $criteria->mergeWith($this->getFindByEavAttributesCriteria($result));
        }


        $ids = $this->id;
        // Adds ability to accepts id as "1,2,3" string
        if (false !== strpos($ids, ',')) {
            $ids = explode(',', $this->id);
            $ids = array_map('trim', $ids);
        }


        $criteria->compare('t.id', $ids);
        $criteria->compare('t.ordern', $this->ordern);

        $criteria->compare('translate.name', $this->name, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('t.price', $this->price);
        $criteria->compare('t.label', $this->label);
        $criteria->compare('translate.short_description', $this->short_description, true);
        $criteria->compare('translate.full_description', $this->full_description, true);
        $criteria->compare('t.sku', $this->sku, true);
        //$criteria->compare('t.date_create', $this->date_create, true);

        //  $criteria->addBetweenCondition('t.date_create',$this->date_create,$this->date_create);
        // $criteria->addSearchCondition('t.date_create','%'.$this->date_create.'%');


        //$criteria->addBetweenCondition('t.date_create', '2018-04-22 00:00:00','2018-04-23 23:59:59');


        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('t.type_id', $this->type_id);
        $criteria->compare('t.manufacturer_id', $this->manufacturer_id);
        $criteria->compare('t.supplier_id', $this->supplier_id);

        if (isset($params['category']) && $params['category']) {
            // $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $params['category']);
        }
        if (isset($_GET['ShopProduct']['categories']) && $_GET['ShopProduct']['categories']) {
            // $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $_GET['ShopProduct']['categories']);
        }

        if (isset($_GET['ShopProduct']['main_category_id']) && $_GET['ShopProduct']['main_category_id']) {
            //  $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $_GET['ShopProduct']['main_category_id']);
        }


        // Id of product to exclude from search
        if ($this->exclude)
            $criteria->compare('t.id !', array(':id' => $this->exclude));

        /* Товары за сегодня */
        if (isset($params['today']) && $params['today'] == true) {
            $today = strtotime(date('Y-m-d'));
            $criteria->addBetweenCondition('t.date_create', date('Y-m-d H:i:s', $today), date('Y-m-d H:i:s', $today + 86400));
        }

        $dependency = new CDbCacheDependency('SELECT MAX(date_create) FROM {{shop_product}}');
//Post::model()->cache(88888, $dependecy, 2)
//$this->cache(88888, $dependency, 2)
        //ShopProduct::model()->cache(88888, $dependency, 2)
        return new ActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => self::getCSort()
            )
        );
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $a = array();
        $a['eavAttr'] = array(
            'class' => 'mod.shop.components.eav.EEavBehavior',
            'tableName' => '{{shop_product_attribute_eav}}'
        );
        $a['seo'] = array(
            'class' => 'mod.seo.components.SeoBehavior',
            'url' => $this->getUrl()
        );
        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'relationName' => 'translate',
            'translateAttributes' => array(
                'name',
                'short_description',
                'full_description',
            ),
        );
        /* $a['uploadfile'] = array(
          'class' => 'app.behaviors.UploadfileBehavior',
          'attributes' => array('image' => 'product'),
          'saved' => 'before'
          ); */
        if (Yii::app()->hasModule('comments')) {
            Yii::import('mod.comments.models.Comments');
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'model' => 'mod.shop.models.ShopProduct',
                'owner_title' => 'name', // Attribute name to present comment owner in admin panel
            );
        }
        //   if (Yii::app()->hasModule('discounts')) {
        // Yii::import('mod.discounts.components.DiscountBehavior');
        if (Yii::app()->hasModule('markup')) {
            $a['markup'] = array(
                'class' => 'mod.markup.components.MarkupBehavior'
            );
        }
        if (Yii::app()->hasModule('discounts')) {
            $a['discounts'] = array(
                'class' => 'mod.discounts.components.DiscountBehavior'
            );
        }
        //return CMap::mergeArray($a, parent::behaviors());
        return CMap::mergeArray($a, parent::behaviors());
    }

    /**
     * Save related products. Notice, related product will be saved after save() method called.
     * @param array $ids Array of related products
     */
    public function setRelatedProducts($ids = array())
    {
        $this->_related = $ids;
    }

    public function beforeValidate()
    {
        // For configurable product set 0 price
        if ($this->use_configurations)
            $this->price = 0;


        if ($this->label) {
            $post = Yii::app()->request->getPost(__CLASS__, null);
            $this->label = implode(',', $post['label']);
        }
        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        if (Yii::app()->settings->get('shop', 'auto_fill_short_desc')) {
            //Yii::import('mod.shop.widgets.SAttributesTableRenderer');
            $a = new CAttributes($this);
            $this->short_description = $a->getStringAttr();
        }
        if ($this->archive) {
            $this->availability = 2;
            $this->quantity = 0;
        }
        if (!Yii::app() instanceof CConsoleApplication) {
            if (!empty(Yii::app()->settings->get('shop', 'auto_gen_product_title'))) {
                $category = ShopCategory::model()->findByPk($this->main_category_id);
                $replace = array(
                    "{main_category}" => (isset($category)) ? $category->name : 'none',
                    "{sub_category_name}" => (isset($category)) ? ($category->parent()->find()->name == 'root') ? '' : $category->parent()->find()->name : '',
                    "{manufacturer}" => ShopManufacturer::model()->findByPk($this->manufacturer_id)->name,
                    "{product_sku}" => $this->sku,
                );
                $this->name = CMS::textReplace(Yii::app()->settings->get('shop', 'auto_gen_product_title'), $replace);
                $this->seo_alias = CMS::translit($this->name);
            }
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {

        //Не работает в 1С
        /* if (Yii::app()->settings->get('shop', 'auto_add_subcategories')) {
          // Авто добавление предков категории
          // Нужно выбирать в админки самую последнию категории по уровню.
          $category = ShopCategory::model()
          ->findByPk($this->main_category_id);

          $categories = array();
          foreach ($category->ancestors()->excludeRoot()->findAll() as $cat) {

          $categories[] = $cat->id;
          }
          $this->setCategories($categories, $this->main_category_id);
          } else {

          $mainCategoryId = 1;
          if (isset($_POST['ShopProduct']['main_category_id']))
          $mainCategoryId = $_POST['ShopProduct']['main_category_id'];

          $this->setCategories(Yii::app()->request->getPost('categories', array()), $mainCategoryId);
          } */

        // Process related products
        if ($this->_related !== null) {
            $this->clearRelatedProducts();

            foreach ($this->_related as $id) {
                $related = new ShopRelatedProduct;
                $related->product_id = $this->id;
                $related->related_id = $id;
                $related->save(false, false);

                //двустороннюю связь между товарами
                if (Yii::app()->settings->get('shop', 'product_related_bilateral')) {
                    $related = new ShopRelatedProduct;
                    $related->product_id = $id;
                    $related->related_id = $this->id;
                    $related->save(false, false);
                }
            }
        }

        // Save configurable attributes
        if ($this->_configurable_attribute_changed === true) {
            // Clear
            Yii::app()->db->createCommand()->delete('{{shop_product_configurable_attributes}}', 'product_id = :id', array(':id' => $this->id));

            foreach ($this->_configurable_attributes as $attr_id) {
                Yii::app()->db->createCommand()->insert('{{shop_product_configurable_attributes}}', array(
                    'product_id' => $this->id,
                    'attribute_id' => $attr_id
                ));
            }
        }

        // Process min and max price for configurable product
        if ($this->use_configurations)
            $this->updatePrices($this);
        else {
            // Check if product is configuration
            $query = Yii::app()->db->createCommand()
                ->from('{{shop_product_configurations}} t')
                ->where(array('in', 't.configurable_id', array($this->id)))
                ->queryAll();

            foreach ($query as $row) {
                $model = ShopProduct::model()->findByPk($row['product_id']);
                if ($model)
                    $this->updatePrices($model);
            }
        }

        return parent::afterSave();
    }

    /**
     * Update price and max_price for configurable product
     * @param ShopProduct $model
     */
    public function updatePrices(ShopProduct $model)
    {
        // Get min and max prices
        $query = Yii::app()->db->createCommand()
            ->select('MIN(t.price) as min_price, MAX(t.price) as max_price')
            ->from('{{shop_product}} t')
            ->where(array('in', 't.id', $model->getConfigurations(true)))
            ->queryRow();

        // Update
        Yii::app()->db->createCommand()
            ->update('{{shop_product}}', array(
                'price' => $query['min_price'],
                'max_price' => $query['max_price']
            ), 'id=:id', array(':id' => $model->id));
    }

    /**
     * Delete related data.
     */
    public function afterDelete()
    {
        // Delete orders products
        //OrderProduct::model()->deleteAll('product_id=:pid',array(':pid'=>$this->id));
        //$orderProducts = OrderProduct::model()->findAllByAttributes(array('product_id' => $this->id));
        // foreach ($orderProducts as $p) {
        //    $p->delete();
        //    $p->order->updateTotalPrice();
        //}

        // Delete related products
        $this->clearRelatedProducts();
        ShopRelatedProduct::model()->deleteAll('related_id=:id', array('id' => $this->id));

        // Delete categorization
        ShopProductCategoryRef::model()->deleteAllByAttributes(array(
            'product' => $this->id
        ));


        // Delete variants
        $variants = ShopProductVariant::model()->findAllByAttributes(array('product_id' => $this->id));
        foreach ($variants as $v)
            $v->delete();

        // Clear configurable attributes
        Yii::app()->db->createCommand()->delete('{{shop_product_configurable_attributes}}', 'product_id=:id', array(':id' => $this->id));

        // Delete configurations
        Yii::app()->db->createCommand()->delete('{{shop_product_configurations}}', 'product_id=:id', array(':id' => $this->id));
        Yii::app()->db->createCommand()->delete('{{shop_product_configurations}}', 'configurable_id=:id', array(':id' => $this->id));

        // Delete from wish lists if install module "wishlist"
        if (Yii::app()->hasModule('wishlist')) {
            Yii::import('mod.wishlist.models.WishlistProducts');
            $wishlistProduct = WishlistProducts::model()->findByAttributes(array('product_id' => $this->id));
            if ($wishlistProduct)
                $wishlistProduct->delete();
        }
        // Delete from comapre if install module "comapre"
        if (Yii::app()->hasModule('comapre')) {
            Yii::import('mod.comapre.components.CompareProducts');
            $comapreProduct = new CompareProducts;
            $comapreProduct->remove($this->id);
        }

        Yii::import('app.forsage.ForsageExternalFinder');
        ForsageExternalFinder::removeObjectByPk(ForsageExternalFinder::OBJECT_TYPE_PRODUCT, $this->id);
        ForsageExternalFinder::removeObjectByPk(ForsageExternalFinder::OBJECT_TYPE_IMAGE, $this->id);

        return parent::afterDelete();
    }

    /**
     * Clear all related products
     */
    private function clearRelatedProducts()
    {
        ShopRelatedProduct::model()->deleteAll('product_id=:id', array('id' => $this->id));
    }

    /**
     * @return array
     */
    public static function getAvailabilityItems()
    {
        return array(
            1 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 1),
            2 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 2),
            3 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 3),
        );
    }

    public function defaultScope()
    {
        $t = $this->getTableAlias(true, false);
        return array(
            'order' => $t . '.`ordern` DESC',
        );
    }

    /**
     * @return array
     */
    public static function getProductLabels()
    {
        return array(
            Yii::t('ShopModule.default', 'PRODUCT_LABEL_NEW'),
            Yii::t('ShopModule.default', 'PRODUCT_LABEL_DISCOUNT'),
            Yii::t('ShopModule.default', 'PRODUCT_LABEL_HOT'),
        );
    }


    public function processPrices(array $categories)
    {
        $dontDelete = array();

        foreach ($categories as $index => $price) {
            if ($price['value'] > 0) {
                $record = ShopProductPrices::model()->findByAttributes(array(
                    'product_id' => $this->id,
                    'id' => $index
                ));
                if (!$record) {
                    $record = new ShopProductPrices;
                }

                $record->setAttributes(array(
                    'order_from' => (int)$price['order_from'],
                    'value' => $price['value'],
                    'product_id' => $this->id,
                ), false);
                $record->save(false, false);

                $dontDelete[] = $record->id;
            }
        }

        // Set main category
        //  ShopProductPrices::model()->updateAll(array(
        //      'switch' => $this->switch,
        //  ), 'product_id=:p', array(':p' => $this->id));

        // Delete not used relations
        if (sizeof($dontDelete) > 0) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('id', $dontDelete);

            ShopProductPrices::model()->deleteAllByAttributes(array(
                'product_id' => $this->id,
            ), $cr);
        } else {
            // Delete all relations
            ShopProductPrices::model()->deleteAllByAttributes(array(
                'product_id' => $this->id,
            ));
        }
    }


    /**
     * Set product categories and main category
     * @param array $categories ids.
     * @param integer $main_category Main category id.
     */
    public function setCategories(array $categories, $main_category)
    {
        $dontDelete = array();

        if (!ShopCategory::model()->countByAttributes(array('id' => $main_category)))
            $main_category = 1;

        if (!in_array($main_category, $categories))
            array_push($categories, $main_category);


        foreach ($categories as $c) {
            $count = ShopProductCategoryRef::model()->countByAttributes(array(
                'category' => $c,
                'product' => $this->id,
            ));

            if ($count == 0) {
                $record = new ShopProductCategoryRef;
                $record->category = (int)$c;
                $record->product = $this->id;
                $record->switch = $this->switch; // new param
                $record->save(false, false, false);
            }

            $dontDelete[] = $c;
        }

        // Clear main category
        ShopProductCategoryRef::model()->updateAll(array(
            'is_main' => 0,
            'switch' => $this->switch
        ), 'product=:p', array(':p' => $this->id));

        // Set main category
        ShopProductCategoryRef::model()->updateAll(array(
            'is_main' => 1,
            'switch' => $this->switch,
        ), 'product=:p AND category=:c', array(':p' => $this->id, ':c' => $main_category));

        // Delete not used relations
        if (sizeof($dontDelete) > 0) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('category', $dontDelete);

            ShopProductCategoryRef::model()->deleteAllByAttributes(array(
                'product' => $this->id,
            ), $cr);
        } else {
            // Delete all relations
            ShopProductCategoryRef::model()->deleteAllByAttributes(array(
                'product' => $this->id,
            ));
        }
    }

    /**
     * Prepare variations
     * @return array product variations
     */
    public function processVariants()
    {
        $result = array();
        foreach ($this->variants as $v) {
            if (!empty($v->price)) { //Pan, add emprty check price
                $result[$v->attribute->id]['attribute'] = $v->attribute;
                $result[$v->attribute->id]['options'][] = $v;
            }

        };
        return $result;
    }

    /**
     * @param $ids array of ShopAttribute pks
     */
    public function setConfigurable_attributes(array $ids)
    {
        $this->_configurable_attributes = $ids;
        $this->_configurable_attribute_changed = true;
    }

    /**
     * @return array
     */
    public function getConfigurable_attributes()
    {
        if ($this->_configurable_attribute_changed === true)
            return $this->_configurable_attributes;

        if ($this->_configurable_attributes === null) {
            $this->_configurable_attributes = Yii::app()->db->createCommand()
                ->select('t.attribute_id')
                ->from('{{shop_product_configurable_attributes}} t')
                ->where('t.product_id=:id', array(':id' => $this->id))
                ->group('t.attribute_id')
                ->queryColumn();
        }

        return $this->_configurable_attributes;
    }

    /**
     * @return array of product ids
     */
    public function getConfigurations($reload = false)
    {
        if (is_array($this->_configurations) && $reload === false)
            return $this->_configurations;

        $this->_configurations = Yii::app()->db->createCommand()
            ->select('t.configurable_id')
            ->from('{{shop_product_configurations}} t')
            ->where('product_id=:id', array(':id' => $this->id))
            ->group('t.configurable_id')
            ->queryColumn();

        return $this->_configurations;
    }


    public function getPriceByQuantity($q = 1)
    {

        $cr = new CDbCriteria();
        $cr->condition = '`t`.`product_id`=:pid AND `t`.`order_from` <= :from';
        $cr->params = array(
            ':pid' => $this->id,
            ':from' => $q
        );
        $cr->order = 'order_from DESC';
        return ShopProductPrices::model()->find($cr);
    }

    /**
     * Calculate product price by its variants, configuration and self price
     * @static
     * @param $product ShopProduct
     * @param array $variants
     * @param $configuration
     * @param $quantity
     * @return float
     */
    public static function calculatePrices($product, array $variants, $configuration, $quantity = 1)
    {
        if (($product instanceof ShopProduct) === false)
            $product = ShopProduct::model()->findByPk($product);

        if (($configuration instanceof ShopProduct) === false && $configuration > 0)
            $configuration = ShopProduct::model()->findByPk($configuration);

        if ($configuration instanceof ShopProduct) {
            $result = $configuration->price;
        } else {
            //  if ($product->currency_id) {
            //      $result = $product->price;
            //  } else {


//@todo need test sql by quantity=1
            if ($quantity > 1 && ($pr = $product->getPriceByQuantity($quantity))) {
                //$calcPrice = $item['model']->value;
                $result = $pr->value;
            } else {
                $result = $product->appliedDiscount ? $product->discountPrice : $product->price;
            }


            // $result = $product->getFrontPrice();
            //   }
        }

        // if $variants contains not models
        if (!empty($variants) && ($variants[0] instanceof ShopProductVariant) === false)
            $variants = ShopProductVariant::model()->findAllByPk($variants);

        foreach ($variants as $variant) {
            // Price is percent
            if ($variant->price_type == 1)
                $result += ($result / 100 * $variant->price);
            else
                $result += $variant->price;
        }

        return $result;
    }

    /**
     * Convert to active currency and format price.
     * Display min and max price for configurable products.
     * Used in product listing.
     * @return string
     */
    public function getFrontPrice()
    {
        $currency = Yii::app()->currency;
        if ($this->appliedDiscount) {
            $price = $currency->convert($this->discountPrice, $this->currency_id);
        } else {
            $price = $currency->convert(isset($this->appliedMarkup) ? $this->markupPrice : $this->price, $this->currency_id);
        }
        return $price;
    }

    public function priceRange()
    {
        $price = $this->getFrontPrice();
        $max_price = Yii::app()->currency->convert($this->max_price);

        if ($this->use_configurations && $max_price > 0)
            return Yii::app()->currency->number_format($price) . ' - ' . Yii::app()->currency->number_format($max_price);

        return Yii::app()->currency->number_format($price);
    }

    /**
     * Replaces comma to dot
     * @param $attr
     */
    public function commaToDot($attr)
    {
        $this->$attr = str_replace(',', '.', $this->$attr);
    }

    /**
     * Convert price to current currency
     *
     * @param string $attr
     * @return mixed
     */
    public function toCurrentCurrency($attr = 'price')
    {
        return Yii::app()->currency->convert($this->$attr);
    }

    /**
     * Check if product is on warehouse.
     *
     * @return bool
     */
    public function getIsAvailable()
    {
        return $this->availability == 1;
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl()
    {
        return Yii::app()->createAbsoluteUrl('/shop/product/view', array('seo_alias' => $this->seo_alias));
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return Yii::app()->createUrl('/shop/product/view', array('seo_alias' => $this->seo_alias));
    }

    /**
     * Decrease product quantity when added to cart
     */
    public function decreaseQuantity()
    {
        if ($this->auto_decrease_quantity && (int)$this->quantity > 0) {
            $this->quantity--;
            $this->save(false, false, false);
        }
    }

    /**
     * Allows to access EAV attributes like normal model attrs.
     * e.g $model->eav_some_attribute_name
     *
     * @todo Optimize, cache.
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        if (substr($name, 0, 4) === 'eav_') {
            if ($this->getIsNewRecord())
                return null;

            $attribute = substr($name, 4);
            $eavData = $this->getEavAttributes();

            if (isset($eavData[$attribute]))
                $value = $eavData[$attribute];
            else
                return null;

            $attributeModel = ShopAttribute::model()->findByAttributes(array('name' => $attribute));
            return $attributeModel->renderValue($value);
        }
        return parent::__get($name);
    }

    public function getCategories()
    {
        $content = array();
        foreach ($this->categories as $c) {
            $content[] = $c->name;
        }
        return implode(', ', $content);
    }

    public function keywords()
    {
        // if (empty(Yii::app()->settings->get('shop', 'auto_gen_tpl_keywords'))) {
        if ($this->mainCategory) {
            if (!empty($this->mainCategory->seo_product_keywords)) {
                return $this->replaceMeta($this->mainCategory->seo_product_keywords);
            } else {
                return $this->replaceMeta($this->mainCategory->parent()->find()->seo_product_keywords);
            }
        }
        // }
        return $this->replaceMeta(Yii::app()->settings->get('shop', 'auto_gen_tpl_keywords'));
    }

    public function description()
    {
        //if (empty(Yii::app()->settings->get('shop', 'auto_gen_tpl_description'))) {
        if ($this->mainCategory) {
            if (!empty($this->mainCategory->seo_product_description)) {
                return $this->replaceMeta($this->mainCategory->seo_product_description);
            } else {
                return $this->replaceMeta($this->mainCategory->parent()->find()->seo_product_description);
            }
        }
        // }
        return $this->replaceMeta(Yii::app()->settings->get('shop', 'auto_gen_tpl_description'));
    }

    public function title()
    {
        //@todo под вопросом для СЕО, нужено ли "auto_gen_tpl_title"
        // if (empty(Yii::app()->settings->get('shop', 'auto_gen_tpl_title'))) {
        if ($this->mainCategory) {
            if (!empty($this->mainCategory->seo_product_title)) {
                return $this->replaceMeta($this->mainCategory->seo_product_title);
            } else {
                return $this->replaceMeta($this->mainCategory->parent()->find()->seo_product_title);
            }
        }
        // }
        return $this->replaceMeta(Yii::app()->settings->get('shop', 'auto_gen_tpl_title'));
    }


    public function replaceMeta($text)
    {
        $attrArray = array();

        foreach ($this->getProductAttributes() as $k => $attr) {
            $attrArray['{eav_' . $k . '_value}'] = $attr->value;
            $attrArray['{eav_' . $k . '_name}'] = $attr->name;
        }

        $replace = CMap::mergeArray(array(
            "{product_name}" => $this->name,
            "{product_price}" => $this->price,
            "{product_sku}" => $this->sku,
            "{product_brand}" => (isset($this->manufacturer)) ? $this->manufacturer->name : null,
            "{product_main_category}" => (isset($this->mainCategory)) ? $this->mainCategory->name : null,
            "{current_currency}" => Yii::app()->currency->active->symbol,
        ), $attrArray);
        return CMS::textReplace($text, $replace);
    }


    public function getProductAttributes()
    {//no work
        // Yii::import('mod.shop.components.AttributesRender');
        $attributes = new CAttributes($this);
        return $attributes->getData();
    }

    public function getCurrencySymbol()
    {
        if ($this->currency_id) {
            return Yii::app()->currency->getSymbol($this->currency_id);
        } else {
            return Yii::app()->currency->active->symbol;
        }
    }

    public function getPriceOriginal()
    {
        return ($this->currency_id) ? Yii::app()->currency->convert($this->toCurrentCurrency('originalPrice'), $this->currency_id) : $this->toCurrentCurrency('originalPrice');
    }

}
