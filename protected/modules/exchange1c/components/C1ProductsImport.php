<?php

Yii::import('mod.exchange1c.components.C1ExternalFinder');
Yii::import('mod.exchange1c.components.C1ProductImage');
Yii::import('mod.shop.models.ShopCategory');
Yii::import('mod.shop.models.ShopProduct');
Yii::import('mod.shop.models.ShopAttribute');
Yii::import('mod.shop.models.ShopTypeAttribute');
Yii::import('mod.shop.models.ShopAttributeOption');
Yii::import('mod.shop.models.ShopManufacturer');


/**
 * Imports products from XML file
 */
class C1ProductsImport extends CComponent {

    /**
     * ID of the ShopType model to apply to new attributes and products
     */
    const DEFAULT_TYPE = 1;

    /**
     * @var string alias where to save uploaded files
     */
    public $tempDirectory = 'application.runtime';

    /**
     * @var string
     */
    protected $xml;

    /**
     * @var ShopCategory
     */
    protected $_rootCategory;

    /**
     * @static
     * @param $type
     * @param $mode
     */
    public static function processRequest($type, $mode) {
        $method = 'command' . ucfirst($type) . ucfirst($mode);
        $import = new self;
        if (method_exists($import, $method))
            $import->$method();
    }

    /**
     * Authenticate
     */
    public function commandCatalogCheckauth() {
        echo "success\n";
        echo Yii::app()->session->sessionName . "\n";
        echo Yii::app()->session->sessionId . "\n";
    }

    /**
     * Initialize catalog.
     */
    public function commandCatalogInit() {
        //Yii::log(__FUNCTION__, 'info', 'application');
        $fileSize = (int) (ini_get('upload_max_filesize')) * 1024 * 1024;
        echo "zip=no\n";
        echo "filelimit={$fileSize}\n";
    }

    /**
     * Save file
     */
    public function commandCatalogFile() {
        $fileName = Yii::app()->request->getQuery('filename');
        $result = file_put_contents($this->buildPathToTempFile($fileName), file_get_contents('php://input'));
        if ($result !== false)
            echo "success\n";
    }

    /**
     * Import
     */
    public function commandCatalogImport() {
        //Yii::log(__FUNCTION__, 'info', 'application');
        $this->xml = $this->getXml(Yii::app()->request->getQuery('filename'));
        if (!$this->xml)
            return false;

        // Import categories
        if (isset($this->xml->{"Классификатор"}->{"Группы"}))
            $this->importCategories($this->xml->{"Классификатор"}->{"Группы"});

        // Import properties
        if (isset($this->xml->{"Классификатор"}->{"Свойства"}))
            $this->importProperties();

        // Import products
        if (isset($this->xml->{"Каталог"}->{"Товары"}))
            $this->importProducts();

        // Import prices
        if (isset($this->xml->{"ПакетПредложений"}->{"Предложения"}))
            $this->importPrices();

        echo "success\n";
    }

