<?php

/**
 * 1. console forsage init --offset=0 --limit=10
 * 2. console forsage init --offset=10 --limit=10
 * 2. console forsage init --offset=20 --limit=10
 * etc.
 *
 *
 * console forsage init All suppliers import
 *
 *  cmd change charset "chcp 65001"
 */
Yii::import('app.forsage.*');

class ForsageCommand extends ConsoleCommand
{

    public function beforeAction($action, $params)
    {
        $forsage = new ForsageProductsImport;
        if (!file_exists(Yii::getPathOfAlias($forsage->tempDirectory))) {
            CFileHelper::createDirectory(Yii::getPathOfAlias($forsage->tempDirectory));
        }

        return parent::beforeAction($action, $params);
    }

    public function actionChanges2()
    {
        Yii::import('app.php-multi-curl.*');

        $forsage = new ForsageProductsImport;
        // $products = $forsage->getSupplierProductIds(505);

        $products = $forsage->getChanges();

        $options = [
            CURLOPT_TIMEOUT => 1000,
            CURLOPT_CONNECTTIMEOUT => 5000,
            CURLOPT_USERAGENT => 'Multi-cURL client v1.5.0',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
        ];

        if ($products) {
            $c = array();
            echo count($products) . PHP_EOL;
            $chunkProducts = array_chunk($products, 100, true);
            //$chunkProducts = array_chunk($products, 100, true);

            $i = 0;

            foreach ($chunkProducts as $k2 => $products1) {
                echo 'Page: ' . $k2 . PHP_EOL;
                $c = array();
                foreach ($products1 as $k => $product_id) {
                    $c[$k] = new Curl($options);
                    $c[$k]->makeGet("https://forsage-studio.com/api/get_product/{$product_id}?token={$forsage->apikey}");
                }
                $mc = new MultiCurl();

                $mc->addCurls($c);
                $allSuccess = $mc->exec();
                if ($allSuccess) {
                    foreach ($c as $resp) {
                        $i++;
                        $data = CJSON::decode($resp->getResponse()->getBody(), false);
                        //$forsage->insert_update($data->product);
                        $forsage->insert_update($data->product, 1);
                    }
                } else {
                    foreach ($c as $resp) {
                        var_dump($resp->getResponse()->getError());
                    }
                }
                $mc->reset();

            }
            echo 'Total items: ' . $i . PHP_EOL;
        }
    }

    public function actionChanges()
    {
        $forsage = new ForsageProductsImport;
        $forsage->change();
        Yii::log('ForsageCommand actionChanges start', 'info', 'console');
    }

    public function actionSupplierProducts($id = false)
    {
        if ((int)$id) {
            Yii::log('ForsageCommand actionSupplierProducts start', 'info', 'console');
            $forsage = new ForsageProductsImport;
            $products = $forsage->getSupplierProductIds((int)$id);
            if ($products) {
                $log = "Products count: " . count($products);
                echo $log . PHP_EOL;
                Yii::log($log, 'info', 'console');
                foreach ($products as $product_id) {
                    $product = $forsage->getProduct((int)$product_id);
                    $forsage->insert_update($product);
                }
            }
            Yii::log('ForsageCommand actionSupplierProducts end', 'info', 'console');

        }
    }


    public function actionAddProduct($id = false, $insert = true)
    {
        if ($id) {
            $forsage = new ForsageProductsImport;
            $product = $forsage->getProduct($id);
            if ($insert)
                $forsage->insert_update($product);
            else
                print_r($product);
            Yii::log('ForsageCommand actionAddProduct start', 'info', 'console');
        }

    }

    public function actionAddSuppliers()
    {
        $forsage = new ForsageProductsImport;
        $suppliers = $forsage->getSuppliers();
        print_r($suppliers);
        if ($suppliers) {

        }
        Yii::log('ForsageCommand actionAddSuppliers start', 'info', 'console');
    }

    public function actionProducts()
    {
        $forsage = new ForsageProductsImport;
        $forsage->products();
        Yii::log('ForsageCommand actionProducts start', 'info', 'console');
    }

    public function actionImportAll($offset = 0, $limit = 10)
    {
        $forsage = new ForsageProductsImport;
        $forsage->importAll($offset, $limit);
        Yii::log('ForsageCommand actionImportAll start', 'info', 'console');

    }

