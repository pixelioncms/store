<?php

ignore_user_abort(1);
set_time_limit(0);
Yii::import('mod.shop.ShopModule');

class DefaultController extends AdminController
{

    public $topButtons = false;

    public function actionIndex()
    {
        $this->pageName = Yii::t('CsvModule.admin', 'IMPORT_PRODUCTS');


        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $this->render('index');
    }

    /**
     * Import products
     */
    public function actionImport()
    {

        $this->pageName = Yii::t('CsvModule.admin', 'IMPORT_PRODUCTS');
        $this->topButtons = array(array(
            'label' => Yii::t('CsvModule.admin', 'EXPORT'),
            'url' => $this->createUrl('/admin/csv/default/export'),
            'htmlOptions' => array('class' => 'btn btn-success')
        ));
        $importer = new CsvImporter;
        $importer->deleteDownloadedImages = Yii::app()->request->getPost('remove_images');

        if (Yii::app()->request->isPostRequest && isset($_FILES['file'])) {
            $importer->file = $_FILES['file']['tmp_name'];

            if ($importer->validate() && !$importer->hasErrors()) {
                // Create db backup
                if (isset($_POST['create_dump']) && $_POST['create_dump']) {
                    $dumper = new DatabaseDumper;

                    $file = Yii::getPathOfAlias('webroot.protected.backups') . DS . 'dump_' . date('Y-m-d_H_i_s') . '.sql';

                    if (is_writable(Yii::getPathOfAlias('webroot.protected.backups'))) {
                        if (function_exists('gzencode'))
                            file_put_contents($file . '.gz', gzencode($dumper->getDump()));
                        else
                            file_put_contents($file, $dumper->getDump());
                    } else
                        throw new CHttpException(503, Yii::t('CsvModule.admin', 'ERROR_WRITE_BACKUP'));
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
    public function actionExport()
    {
        $this->pageName = Yii::t('CsvModule.admin', 'EXPORT_PRODUCTS');
        $exporter = new CsvExporter;

        $this->topButtons = array(array(
            'label' => Yii::t('CsvModule.admin', 'IMPORT'),
            'url' => $this->createUrl('/admin/csv/default/import'),
            'htmlOptions' => array('class' => 'btn btn-success')
        ));
        $pagination=false;
        $dataProvider = null;
        if (Yii::app()->request->getQuery('manufacturer_id')) {
            $query = new ShopProduct(null);
            $manufacturers = Yii::app()->request->getParam('manufacturer_id');
            //  if(Yii::app()->request->getQuery('manufacturer_id')!=='all'){
            // $manufacturers = explode(',', Yii::app()->request->getParam('manufacturer_id'));

            //  }
            if ($manufacturers && $manufacturers!='all')
                $query->applyManufacturers($manufacturers);
            //if (Yii::app()->request->getParam('page')) {
            //    $_GET['page'] = Yii::app()->request->getParam('page');
           // }

            $dataProvider = new ActiveDataProvider($query, array(
                'id' => false,
                'pagination' => array(
                    'pageSize' => (int)Yii::app()->settings->get('csv', 'pagenum'),
                ),
            ));



            $criteria = new CDbCriteria();
            $pagination = new CPagination($dataProvider->totalItemCount);
            $pagination->pageSize=(int)Yii::app()->settings->get('csv', 'pagenum');
            $pagination->applyLimit($criteria);

        }


        if (Yii::app()->request->getPost('attributes')) {

            $exporter->export(Yii::app()->request->getPost('attributes'), $dataProvider);
        }


        $this->render('export', array(
            'dataProvider' => $dataProvider,
            'exporter' => $exporter,
            'pagination'=>$pagination,
            'importer' => new CsvImporter,
        ));
    }
    /*
        public function actionExportRun() {
            $exporter = new CsvExporter;
            if (Yii::app()->request->isPostRequest && isset($_POST['attributes']) && !empty($_POST['attributes'])) {
                $exporter->export($_POST['attributes'], Yii::app()->request->getQuery('manufacturer_id'));
            }
        }
    */
    /**
     * Sample csv file
     */
    public function actionSample()
    {
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"sample.csv\"");
        echo '"name";"category";"price";"type"' . "\n";
        echo '"Product Name";"Category/Subcategory";"10.99";"Base Product"' . "\n";
        Yii::app()->end();
    }

    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/csv/settings/index'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