    /**
     * Import catalog products
     */
    public function importProducts() {
        $config = Yii::app()->settings->get('exchange1c');

        foreach ($this->xml->{"Каталог"}->{"Товары"}->{"Товар"} as $product) {

            $createExId = false;
            $model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $product->{"Ид"});


            //Удаление товара
            if ($product->{"ПометкаУдаления"} == 'true' && $config['deletion_product_flag'] == 'delete') {
                if ($model)
                    $model->delete();
                //C1ExternalFinder::removeObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $product->{"Ид"});
            }

            if (!$model) {
                $model = new ShopProduct;
                $model->type_id = self::DEFAULT_TYPE;
                $model->price = 0;
                $model->switch = 1;
                $createExId = true;
            }



            if ($config['deletion_product_flag'] == 'switch') {
                $model->switch = ($product->{"ПометкаУдаления"} == 'true') ? 0 : 1;
            }

            $model->name = $product->{"Наименование"};
            $model->seo_alias = CMS::translit($model->name);
            $model->sku = $product->{"Артикул"};
            $model->full_description = $product->{"Описание"};
            
            $model->in_ros = $product->{"Ростовка"};
            $model->in_box = $product->{"Ящик"};
            $model->stamp1 = ($product->{"Кожа"}=='Да')?true:false;



            if (isset($product->{"Изготовитель"})) {

                $manufacturer = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_MANUFACTURER, $product->{"Изготовитель"}->{"Ид"});
                if (!$manufacturer) {
                    $manufacturer = new ShopManufacturer;
                    $manufacturer->name = $product->{"Изготовитель"}->{"Наименование"};
                    $manufacturer->seo_alias = CMS::translit($manufacturer->name);
                    $manufacturer->save(false, false, false);
                    $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_MANUFACTURER, $manufacturer->id, $product->{"Изготовитель"}->{"Ид"});
                }
                $model->manufacturer_id = $manufacturer->id;
            }



            if ($model->save(false, false, false)) {
                Yii::log('product saved success', 'info', 'application');
            } else {
                //  print_r($model->getErrors());
                Yii::log($model->id . ' + ' . CJSON::encode($model->getErrors()), 'info', 'application');
                //die;
            }

            Yii::log($model->id . ' ID', 'info', 'application');
            // Create external id
            if ($createExId === true)
                $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $model->id, $product->{"Ид"});

            // Set category
            $categoryId = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $product->{"Группы"}->{"Ид"}, false);


            //if (Yii::app()->settings->get('shop', 'auto_add_subcategories')) {
            if (is_numeric($categoryId)) {
                $category1 = ShopCategory::model()
                        ->findByPk($categoryId);

                $categories = array();
                $subCategory = $category1->ancestors()->excludeRoot()->findAll();
                if (isset($subCategory)) {
                    foreach ($subCategory as $cat) {
                        $categories[] = $cat->id;
                    }
                    $model->setCategories($categories, $categoryId);
                } else {
                    $model->setCategories(array($categoryId), $categoryId);
                }
            }
            /// } else {
            //     $model->setCategories(array($categoryId), $categoryId);
            // }
            //Set once image
            /* if (!empty($product->{"Картинка"})) {
              $image = C1ProductImage::create($this->buildPathToTempFile($product->{"Картинка"}));
              if ($image && !$model->mainImage)
              $model->addImage($image);
              } */
            if (!empty($product->{"Картинка"})) {
                foreach ($product->{"Картинка"} as $k => $pi) {
                    $image = C1ProductImage::create($this->buildPathToTempFile($pi));
                    if ($image && !$model->mainImage) // && !$model->mainImage
                        $model->addImage($image);
                }
            }
            // Process properties
            if (isset($product->{"ЗначенияСвойств"}->{"ЗначенияСвойства"})) {
                $attrsdata = array();
                foreach ($product->{"ЗначенияСвойств"}->{"ЗначенияСвойства"} as $attribute) {
                    $attributeModel = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $attribute->{"Ид"});
                    if ($attributeModel && $attribute->{"Значение"} != '') {
                        $cr = new CDbCriteria;
                        //$cr->with = 'option_translate';
                        //$cr->compare('option_translate.value', $attribute->{"Значение"});
                        $cr->compare('value', $attribute->{"Значение"});
                        $option = ShopAttributeOption::model()->find($cr);
                        if (!$option)
                            $option = $this->addOptionToAttribute($attributeModel->id, $attribute->{"Значение"});
                        //$option = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE_OPTION, $attribute->{"Значение"});
                        $attrsdata[$attributeModel->name] = $option->id;
                    }
                }

                if (!empty($attrsdata)) {
                    $model->setEavAttributes($attrsdata, true);
                }
            }
        }
    }

    /**
     * Import catalog prices
     */
    public function importPrices() {
        foreach ($this->xml->{"ПакетПредложений"}->{"Предложения"}->{"Предложение"} as $offer) {
            $params = explode('#',$offer->{"Ид"});
            $product = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $params[0]);
            if ($product) {
                $product->price = $offer->{"Цены"}->{"Цена"}->{"ЦенаЗаЕдиницу"};
                $product->quantity = 1;//$offer->{"Количество"};
                $product->save(false, false, false);
            }
        }
    }

    /**
     * @param $attribute_id
     * @param $value
     * @return ShopAttributeOption
     */
    public function addOptionToAttribute($attribute_id, $value) {
        // Add option
        $option = new ShopAttributeOption;
        $option->attribute_id = $attribute_id;
        $option->value = $value;
        $option->save(false, false, false);
        return $option;
    }

    /**
     * Import product properties
     */
    public function importProperties() {
        foreach ($this->xml->{"Классификатор"}->{"Свойства"}->{"Свойство"} as $attribute) {
            $model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $attribute->{"Ид"});

            if ($attribute->{"ЭтоФильтр"} == 'false')
                $useInFilter = false;
            else
                $useInFilter = true;

            if (!$model) {
                // Create new attribute
                $model = new ShopAttribute;
                $model->title = $attribute->{"Наименование"};
                $model->name = CMS::translit($model->title);
                $model->type = ShopAttribute::TYPE_DROPDOWN;
                $model->use_in_filter = $useInFilter;
                $model->select_many = true;
                $model->display_on_front = true;

                if ($model->save()) {
                    // Add to type
                    $typeAttribute = new ShopTypeAttribute;
                    $typeAttribute->type_id = self::DEFAULT_TYPE;
                    $typeAttribute->attribute_id = $model->id;
                    $typeAttribute->save();

                    $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $model->id, $attribute->{"Ид"});
                }
            }

            //Добавляем все здачения свойств.
            /*
            if (isset($attribute->{"ВариантыЗначений"})) {
                foreach ($attribute->{"ВариантыЗначений"} as $opti) {
                    if (isset($opti->{"Справочник"})) {
                        foreach ($opti->{"Справочник"} as $sp) {
                            $option = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE_OPTION, $sp->{"ИдЗначения"});
                            if (!$option) {
                                $option = new ShopAttributeOption;
                                $option->attribute_id = $model->id;
                                $option->value = $sp->{"Значение"};
                                $option->save(false, false, false);
                                $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE_OPTION, $option->id, $sp->{"ИдЗначения"});
                            }
                        }
                    }
                }
            }*/


            // Update attributes
            $model->name = CMS::translit($attribute->{"Наименование"});
            $model->use_in_filter = $useInFilter;
            $model->save();
        }
    }

    /**
     * @param $data
     * @param null|ShopCategory $parent
     */
    public function importCategories($data, $parent = null) {
        foreach ($data->{"Группа"} as $category) {
            // Find category by external id
            $model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $category->{"Ид"});

            if (!$model) {
                $model = new ShopCategory;
                $model->name = $category->{"Наименование"};
                $model->seo_alias = CMS::translit($category->{"Наименование"});
                $model->appendTo($this->getRootCategory());
                $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $model->id, $category->{"Ид"});
            }

            if ($parent === null)
                $model->moveAsLast($this->getRootCategory());
            else
                $model->moveAsLast($parent);

            $model->saveNode();

            // Process subcategories
            if (isset($category->{"Группы"}))
                $this->importCategories($category->{"Группы"}, $model);
        }
    }

    /**
     * parse xml file from temp dir.
     * @param $xmlFileName
     * @return bool|object
     */
    public function getXml($xmlFileName) {
        $xmlFileName = str_replace('../', '', $xmlFileName);
        $fullPath = Yii::getPathOfAlias($this->tempDirectory) . DS . $xmlFileName;
        if (file_exists($fullPath) && is_file($fullPath))
            return simplexml_load_file($fullPath);
        else
            return false;
    }

    /**
     * @return ShopCategory
     */
    public function getRootCategory() {
        if ($this->_rootCategory)
            return $this->_rootCategory;
        $this->_rootCategory = ShopCategory::model()->findByPk(1);
        if ($this->_rootCategory) {
            return $this->_rootCategory;
        } else {
            echo "failure\n";
            echo $this->setMessage('ERROR_ROOT_CATEGORY');
            die;
        }
    }

    /**
     * @param $type
     * @param $id
     * @param $externalId
     */
    public function createExternalId($type, $id, $externalId) {
        Yii::app()->db->createCommand()->insert('{{exchange1c}}', array(
            'object_type' => $type,
            'object_id' => $id,
            'external_id' => $externalId
        ));
    }

    /**
     * Builds path to 1C downloaded files. E.g: we receive
     * file with name 'import/df3/fl1.jpg' and build path to temp dir,
     * protected/runtime/fl1.jpg
     *
     * @param $fileName
     * @return string
     */
    public function buildPathToTempFile($fileName) {
        //Yii::log('TEST ' . $fileName, 'info', 'application');
        //$fileName = end(explode('/', $fileName));

        $tmp = explode('/', $fileName);
        $fileName = end($tmp);

        return Yii::getPathOfAlias($this->tempDirectory) . DS . $fileName;
    }

    private function setMessage($message_code) {
        return Yii::app()->name . ': ' . iconv('UTF-8', 'windows-1251', Yii::t('Exchange1cModule.default', $message_code));
    }

}
