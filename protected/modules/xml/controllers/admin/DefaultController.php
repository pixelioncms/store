<?php

ignore_user_abort(1);
set_time_limit(0);
Yii::import('mod.shop.ShopModule');

class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionXml() {
        header('Content-type: application/xml');
        $products = ShopProduct::model()->findAll();
        $productsList = array();
        $productsList['products'] = array();
        foreach ($products as $obj) {
            $productsList['products']['product'][] = array(
                '@attributes' => array(
                    'id' => $obj->id,
                ),
                'name' => $obj->name,
                'price' => $obj->price,
            );
        }


        $xml = Array2XML::createXML('shop', $productsList);
        echo $xml->saveXML();
        Yii::app()->end();
    }

    public function actionIndex() {
        $this->pageName = Yii::t('XmlModule.admin', 'IMPORT_PRODUCTS');


        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $this->render('index');
    }

    /**
     * Import products
     */
    public function actionImport() {

        $this->pageName = Yii::t('XmlModule.admin', 'IMPORT_PRODUCTS');

        $importer = new XmlImporter;
        $importer->deleteDownloadedImages = Yii::app()->request->getPost('remove_images');

        if (Yii::app()->request->isPostRequest && isset($_FILES['file'])) {
            $importer->file = $_FILES['file']['tmp_name'];

            if ($importer->validate() && !$importer->hasErrors()) {
                // Create db backup
                if (isset($_POST['create_dump']) && $_POST['create_dump']) {
                    if (Yii::app()->hasComponent('database')) {
                        $db = Yii::app()->db;
                        if ($db->export()) {
                            Yii::app()->user->setFlash('success', Yii::t('app', 'BACKUP_DB_SUCCESS'));
                        } else {
                            Yii::app()->user->setFlash('error', Yii::t('app', 'BACKUP_DB_ERROR'));
                        }
                    }
                }
                $importer->import();
            }
        }
        $this->render('import', array(
            'importer' => $importer
        ));
    }

    /**
     * Export products
     */
    public function actionExport() {
        $this->pageName = Yii::t('XmlModule.admin', 'EXPORT_PRODUCTS');


        $exporter = new XmlExporter;
        $importer = new XmlImporter;


        if (Yii::app()->request->isPostRequest && isset($_POST['attributes']) && !empty($_POST['attributes']))
            $exporter->export($_POST['attributes']);

        $this->render('export', array(
            'exporter' => $exporter,
            'importer' => $importer,
        ));
    }

    /**
     * Sample xml file
     */
    public function actionSample() {
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"sample.xml\"");
        $productsList = array();
        $attributes = array();
        $attributes['attribute'][] = array(
            '@attributes' => array('name' => 'Размер'),
            '@value' => 39
        );
        $attributes['attribute'][] = array(
            '@attributes' => array('name' => 'Цвет'),
            '@value' => 'Черный'
        );
        $productsList['product'] = array(
            'name' => 'Мой товара',
            'price' => 10,
            'type' => 'Ваш тип товара',
            'manufacturer' => 'Бренд',
            'image' => 'http://example.com/myimage.jpg',
            'category' => 'Категория',
            'attributes' => $attributes
        );
        $xml = Array2XML::createXML('products', $productsList);
        echo $xml->saveXML();
        Yii::app()->end();
    }

}
