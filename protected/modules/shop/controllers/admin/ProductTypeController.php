<?php

/**
 * Admin product types
 */
class ProductTypeController extends AdminController
{

    public $icon = 'icon-t';

    /**
     * Display types list
     */
    public function actionIndex()
    {
        $model = new ShopProductType('search');

        $this->pageName = Yii::t('ShopModule.admin', 'TYPE_PRODUCTS');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $this->topButtons = array(array('label' => $model::t('CREATE_TYPE'),
            'url' => $this->createUrl('create'),
            'icon' => 'icon-add',
            'htmlOptions' => array('class' => 'btn btn-success')));

        if (!empty($_GET['ShopProductType']))
            $model->attributes = $_GET['ShopProductType'];

        $dataProvider = $model->orderByName()->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update product type
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false)
    {
        if ($new === true)
            $model = new ShopProductType;
        else
            $model = ShopProductType::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_TYPEPRODUCT'));


        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/productType.update.js', CClientScript::POS_END);


        $this->pageName = ($model->isNewRecord) ? Yii::t('ShopModule.admin', 'Создание нового типа продукта') :
            Yii::t('ShopModule.admin', 'Редактирование типа продукта');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'TYPE_PRODUCTS') => $this->createUrl('index'),
            $this->pageName
        );

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopProductType'];

            if (isset($_POST['categories']) && !empty($_POST['categories'])) {
                $model->categories_preset = serialize($_POST['categories']);
                $model->main_category = $_POST['main_category'];
            } else {
                //return defaults when all checkboxes were checked off
                $model->categories_preset = null;
                $model->main_category = 0;
            }

            if ($model->validate()) {
                $model->save();
                // Set type attributes
                $model->useAttributes(Yii::app()->request->getPost('attributes', array()));
                $this->redirect('index');
            }
        }

        $cr = new CDbCriteria;
       // $cr->addNotInCondition('t.id', Html::listData($model->attributeRelation, 'attribute_id', 'attribute_id')); //ShopAttribute.id
        $allAttributes = ShopAttribute::model()->findAll($cr);

        $this->render('update', array(
            'model' => $model,
            'attributes' => $allAttributes,
        ));
    }

    /**
     * Delete type
     * @param array $id
     */
    public function actionDelete($id = array())
    {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopProductType::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->productsCount > 0) {
                        throw new CHttpException(404, Yii::t('ShopModule.admin', 'ERR_DEL_TYPE_PRODUCT'));
                    } else {
                        $m->delete();
                    }
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

}
