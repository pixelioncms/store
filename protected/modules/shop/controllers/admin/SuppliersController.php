<?php

class SuppliersController extends AdminController {
    public $icon = 'icon-supplier';
    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }


    /**
     * Display statuses list
     */
    public function actionIndex() {
        $model = new ShopSuppliers('search');
        $model->unsetAttributes();

        if (!empty($_GET['ShopSuppliers']))
            $model->attributes = $_GET['ShopSuppliers'];
        $this->pageName = Yii::t('ShopModule.admin', 'SUPPLIERS');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );


        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update status
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new ShopSuppliers;
            $model->unsetAttributes();
        } else {
            $model = ShopSuppliers::model()
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CURRENCY'));


        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_NAME', 0) : $model::t('PAGE_NAME', 1);

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'SUPPLIERS') => $this->createUrl('index'),
            $this->pageName
        );

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopSuppliers'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

}
