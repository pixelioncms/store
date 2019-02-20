<?php


class CsvExporter {

    /**
     * @var array
     */
    public $rows = array();

    /**
     * @var string
     */
    public $delimiter = ";";

    /**
     * @var string
     */
    public $enclosure = '"';

    /**
     * Cache category path
     * @var array
     */
    public $categoryCache = array();

    /**
     * @var array
     */
    public $manufacturerCache = array();

    /**
     * @param array $attributes
     */
    public function export(array $attributes) {
        $this->rows[0] = $attributes;

        foreach ($this->rows[0] as &$v) {
            if (substr($v, 0, 4) === 'eav_')
                $v = substr($v, 4);
        }

        $limit = 10;
        $total = ceil(ShopProduct::model()->count() / $limit);
        $offset = 0;

        for ($i = 0; $i <= $total; ++$i) {
            $products = ShopProduct::model()->findAll(array(
                'limit' => $limit,
                'offset' => $offset
                    ));

            foreach ($products as $p) {
                $row = array();

                foreach ($attributes as $attr) {
                    if ($attr === 'category') {
                        $value = $this->getCategory($p);
                    } elseif ($attr === 'manufacturer') {
                        $value = $this->getManufacturer($p);
                    } elseif ($attr === 'image') {
                        $value = $p->attachmentsMain ? $p->attachmentsMain->name : '';
                    } elseif ($attr === 'additionalCategories') {
                        $value = $this->getAdditionalCategories($p);
                    } else {
                        $value = $p->$attr;
                    }

                    $row[$attr] =  iconv('utf-8', 'cp1251', $value); //append iconv by panix
                }

                array_push($this->rows, $row);
            }

            $offset+=$limit;
        }

        $this->proccessOutput();
    }

    /**
     * Get category path
     * @param ShopProduct $product
     * @return string
     */
    public function getCategory(ShopProduct $product) {

        $category = $product->mainCategory;

        if ($category && $category->id == 1)
            return '';

        if (isset($this->categoryCache[$category->id]))
            $this->categoryCache[$category->id];
      // foreach($category->excludeRoot()->ancestors()->findAll() as $test){
       //    VarDumper::dump($test->name);
       //}
        // die();
        $ancestors = $category->excludeRoot()->ancestors()->findAll();
        if (empty($ancestors))
            return $category->name;

        $result = array();
        foreach ($ancestors as $c)
            array_push($result, preg_replace('/\//', '\/', $c->name));
        array_push($result, preg_replace('/\//', '\/', $category->name));

        $this->categoryCache[$category->id] = implode('/', $result);

        return $this->categoryCache[$category->id];
    }

    /**
     * @param ShopProduct $product
     * @return string
     */
    public function getAdditionalCategories(ShopProduct $product) {
        $mainCategory = $product->mainCategory;
        $categories = $product->categories;

        $result = array();
        foreach ($categories as $category) {
            if ($category->id !== $mainCategory->id) {
                $path = array();
                $ancestors = $category->excludeRoot()->ancestors()->findAll();
                foreach ($ancestors as $c)
                    $path[] = preg_replace('/\//', '\/', $c->name);
                $path[] = preg_replace('/\//', '\/', $category->name);
                $result[] = implode('/', $path);
            }
        }

        if (!empty($result))
            return implode(';', $result);
        return '';
    }

    /**
     * Get manufacturer
     */
    public function getManufacturer(ShopProduct $product) {
        if (isset($this->manufacturerCache[$product->manufacturer_id]))
            return $this->manufacturerCache[$product->manufacturer_id];

        $product->manufacturer ? $result = $product->manufacturer->name : $result = '';
        $this->manufacturerCache[$product->manufacturer_id] = $result;
        return $result;
    }

    /**
     * Create CSV file
     */
    public function proccessOutput() {
        header('Content-Type: text/html;charset=UTF-8');//append by panix
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"price.csv\"");
        foreach ($this->rows as $row) {
            foreach ($row as $l)
                echo $this->enclosure . str_replace($this->enclosure, $this->enclosure . $this->enclosure, $l) . $this->enclosure . $this->delimiter;
            echo PHP_EOL;
        }
        Yii::app()->end();
    }

}
