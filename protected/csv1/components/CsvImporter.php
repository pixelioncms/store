<?php

/**
 * Import products from csv format
 * Images must be located at ./uploads/importImages
 */
class CsvImporter extends CComponent {

    /**
     * @var string column delimiter
     */
    public $delimiter = ";";

    /**
     * @var int
     */
    public $maxRowLength = 10000;

    /**
     * @var string
     */
    public $enclosure = '"';

    /**
     * @var string path to file
     */
    public $file;

    /**
     * @var string encoding.
     */
    public $encoding;

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
    protected $csv_columns = array();

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
    protected $line = 1;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var array
     */
    public $stats = array(
        'date_create' => 0,
        'date_update' => 0,
    );

    /**
     * @return bool validate csv file
     */
    public function validate() {
        // Check file exists and readable
        if (is_uploaded_file($this->file)) {
            $newDir = Yii::getPathOfAlias('application.runtime') . '/tmp.csv';
            move_uploaded_file($this->file, $newDir);
            $this->file = $newDir;
        } elseif (file_exists($this->file)) {
            // ok. file exists.
        } else {
            $this->errors[] = array('line' => 0, 'error' => Yii::t('admin', 'Файл недоступен.'));
            return false;
        }

        $file = $this->getFileHandler();

        // Read first line to get attributes
        $line = fgets($file);
        $this->csv_columns = str_getcsv($line, $this->delimiter, $this->enclosure);

        foreach (array('category', 'name', 'type', 'price') as $column) {
            if (!in_array($column, $this->csv_columns))
                $this->errors[] = array('line' => 0, 'error' => Yii::t('admin', 'Укажите обязательную колонку {column}.', array('{column}' => $column)));
        }

        return !$this->hasErrors();
    }

    /**
     * Here we go
     */
    public function import() {
        $file = $this->getFileHandler();
        fgets($file); // Skip first
        // Process lines
        $this->line = 1;

        while (($row = fgetcsv($file, $this->maxRowLength, $this->delimiter, $this->enclosure)) !== false) {
            $row = $this->prepareRow($row);
            $this->importRow($row);
            $this->line++;
        }
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
            $this->stats['date_create']++;
        } else {
            $this->stats['date_update']++;
        }
        $model->seo_alias = CMS::translit($data['name']);
        // Process product type
        $model->type_id = $this->getTypeIdByName($data['type']);

        // Manufacturer
        if (isset($data['manufacturer']) && !empty($data['manufacturer']))
            $model->manufacturer_id = $this->getManufacturerIdByName($data['manufacturer']);

        // Update product variables and eav attributes.
        $attributes = new CsvAttributesProcessor($model, $data);

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
            $model->save(false,false);

            // Update EAV data
            $attributes->save();

            // Update categories
            $model->setCategories($categories, $category_id);

            // Process product main image if product doesn't have one
            if (isset($data['image']) && !empty($data['image'])) {
                $image = CsvImage::create($data['image']);

                if ($image && $model->mainImage === null)
                    $model->addImage($image);
                if ($image && $this->deleteDownloadedImages)
                    $image->deleteTempFile();
            }
        }
        else {
            $errors = $model->getErrors();
            $error = array_shift($errors);

            $this->errors[] = array(
                'line' => $this->line,
                'error' => $error[0],
            );
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
        $parts = explode(';', $str);
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
            $model->save();
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
     * Apply column key to csv row.
     * @param $row array
     * @return array e.g array(key=>value)
     */
    protected function prepareRow($row) {
        $row = array_map('trim', $row);
        $row = array_combine($this->csv_columns, $row);
        $row['date_create'] = date('Y-m-d H:i:s');
        $row['date_update'] = date('Y-m-d H:i:s');
        return array_filter($row); // Remove empty keys and return result
    }

    /**
     * Read csv file.
     * Check encoding. If !utf8 - convert.
     * @return resource csv file
     */
    protected function getFileHandler() {
        $test_content = file_get_contents($this->file);
        $is_utf8 = mb_detect_encoding($test_content, 'UTF-8', true);

        if ($is_utf8 == false) {
            // Convert all file content to utf-8 encoding
            $content = iconv('cp1251', 'utf-8', $test_content);
            $this->fileHandler = tmpfile();
            fwrite($this->fileHandler, $content);
            fseek($this->fileHandler, 0);
        }else
            $this->fileHandler = fopen($this->file, 'r');
        return $this->fileHandler;
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
            'image' => Yii::t('app', 'Главное изображение'),
            'short_description' => Yii::t('app', 'Краткое описание'),
            'full_description' => Yii::t('app', 'Полное описание'),
            //'seo_title' => Yii::t('app', 'Meta Title'),
            //'seo_keywords' => Yii::t('app', 'Meta Keywords'),
           // 'seo_description' => Yii::t('app', 'Meta Description'),
            'quantity' => Yii::t('app', 'Количество'),
            'availability' => Yii::t('app', 'Доступность'),
            'date_create' => Yii::t('app', 'Дата создания'),
            'date_update' => Yii::t('app', 'Дата обновления'),
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
