<?php

/**
 * Import products from xml format
 * Images must be located at ./uploads/importImages
 */
class XmlImporter extends CComponent {

    /**
     * @var string path to file
     */
    public $file;

    /**
     * @var string
     */
    public $subCategoryPattern = '/\\/((?:[^\\\\\/]|\\\\.)*)/';

    /**
     * @var bool
     */
    public $deleteDownloadedImages = false;

    /**
     * @var resource
     */
    protected $fileHandler;

    /**
     * Columns from first line. e.g array(category,price,name,etc...)
     * @var array
     */
    protected $columns = array();

    /**
     * @var null|ShopCategory
     */
    protected $rootCategory = null;

    /**
     * @var array
     */
    protected $categoriesPathCache = array();

    /**
     * @var array
     */
    protected $productTypeCache = array();

    /**
     * @var array
     */
    protected $manufacturerCache = array();

    /**
     * @var int
     */
    protected $line = 0;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var array
     */
    public $stats = array(
        'create' => 0,
        'update' => 0,
    );
    public $requiredAttrs = array('category', 'name', 'type', 'price');

    /**
     * @return bool validate csv file
     */
    public function validate() {
        // Check file exists and readable
        if (is_uploaded_file($this->file)) {
            $newDir = Yii::getPathOfAlias('application.runtime') . '/tmp.xml';
            move_uploaded_file($this->file, $newDir);
            $this->file = $newDir;
        } elseif (file_exists($this->file)) {
            // ok. file exists.
        } else {
            $this->errors[] = array('line' => 0, 'error' => Yii::t('admin', 'Файл недоступен.'));
            return false;
        }

        $file = $this->getFileHandler();
        if (isset($file['product'])) {
            foreach ($file['product'] as $key => $product) {
                //s print_r($key);
                // die;
                $this->columns = array_keys($file['product']);
                foreach ($this->requiredAttrs as $column) {
                    if (!in_array($column, $this->columns))
                        $this->errors[] = array('line' => 0, 'error' => Yii::t('admin', 'Укажите обязательный параметр {column} в товаре #{key}.', array('{column}' => $column, '{key}' => $key + 1)));
                }
                $this->validateAttributes($product);
            }
        }else {
            $this->errors[] = array('line' => 0, 'error' => Yii::t('admin', 'Неверный формат XML (200)'));
            return false;
        }
        return !$this->hasErrors();
    }

    protected function validateAttributes($product) {

        if (isset($product['attributes'])) {
            $i = 0;
            foreach ($product['attributes'] as $key => $attr) {
                
//print_r($attr);
//print_r($attr);
//die;
             /*   if (isset($attr['@attributes'])) {
                    $this->columns = array_keys($attr['@attributes']);
                } else {
                    $this->errors[] = array('line' => 0, 'error' => 'Не верный формат XML. Проверте файл, Ошибка в attributes!');
                    return false;
                }*/
                

                if (empty($attr[$i]['@value'])) {
                    $this->errors[] = array(
                        'line' => 0,
                        'error' => Yii::t('admin', 'Атрибут "{attr}" не может быть пустым. "<b>{product_name}</b>"', array(
                            '{attr}' => $attr[$i]['@attributes']['name'],
                            '{product_name}' => $product['name']
                                )
                        )
                    );
                }
                $i++;
            }

            foreach (array('name') as $column) {
                if (!in_array($column, $this->columns))
                    $this->errors[] = array('line' => 0, 'error' => Yii::t('admin', 'Укажите обязательный параметр атрибутов {name} в товаре "{product_name}".', array('{name}' => $column, '{product_name}' => $product['name'])));
            }
        }


    }

    /**
     * Read xml file.
     * @return resource xml file
     */
    protected function getFileHandler() {
        $content = file_get_contents($this->file);
        if (empty($content)) {
            $this->errors[] = array('line' => 0, 'error' => 'Неверный формат XML (100)');
            return false;
        } else {
            $result = XML2Array::createArray($content);
            return $result['products'];
        }
    }