    public function actionTestInsert()
    {
        $builder=Yii::app()->db->schema->commandBuilder;
        $rows=array();
        $forsage = new ForsageProductsImport;
        $products = $forsage->getSupplierProductIds(505);
        if ($products) {
            echo 'Count: '.count($products).PHP_EOL;
            foreach ($products as $product_id) {
               // $product = $forsage->getProduct((int)$product_id);

               // $rows[] =array('title' => $product->vcode, 'text' => 'text');
            }
        }



       // for ($x=0; $x<500000; $x++){
           // $rows[] =array('title' => 'record '.$x, 'text' => 'text'.$x);
       // }

        $command=$builder->createMultipleInsertCommand('{{testtable}}', $rows);
        $command->execute();
    }

    public function actionSetsql()
    {

        $command = Yii::app()->db->createCommand();


        //Clear table
        $command->truncateTable('{{exchange_forsage}}');

        $start = microtime(true);
        $i = 0;
        echo "INSERT INTO `" . Yii::app()->db->tablePrefix . "exchange_forsage` (`id`, `object_id`, `object_type`, `external_id`) VALUES" . PHP_EOL;

        //Size
        $attributes = ShopAttribute::model()->findAll();
        foreach ($attributes as $a) {
            $i++;
            $command->insert('{{exchange_forsage}}', array(
                'object_id' => $a->id,
                'object_type' => ForsageExternalFinder::OBJECT_TYPE_ATTRIBUTE,
                'external_id' => $a->title,
            ));
            echo "(NULL," . $a->id . "," . ForsageExternalFinder::OBJECT_TYPE_ATTRIBUTE . ",'" . $a->title . "')," . PHP_EOL;
            foreach ($a->options as $o) {
                $i++;
                $command->insert('{{exchange_forsage}}', array(
                    'object_id' => $o->id,
                    'object_type' => ForsageExternalFinder::OBJECT_TYPE_ATTRIBUTE_OPTION,
                    'external_id' => $o->value,
                ));
                echo "(NULL," . $o->id . "," . ForsageExternalFinder::OBJECT_TYPE_ATTRIBUTE_OPTION . ",'" . $o->value . "')," . PHP_EOL;
            }
        }


        //Manufacturer
        $manufacturers = ShopManufacturer::model()->findAll();
        foreach ($manufacturers as $a) {
            $i++;
            $command->insert('{{exchange_forsage}}', array(
                'object_id' => $a->id,
                'object_type' => ForsageExternalFinder::OBJECT_TYPE_MANUFACTURER,
                'external_id' => $a->name,
            ));
            echo "(NULL," . $a->id . "," . ForsageExternalFinder::OBJECT_TYPE_MANUFACTURER . ",'" . $a->name . "')," . PHP_EOL;
        }


        //Categories
        $categories = ShopCategory::model()
            ->findByPk(1);
        $results = $categories->menuArray();
        foreach ($results['items'] as $a) {
            $i++;
            echo "(NULL," . $a['id'] . "," . ForsageExternalFinder::OBJECT_TYPE_MAIN_CATEGORY . ",'" . $a['label'] . "')," . PHP_EOL;
            $command->insert('{{exchange_forsage}}', array(
                'object_id' => $a['id'],
                'object_type' => ForsageExternalFinder::OBJECT_TYPE_MAIN_CATEGORY,
                'external_id' => $a['label'],
            ));
            if (isset($a['items'])) {
                foreach ($a['items'] as $b) {
                    $i++;
                    $command->insert('{{exchange_forsage}}', array(
                        'object_id' => $b['id'],
                        'object_type' => ForsageExternalFinder::OBJECT_TYPE_CATEGORY,
                        'external_id' => $b['label'],
                    ));
                    echo "(NULL," . $b['id'] . "," . ForsageExternalFinder::OBJECT_TYPE_CATEGORY . ",'" . $b['label'] . "')," . PHP_EOL;
                }
            }
        }


    }


