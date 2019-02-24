<?php

class SettingsShopForm extends FormSettingsModel
{
    /* const MODULE_ID = 'shop'; */

    public $auto_gen_cat_meta;
    public $auto_gen_cat_tpl_title;
    public $auto_gen_cat_tpl_keywords;
    public $auto_gen_cat_tpl_description;
    public $per_page;
    public $auto_fill_short_desc;
    public $auto_gen_meta;
    public $auto_gen_tpl_title;
    public $auto_gen_tpl_keywords;
    public $auto_gen_tpl_description;
    public $maxFileSize;
    public $maximum_image_size;
    public $watermark_image;
    public $watermark_active;
    public $watermark_corner;
    public $watermark_offsetX = 0;
    public $watermark_offsetY = 0;
    public $create_btn_action;
    public $filter_enable_price;
    public $filter_enable_brand;
    public $filter_enable_attr;
    public $ajax_mode;
    public $auto_add_subcategories;
    public $product_related_bilateral;
    public $label_new_days;
    public $label_sale;
    public $label_popular;
    public $label_topbuy;
    public $auto_gen_product_title;
    public $enable_auto_label;

    public static function defaultSettings()
    {
        return array(
            'auto_gen_product_title' => '',
            'per_page' => '10,20,30',
            'maxFileSize' => 10485760, //10*1024*1024,
            'watermark_active' => true,
            'watermark_image' => 'watermark.png',
            'watermark_corner' => 4,
            'watermark_offsetX' => 10,
            'watermark_offsetY' => 10,
            'auto_fill_short_desc' => 0,
            'maximum_image_size' => '800x600',
            'auto_gen_meta' => 1,
            'auto_gen_tpl_keywords' => 'название продукта {product_sku}',
            'auto_gen_tpl_description' => 'название продукта {product_sku}',
            'auto_gen_tpl_title' => 'Купить {product_main_category}  {product_brand} {product_sku} в Одессе оптом',
            'filter_enable_price' => 1,
            'filter_enable_brand' => 1,
            'filter_enable_attr' => 1,
            'create_btn_action' => 0,
            'ajax_mode' => 0,
            'auto_add_subcategories' => true,
            'product_related_bilateral' => true,
            'enable_auto_label'=>true
        );
    }

