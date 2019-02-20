<?php

class AttributeGroupsController extends AdminController {

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ShopAttributeGroups::model(),
            ),
        );
    }

    public function actionIndex() {
        $model = new ShopAttributeGroups('search');

        if (!empty($_GET['ShopAttributeGroups']))
            $model->attributes = $_GET['ShopAttributeGroups'];

        $this->pageName = Yii::t('ShopModule.admin', 'ATTRIBUTES_GROUP');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'ATTRIBUTES') => array('/admin/shop/attribute'),
            $this->pageName
        );

        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update attribute
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        $this->topButtons = false;
        if ($new === true)
            $model = new ShopAttributeGroups;
        else {
            $model = ShopAttributeGroups::model()
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_ATTR'));



        $this->pageName = ($model->isNewRecord) ? $model::t('ISNEW', 0) : $model::t('ISNEW', 1);

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'ATTRIBUTES') => array('/admin/shop/attribute'),
            Yii::t('ShopModule.admin', 'ATTRIBUTES_GROUP') => $this->createUrl('index'),
            $this->pageName
        );


        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopAttributeGroups'];
            if ($model->validate()) {
                $model->save();
                if ($new) {
                    $this->redirect(array('index'));
                } else {
                    $this->redirect(array('update', 'id' => $_GET['id']));
                }
            }
        }

        $this->render('update', array('model' => $model));
    }

}