    public function actionGetSql()
    {

        $start = microtime(true);
        $i = 0;
        echo "INSERT INTO `" . Yii::app()->db->tablePrefix . "exchange_forsage` (`id`, `object_id`, `object_type`, `external_id`) VALUES" . PHP_EOL;

        //Size
        $attributes = ShopAttribute::model()->findAll();
        foreach ($attributes as $a) {
            $i++;
            echo "(NULL," . $a->id . "," . ForsageExternalFinder::OBJECT_TYPE_ATTRIBUTE . ",'" . $a->title . "')," . PHP_EOL;
            foreach ($a->options as $o) {
                $i++;
                echo "(NULL," . $o->id . "," . ForsageExternalFinder::OBJECT_TYPE_ATTRIBUTE_OPTION . ",'" . $o->value . "')," . PHP_EOL;
            }
        }


        //Manufacturer
        $manufacturers = ShopManufacturer::model()->findAll();
        foreach ($manufacturers as $a) {
            $i++;
            echo "(NULL," . $a->id . "," . ForsageExternalFinder::OBJECT_TYPE_MANUFACTURER . ",'" . $a->name . "')," . PHP_EOL;
        }


        //Categories
        $categories = ShopCategory::model()
            ->findByPk(1);
        $results = $categories->menuArray();

        $this->recursiveGetSql($results['items']);

        foreach ($results['items'] as $a) {
            $i++;
            //echo "(NULL," . $a['id'] . "," . ForsageExternalFinder::OBJECT_TYPE_MAIN_CATEGORY . ",'" . $a['label'] . "')," . PHP_EOL;
            /*if (isset($a['items'])) {
                foreach ($a['items'] as $b) {
                    $i++;
                    echo "(NULL," . $b['id'] . "," . ForsageExternalFinder::OBJECT_TYPE_CATEGORY . ",'" . $a['label'] . '/' . $b['label'] . "')," . PHP_EOL;
                }
            }*/
        }


        echo 'PageLoad: ' . (microtime(true) - $start) . ' sec.';
        Yii::log('ForsageCommand get sql', 'info', 'console');

    }

    private function recursiveGetSql(array $items)
    {
        if (isset($items)) {
            foreach ($items as $item) {
//$item['url']['seo_alias']
                echo "(NULL," . $item['id'] . "," . ForsageExternalFinder::OBJECT_TYPE_CATEGORY . ",'" . $item['label'] . "')," . PHP_EOL;
                if (isset($item['items']))
                    $this->recursiveGetSql($item['items']);
            }
        }
    }

    public function addSupplier()
    {

        $forsage = new ForsageProductsImport;


        foreach ($forsage->supplierList as $supplier_id => $supplier_name) {

            $model = ShopManufacturer::model()->findByAttributes(array('name' => $supplier_name));
            if ($model) {
                Yii::app()->db->createCommand()->insert('{{exchange_forsage}}', array(
                    'object_type' => ForsageExternalFinder::OBJECT_TYPE_MANUFACTURER,
                    'object_id' => $model->id,
                    'external_id' => $model->name
                ));
            } else {
                $manufacturer = ForsageExternalFinder::getObject(ForsageExternalFinder::OBJECT_TYPE_MANUFACTURER, $supplier_id);
                if (!$manufacturer) { //new
                    $manufacturer = new ShopManufacturer;
                    $manufacturer->name = $supplier_name;
                    $manufacturer->seo_alias = CMS::translit($manufacturer->name);
                    $manufacturer->save(false, false, false);
                    Yii::app()->db->createCommand()->insert('{{exchange_forsage}}', array(
                        'object_type' => ForsageExternalFinder::OBJECT_TYPE_MANUFACTURER,
                        'object_id' => $manufacturer->id,
                        'external_id' => $supplier_name
                    ));
                }
            }
        }
    }

    public function actionCreateTable()
    {
        $command = Yii::app()->db->createCommand();
        $command->createTable('{{exchange_forsage}}', array(
            'id' => 'int(11) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT',
            'object_id' => 'int(11) unsigned DEFAULT NULL',
            'object_type' => 'int(11) unsigned DEFAULT NULL',
            'external_id' => 'varchar(255)'
        ));


        $command->createIndex('object_id', '{{exchange_forsage}}', 'object_id');
        $command->createIndex('object_type', '{{exchange_forsage}}', 'object_type');
    }
}