    public function getForm()
    {
        Yii::import('ext.tageditor.TagEditor');
        $tab = new TabForm(array(
            //'positionTabs' => 'vertical',
            'attributes' => array(
                'id' => __CLASS__,
                'enctype' => 'multipart/form-data',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'main' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'per_page' => array(
                            'type' => 'text',
                            'hint' => self::t('HINT_PER_PAGE'),
                        ),
                        //'currency_autoconvert' => array(
                        //    'type' => 'checkbox',
                        //    'hint' => self::t('HINT_CURRENCY_AUTOCONVERT')
                        //),
                        /*
                         * В планах */
                        //'ajax_mode' => array(
                        //    'type' => 'checkbox',
                        //    'hint'=>self::t('HINT_AJAX_MODE')
                        // ),

                        'auto_gen_product_title' => array(
                            'type' => 'text',
                            'hint' => self::t('HINT_AUTO_GEN_URL')
                        ),
                        'auto_fill_short_desc' => array(
                            'type' => 'checkbox',
                        ),
                        'auto_add_subcategories' => array(
                            'type' => 'checkbox',
                            'labelHelp' => Yii::t('ShopModule.admin', 'При выборе основной категории товар автоматически будет находится в предках основной категории.')
                        ),
                        'product_related_bilateral' => array('type' => 'checkbox'),
                        'filter_enable_price' => array('type' => 'checkbox'),
                        'filter_enable_brand' => array('type' => 'checkbox'),
                        'filter_enable_attr' => array('type' => 'checkbox'),

                        'create_btn_action' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopProductType::model()->findAll(), 'id', 'name'),
                            'empty' => '&mdash; Не привязывать &mdash;',
                            'hint' => self::t('HINT_CREATE_BTN_ACTION')
                        )
                    )
                ),
                'seo' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_SEO'),
                    'elements' => array(
                        'auto_gen_meta' => array('type' => 'checkbox'),
                        'auto_gen_tpl_title' => array(
                            'type' => 'textarea',
                            'hint' => self::t('META_TPL', array(
                                '{currency}' => Yii::app()->currency->active->symbol
                            ))
                        ),
                        'auto_gen_tpl_keywords' => array(
                            'type' => 'textarea',
                            'hint' => self::t('META_TPL', array(
                                '{currency}' => Yii::app()->currency->active->symbol
                            ))
                        ),
                        'auto_gen_tpl_description' => array('type' => 'textarea', 'hint' => self::t('META_TPL', array(
                            '{currency}' => Yii::app()->currency->active->symbol
                        ))),
                    )
                ),
                'catseo' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CAT_SEO'),
                    'elements' => array(
                        'auto_gen_cat_meta' => array('type' => 'checkbox'),
                        'auto_gen_cat_tpl_title' => array(
                            'type' => 'textarea',
                            'hint' => self::t('META_CAT_TPL', array(
                                '{currency}' => Yii::app()->currency->active->symbol
                            ))
                        ),
                        'auto_gen_cat_tpl_keywords' => array(
                            'type' => 'textarea',
                            'hint' => self::t('META_CAT_TPL', array(
                                '{currency}' => Yii::app()->currency->active->symbol
                            ))
                        ),
                        'auto_gen_cat_tpl_description' => array('type' => 'textarea', 'hint' => self::t('META_CAT_TPL', array(
                            '{currency}' => Yii::app()->currency->active->symbol
                        ))),
                    )
                ),
                'watermark' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_WM'),
                    'elements' => array(
                        'watermark_active' => array(
                            'type' => 'checkbox',
                        ),
                        'watermark_image' => array(
                            'type' => 'file',
                        ),
                        '<div class="form-group">
				<div class="col-sm-4"><label></label></div>
				<div class="col-sm-8">' . $this->renderWatermarkImageTag() . '</div>
				</div>',
                        'watermark_corner' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->getWatermarkCorner()
                        ),
                        'watermark_offsetX' => array(
                            'type' => 'text'
                        ),
                        'watermark_offsetY' => array(
                            'type' => 'text'
                        ),
                    )
                ),
                'images' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_IMG'),
                    'elements' => array(
                        'maxFileSize' => array(
                            'type' => 'text',
                            'hint' => Yii::t('ShopModule.admin', 'Укажите размер в байтах.')
                        ),
                        'maximum_image_size' => array(
                            'type' => 'text',
                            'hint' => Yii::t('ShopModule.admin', 'Изображения превышающие этот размер, будут изменены.')
                        ),
                    )
                ),
                'autolabels' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_AUTOLABELS'),
                    'elements' => array(
                        '<div class="alert alert-info">Необходимо запустить крон задачу "labelControl"</div>',
                        'enable_auto_label'=> array('type' => 'checkbox'),
                        'label_new_days' => array('type' => 'text'),
                        'label_topbuy' => array('type' => 'text'),
                        'label_popular' => array('type' => 'text'),
                        'label_sale' => array('type' => 'checkbox'),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        ), $this);

        return $tab;
    }


    public function getWatermarkCorner()
    {
        return array(
            1 => self::t('CORNER_LEFT_TOP'),
            2 => self::t('CORNER_RIGHT_TOP'),
            3 => self::t('CORNER_LEFT_BOTTOM'),
            4 => self::t('CORNER_RIGHT_BOTTOM'),
            5 => self::t('CORNER_CENTER'),
        );
    }

    public function rules()
    {
        return array(
            array('create_btn_action', 'numerical', 'integerOnly' => true),
            array('watermark_corner', 'numerical', 'integerOnly' => true),
            array('watermark_offsetX, watermark_offsetY', 'required'),
            //, watermark_opacity
            array('per_page, maxFileSize, maximum_image_size', 'required'),
            array('watermark_image', 'validateWatermarkFile'),
            array('auto_add_subcategories, product_related_bilateral, ajax_mode, watermark_active, auto_gen_meta, filter_enable_price, filter_enable_brand, filter_enable_attr, auto_fill_short_desc, auto_gen_cat_meta, enable_auto_label', 'boolean'),
            // array('watermark_opacity', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
            array('label_new_days, label_topbuy, label_popular, auto_gen_product_title', 'type', 'type' => 'string'),
            array('label_sale', 'boolean'),
            array('auto_gen_tpl_title, auto_gen_tpl_keywords, auto_gen_tpl_description', 'type', 'type' => 'string'),
            array('auto_gen_cat_tpl_title, auto_gen_cat_tpl_keywords, auto_gen_cat_tpl_description', 'type', 'type' => 'string'),
        );
    }

    public function renderWatermarkImageTag()
    {
        if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/watermark.png'))
            return Html::image('/uploads/watermark.png?' . time());
    }

    /**
     * Validates uploaded watermark file
     */
    public function validateWatermarkFile($attr)
    {
        $file = CUploadedFile::getInstance($this, 'watermark_image');
        if ($file) {
            $allowedExts = array('jpg', 'gif', 'png');
            if (!in_array($file->getExtensionName(), $allowedExts))
                $this->addError($attr, self::t('ERROR_WM_NO_IMAGE'));
        }
    }

    public function getCurrencies()
    {
        $result = array();
        foreach (Yii::app()->currency->getCurrencies() as $id => $model)
            $result[$id] = $model->name;
        return $result;
    }

    public function save($message = true)
    {
        $this->saveWatermark();
        parent::save($message);
    }

    public function saveWatermark()
    {
        $watermark = CUploadedFile::getInstance($this, 'watermark_image');
        if ($watermark)
            $watermark->saveAs(Yii::getPathOfAlias('webroot') . '/uploads/watermark.png');
    }

}
