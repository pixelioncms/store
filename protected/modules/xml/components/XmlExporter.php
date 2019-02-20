<?php

class XmlExporter {

    /**
     * @var array
     */
    public $rows = array();

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
        /* $this->rows[0] = $attributes;

          foreach ($this->rows[0] as &$v) {
          if (substr($v, 0, 4) === 'eav_')
          $v = substr($v, 4);
          } */
        $limit = 10;
        $total = ceil(ShopProduct::model()->count() / $limit);
        $offset = 0;
        $row = array();
        for ($i = 0; $i <= $total; ++$i) {
            $products = ShopProduct::model()->findAll(array(
                'limit' => $limit,
                'offset' => $offset
            ));


            foreach ($products as $pid => $p) {
         
                foreach ($attributes as $k => $attr) {
                    if ($attr === 'category') {
                        $value = $this->getCategory($p);
                    } elseif ($attr === 'manufacturer') {
                        $value = $this->getManufacturer($p);
                    } elseif ($attr === 'images') {
                        // $value = $this->getImages($p);
                        $row['product'][$pid]['images'] = $this->getImages($p);
                    } elseif ($attr === 'additionalCategories') {
                        $this->getAdditionalCategories($p);
                    } else {
                        if (substr($attr, 0, 4) === 'eav_') {
                            $v = substr($attr, 4);
                            /*$row['product'][$pid]['attributes']['attribute'][] = array(
                                '@attributes' => array('name' => $v),
                                '@value' => (string) $p->$attr
                            );*/
if(!empty($p->$attr)){
                           $row['product'][$pid]['attributes']['attribute'][]=array(
                                '@attributes' => array('name' => $v),
                                '@value' => $p->$attr
                            );
}
                        } else {
                           /*$row['product'][$pid][$attr] = array(
                               //  '@attributes' => array('name' => 'dsadas'),
                                '@value' => (string) $p->$attr
                            );*/
                      
                           if(!empty($p->$attr))
                               $row['product'][$pid][$attr] = $p->$attr;
                        }
                    }
                }
                //print_r($row);
                //die;
                array_push($this->rows, $row);
            }
            $offset+=$limit;
        }

        // print_r($row);die;
        header('Content-Type: text/html;charset=UTF-8');
        header('Content-type: application/xml');
        $xml = Array2XML::createXML('products', $row);
        echo $xml->saveXML();
        Yii::app()->end();


        $this->proccessOutput();
    }

    public function getImages(ShopProduct $product) {
        $images = array();
        if (count($product->images)) {
            foreach ($product->images as $k => $image) {
                if ($image->is_main) {
                    $images['image'][] = array(
                        '@value' => $image->name,
                        '@attributes' => array(
                            'main' => 1
                        )
                    );
                } else {
                    $images['image'][] = array(
                        '@value' => $image->name
                    );
                }
            }
        }

        return $images;
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
     * Create XML file
     */
    public function proccessOutput() {
        $result = array();
        header('Content-Type: text/html;charset=UTF-8');
        header('Content-type: application/xml');
        header('Content-Disposition: attachment; filename="export.xml"');
        header('Content-Transfer-Encoding: binary');
        foreach ($this->rows as $key => $row) {
            // $result['products']['product'][$key] = array('@attributes' => array('id' => 125));
            foreach ($row as $index => $value) {
                if (!empty($value)) {
                    // $result['products'][]=array($k=>$l);
                    //iconv('cp1251', 'utf-8', $value);

                    $result['product'][$key][$index] = $value;
                }
            }
        }
        $xml = Array2XML::createXML('products', $result);
        echo $xml->saveXML();
        Yii::app()->end();
    }

}