    /**
     * Here we go
     */
    public function import() {
        $file = $this->getFileHandler();

        foreach ($file['product'] as $key => $row) {
            //$row = $this->prepareRow($key);
            $this->importRow($row);
            $this->line++;
        }
    }

    /**
     * Apply column key to csv row.
     * @param $row array
     * @return array e.g array(key=>value)
     */
    protected function prepareRow($row) {

        //$row = array_map('trim', $row);
        //$row = array_combine($this->columns, $row);
       $row['date_create'] = date('Y-m-d H:i:s');
       $row['date_update'] = date('Y-m-d H:i:s');


        return array_filter($row);
    }

    /**
     * Create/update product from key=>value array
     * @param $data array of product attributes
     */
    protected function importRow($data) {

        if (!isset($data['category']) || empty($data['category']))
            $data['category'] = 'root';

        $newProduct = false;
        $category_id = $this->getCategoryByPath($data['category']);

        // Search product by name, category
        // or create new one
        $cr = new CDbCriteria;
        $cr->with = array('translate');

        // if (isset($data['seo_alias']) && !empty($data['seo_alias']) && $data['seo_alias'] != '')
        //     $cr->compare('t.seo_alias', $data['seo_alias']);

        if (isset($data['sku']) && !empty($data['sku']) && $data['sku'] != '')
            $cr->compare('t.sku', $data['sku']);
        else
            $cr->compare('translate.name', $data['name']);

        $model = ShopProduct::model()
                ->applyCategories($category_id)
                ->find($cr);

        if (!$model) {
            $newProduct = true;
            $model = new ShopProduct;
            $this->stats['create'] ++;
        } else {
            $this->stats['update'] ++;
        }
        $model->name = $data['name'];
        $model->price = $data['price'];
        $model->seo_alias = CMS::translit($data['name']);
        // Process product type
        $model->type_id = $this->getTypeIdByName($data['type']);
        $model->switch = (isset($data['switch']))?$data['switch']:1;

        // Manufacturer
        if (isset($data['manufacturer']) && !empty($data['manufacturer']))
            $model->manufacturer_id = $this->getManufacturerIdByName($data['manufacturer']);

        // Update product variables and eav attributes.
        if (isset($data['attributes'])) {
            $attributes = new XmlAttributesProcessor($model, $data['attributes']);
        }
        if ($model->validate()) {
            $categories = array($category_id);

            if (isset($data['additionalCategories']))
                $categories = array_merge($categories, $this->getAdditionalCategories($data['additionalCategories']));

            if (!$newProduct) {
                foreach ($model->categorization as $c)
                    $categories[] = $c->category;
                $categories = array_unique($categories);
            }

            // Save product
            $model->save(false, false,false);
            if (isset($data['attributes'])) {
                // Update EAV data
                $attributes->save();
            }
            // Update categories
            $model->setCategories($categories, $category_id);

            // Process product main image if product doesn't have one
            $this->setImages($model, $data);
        } else {
            $errors = $model->getErrors();
            $error = array_shift($errors);

            $this->errors[] = array(
                'line' => $this->line,
                'error' => $error[0],
            );
        }
    }

    protected function setImages($model, $data) {
        if (isset($data['images'])) {
            $deleteOld = false;
            if (isset($data['images']['@attributes'])) {
                if (isset($data['images']['@attributes']['deleteOld'])) {
                    if ($data['images']['@attributes']['deleteOld'] == 'true') {
                        $deleteOld = true;
                    }
                }
            }
            if ($deleteOld) {
                // Delete images
                $images = $model->images;
                if (!empty($images)) {
                    foreach ($images as $image)
                        $image->delete();
                }
            }
            foreach ($data['images']['image'] as $k => $img) {
                $imageName = (isset($img['@value'])) ? $img['@value'] : $img;
                $image = XmlImage::create($imageName);
                if ($image) {
                    $model->addImage($image);
                }
                if ($image && $this->deleteDownloadedImages)
                    $image->deleteTempFile();
            }
        }
    }

    /**
     * Get additional categories array from string separated by ";"
     * E.g. Video/cat1;Video/cat2
     * @param $str
     * @return array
     */
    public function getAdditionalCategories($str) {
        $result = array();
        $parts = explode('/', $str);
        foreach ($parts as $path)
            $result[] = $this->getCategoryByPath(trim($path));
        return $result;
    }

