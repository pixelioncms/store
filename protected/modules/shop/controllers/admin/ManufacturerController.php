<?php

class ManufacturerController extends AdminController {

    public $icon = 'icon-apple';

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ShopManufacturer::model(),
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    /**
     * Display manufacturers list
     */
    public function actionIndex() {
        $model = new ShopManufacturer('search');

        if (!empty($_GET['ShopManufacturer']))
            $model->attributes = $_GET['ShopManufacturer'];

        $this->pageName = Yii::t('ShopModule.admin', 'BRANDS');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        if (Yii::app()->user->openAccess(array("{$this->module->id}.Manufacturer.*", "{$this->module->id}.Manufacturer.Create"))) {
            $this->topButtons = array(
                array('label' => Yii::t('ShopModule.admin', 'Создать производителя'),
                    'url' => $this->createUrl('create'),
                    'icon'=>'icon-add',
                    'htmlOptions' => array('class' => 'btn btn-success')
                )
            );
        }
        $dataProvider = $model->orderByName()->search();


        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update manufacturer
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {

        if ($new === true) {
            $model = new ShopManufacturer;
        } else {
            $model = ShopManufacturer::model()
                    ->findByPk($_GET['id']);
        }
        $this->topButtons = false;
        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_BRAND'));


        if (!$model->isNewRecord) {
            $this->topButtons = array(array(
                    'label' => Yii::t('ShopModule.admin', 'VIEW_BRAND'),
                    'url' => $model->getUrl(),
                    'htmlOptions' => array('class' => 'btn btn-primary','target'=>'_blank')
            ));
        }

        // $oldImage = $model->image;

        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_NAME', 0) : $model::t('PAGE_NAME', 1);

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'BRANDS') => $this->createUrl('index'),
            $this->pageName
        );


        $form = new TabForm($model->getForm(), $model);

        $form->additionalTabs[Yii::t('app', 'TAB_META')] = array(
            'content' => $this->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $model, 'form' => $form), true)
        );

        if (isset($_POST['ShopManufacturer'])) {
            $model->attributes = $_POST['ShopManufacturer'];

            if ($model->validate()) {
                //$model->image='laslsalsa';
                //    $model->uploadFile('image', 'webroot.uploads.manufacturer', $oldImage);
                $model->save();
                $this->redirect('index');
            }
        }
        $this->render('update', array('model' => $model, 'form' => $form));
    }

}
