<?php

class SettingsSitemapForm extends FormSettingsModel
{
    /* const MODULE_ID = 'shop'; */

    public $category_changefreq;
    public $manufacturer_changefreq;
    public $product_changefreq;
    public $category_priority;
    public $manufacturer_priority;
    public $product_priority;
    public $product_enable;
    public $manufacturer_enable;
    public $category_enable;


    public static function defaultSettings()
    {
        return array(
            'category_changefreq' => 'daily',
            'manufacturer_changefreq' => 'daily',
            'product_changefreq' => 'daily',
            'category_priority' => '1.0',
            'manufacturer_priority' => '1.0',
            'product_priority' => '1.0',
            'product_enable' => true,
            'manufacturer_enable' => true,
            'category_enable' => true,

        );
    }

    public function getForm()
    {
        $tab = new TabForm(array(
            //'positionTabs' => 'vertical',
            'attributes' => array(
                'id' => __CLASS__,
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'category' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CATEGORY'),
                    'elements' => array(
                        'category_enable' => array('type' => 'checkbox'),
                        'category_changefreq' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->changefreqList()
                        ),
                        'category_priority' => array('type' => 'text','hint'=>self::t('HINT_PRIORITY')),
                    )
                ),
                'product' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_PRODUCT'),
                    'elements' => array(
                        'product_enable' => array('type' => 'checkbox'),
                        'product_changefreq' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->changefreqList()
                        ),
                        'product_priority' => array('type' => 'text','hint'=>self::t('HINT_PRIORITY')),
                    )
                ),
                'manufacturer' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_MANUFACTURER'),
                    'elements' => array(
                        'manufacturer_enable' => array('type' => 'checkbox'),
                        'manufacturer_changefreq' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->changefreqList()
                        ),
                        'manufacturer_priority' => array('type' => 'text','hint'=>self::t('HINT_PRIORITY')),
                    )
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

    public function changefreqList()
    {
        return array(
            'always' => 'always',
            'hourly' => 'hourly',
            'daily' => 'daily',
            'weekly' => 'weekly',
            'monthly' => 'monthly',
            'yearly' => 'yearly',
            'never' => 'never',
        );
    }

    public function rules()
    {
        return array(
             // array('create_btn_action', 'numerical', 'integerOnly' => true),
            array('category_changefreq, manufacturer_changefreq, product_changefreq, product_enable, manufacturer_enable, category_enable', 'required'),
            //, watermark_opacity
            array('product_enable, manufacturer_enable, category_enable', 'boolean'),
            // array('watermark_opacity', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
            array('category_priority, manufacturer_priority, product_priority', 'type', 'type' => 'string'),
        );
    }

    public function save($message = true) {
        Yii::app()->cache->delete(Yii::app()->getModule('sitemap')->cacheKey);
        parent::save($message);
    }
}