    /**
     * Find or create manufacturer
     * @param $name
     * @return integer
     */
    public function getManufacturerIdByName($name) {
        if (isset($this->manufacturerCache[$name]))
            return $this->manufacturerCache[$name];

        $cr = new CDbCriteria;
        $cr->with = array('man_translate');
        $cr->compare('man_translate.name', $name);
        $model = ShopManufacturer::model()->find($cr);

        if (!$model) {
            $model = new ShopManufacturer;
            $model->name = $name;
            $model->seo_alias = CMS::translit($name);
            if ($model->validate()) {
                $model->save(false, false);
            }
        }

        $this->manufacturerCache[$name] = $model->id;
        return $model->id;
    }

    /**
     * Get product type by name. If type not exists - create new one.
     * @param $name
     * @return int
     */
    public function getTypeIdByName($name) {
        if (isset($this->productTypeCache[$name]))
            return $this->productTypeCache[$name];

        $model = ShopProductType::model()->findByAttributes(array(
            'name' => $name,
        ));

        if (!$model) {
            $model = new ShopProductType;
            $model->name = $name;
            $model->save();
        }

        $this->productTypeCache[$name] = $model->id;

        return $model->id;
    }

    /**
     * Get category id by path. If category not exits it will new one.
     * @param $path string Main/Music/Rock
     * @return integer category id
     */
    protected function getCategoryByPath($path) {
        if (isset($this->categoriesPathCache[$path]))
            return $this->categoriesPathCache[$path];

        if ($this->rootCategory === null)
            $this->rootCategory = ShopCategory::model()->findByPk(1);

        $result = preg_split($this->subCategoryPattern, $path, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $result = array_map('stripcslashes', $result);

        $parent = $this->rootCategory;

        $level = 2; // Level 1 is only root
        foreach ($result as $name) {
            $cr = new CDbCriteria;
            $cr->with = array('cat_translate');
            $cr->compare('cat_translate.name', $name);
            $model = ShopCategory::model()->find($cr);

            if (!$model) {
                $model = new ShopCategory;
                $model->name = $name;
                $model->seo_alias = CMS::translit($name);
                $model->appendTo($parent);
            }

            $parent = $model;
            $level++;
        }

        // Cache category id
        $this->categoriesPathCache[$path] = $model->id;

        if (isset($model))
            return $model->id;
        return 1; // root category
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @param string $eav_prefix
     * @return array
     */
    public function getImportableAttributes($eav_prefix = '') {
        $attributes = array(
            'type' => Yii::t('app', 'Тип'),
            'name' => Yii::t('app', 'Название'),
            'category' => Yii::t('app', 'Категория'),
            'additionalCategories' => Yii::t('app', 'Доп. Категории'),
            'manufacturer' => Yii::t('app', 'Производитель'),
            'sku' => Yii::t('app', 'Артикул'),
            'price' => Yii::t('app', 'Цена'),
            'switch' => Yii::t('app', 'Активен'),
            'images' => Yii::t('app', 'Изображения'),
            'short_description' => Yii::t('app', 'Краткое описание'),
            'full_description' => Yii::t('app', 'Полное описание'),
            // 'seo_title' => Yii::t('app', 'Meta Title'),
            // 'seo_keywords' => Yii::t('app', 'Meta Keywords'),
            //'seo_description' => Yii::t('app', 'Meta Description'),
            'quantity' => Yii::t('app', 'Количество'),
            'availability' => Yii::t('app', 'Доступность'),
            'date_create' => Yii::t('app', 'Дата создания'),
            'date_update' => Yii::t('app', 'Дата обновления'),
            'attributes' => Yii::t('app', 'attributes'),
        );

        foreach (ShopAttribute::model()->findAll() as $attr)
            $attributes[$eav_prefix . $attr->name] = $attr->title;

        return $attributes;
    }

    /**
     * Close file handler
     */
    public function __destruct() {
        if ($this->fileHandler !== null)
            fclose($this->fileHandler);
    }

}
